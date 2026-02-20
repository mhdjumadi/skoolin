<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TeachingSchedule;
use App\Models\TeachingJournal;
use App\Models\AcademicYear;
use Carbon\Carbon;

class TeacherClassAttendance extends TableWidget
{
    protected static ?string $heading = 'Kehadiran Guru per Kelas Hari Ini';

    // Full width di dashboard
    // protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $today = now()->toDateString();

        // Ambil semua jurnal hari ini sekaligus, termasuk end_time
        $journalsToday = TeachingJournal::whereDate('date', $today)
            ->get(['teaching_schedule_id', 'end_time'])
            ->keyBy('teaching_schedule_id');

        return $table
            ->query(fn(): Builder => $this->getSchedulesQuery())
            ->columns([
                TextColumn::make('lessonPeriod.number')
                    ->label('Jam Ke')
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                // TextColumn::make('subject.name')
                //     ->label('Mata Pelajaran')
                //     ->searchable(),

                // TextColumn::make('teacher.user.name')
                //     ->label('Guru')
                //     ->searchable(),

                TextColumn::make('status')
                    ->label('Status Mengajar')
                    ->getStateUsing(function ($record) use ($journalsToday) {
                        if (!isset($journalsToday[$record->id])) {
                            return '❌ Tidak Ada Guru';
                        }

                        return $journalsToday[$record->id]->end_time
                            ? '✅ Selesai'
                            : '⏳ Proses Mengajar';
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            '✅ Sudah Mengajar' => 'success',
                            '⏳ Sedang Mengajar' => 'warning',
                            default => 'danger',
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('class')
                    ->label('Filter Kelas')
                    ->relationship('class', 'name'),

                SelectFilter::make('teacher')
                    ->label('Filter Guru')
                    ->relationship('teacher.user', 'name'),
            ])
            ->actions([
                ViewAction::make('view')
                    ->label('Detail')
                    ->url(fn($record) => TeachingScheduleResource::getUrl('view', [
                        'record' => $record->id,
                    ]))
                    ->icon('heroicon-o-eye'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }

    protected function getSchedulesQuery(): Builder
    {
        $todayNumber = now()->dayOfWeekIso; // 1 = Senin ... 7 = Minggu

        $activeYear = AcademicYear::where('is_active', true)->first();

        return TeachingSchedule::query()
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber))
            ->with(['class', 'lessonPeriod', 'subject', 'teacher.user']);
    }
}