<?php

namespace App\Filament\Widgets\Admin;

use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
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
    use HasWidgetShield;
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Kehadiran Guru per Kelas Hari Ini';

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
                TextColumn::make('startPeriod.number')
                    ->label('Jam Ke')
                    ->formatStateUsing(function ($state, $record) {
                        $startNumber = $record->startPeriod?->number;
                        $endNumber = $record->endPeriod?->number;

                        if (!$startNumber || !$endNumber) {
                            return '-';
                        }

                        // Jika start=end, tampilkan satu nomor, kalau beda tampilkan range
                        return $startNumber === $endNumber
                            ? (string)$startNumber
                            : "{$startNumber}-{$endNumber}";
                    })
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status Mengajar')
                    ->getStateUsing(function ($record) use ($journalsToday) {
                        if (!isset($journalsToday[$record->id])) {
                            return 'Tidak ada guru';
                        }

                        return $journalsToday[$record->id]->end_time
                            ? 'Selesai'
                            : 'Sedang mengajar';
                    })
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'Selesai' => 'success',
                            'Sedang mengajar' => 'warning',
                            default => 'danger',
                        };
                    }),
            ])
            ->filters([
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
        $user = auth()->user();

        return TeachingSchedule::query()
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber))

            // 👇 Jika login sebagai guru
            ->when(
                $user->hasRole('teacher') && $user->teacher,
                fn($q) => $q->where('teacher_id', $user->teacher->id)
            )

            ->with(['class', 'lessonPeriod', 'subject', 'teacher.user']);
    }
}