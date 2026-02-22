<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Students\StudentResource;
use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use App\Models\Student;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;

class WeeklyStudentAttendance extends TableWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Kehadiran Siswa Minggu Ini';
    protected static ?int $sort = 1;


    public function table(Table $table): Table
    {
        $user = auth()->user();
        $childrenIds = $user->guardian?->students()->pluck('students.id')->toArray() ?? [];

        return $table
            ->query(
                Student::query()
                    ->with(['attendances' => fn($q) => $q->whereBetween('date', [now()->subMonth(), now()->addDay()])])
                    ->with('attendances.class')
                    ->withCount([
                        'attendances as hadir_count' => fn($q) => $q->where('status', 'hadir'),
                        'attendances as izin_count' => fn($q) => $q->where('status', 'izin'),
                        'attendances as sakit_count' => fn($q) => $q->where('status', 'sakit'),
                        'attendances as dispensasi_count' => fn($q) => $q->where('status', 'dispensasi'),
                    ])
                    ->whereIn('id', $childrenIds)
                    ->where('is_active', true)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Anak')
                    ->sortable(),
                    // ->searchable(),

                TextColumn::make('attendances.class.name')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->badge()
                    ->color('success'),

                TextColumn::make('izin_count')
                    ->label('Izin')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('sakit_count')
                    ->label('Sakit')
                    ->badge()
                    ->color('info'),

                TextColumn::make('dispensasi_count')
                    ->label('Dispensasi')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                // SelectFilter::make('class')
                //     ->label('Kelas')
                //     ->relationship('attendances.class', 'name')
                //     ->searchable()
                //     ->multiple()
                //     ->preload(),
            ])
            ->actions([
                ViewAction::make('view')
                    ->label('Detail')
                    ->url(fn($record) => StudentResource::getUrl('view', [
                        'record' => $record->id,
                    ]))
                    ->icon('heroicon-o-eye'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}