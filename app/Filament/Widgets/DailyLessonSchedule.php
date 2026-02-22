<?php

namespace App\Filament\Widgets;

use App\Models\TeachingSchedule;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class DailyLessonSchedule extends TableWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Jadwal Pelajaran Siswa Hari Ini';
    protected static ?int $sort = 2;


    public function table(Table $table): Table
    {
        // Ambil id kelas anak dari orang tua
        $childrenClassIds = auth()->user()?->guardian?->students?->pluck('class_id')->unique()->toArray() ?? [];

        return $table
            ->query(
                TeachingSchedule::query()
                    ->with(['lessonPeriod', 'class', 'subject', 'teacher.user'])
                    ->whereDate('date', now())
                    ->whereIn('class_id', $childrenClassIds)
            )
            ->columns([
                TextColumn::make('lessonPeriod.number')
                    ->label('Jam Ke')
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Kelas')
                    // ->searchable()
                    ->sortable(),

                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),
                // ->searchable(),

                TextColumn::make('teacher.user.name')
                    ->label('Guru'),
                // ->searchable(),

                // TextColumn::make('date')
                //     ->label('Tanggal')
                //     ->date('d M Y')
                //     ->sortable(),

                // TextColumn::make('lessonPeriod.start_time')
                //     ->label('Mulai')
                //     ->time('H:i'),

                // TextColumn::make('lessonPeriod.end_time')
                //     ->label('Selesai')
                //     ->time('H:i'),
            ])
            ->filters([
                // SelectFilter::make('class')
                //     ->label('Kelas')
                //     ->relationship('class', 'name')
                //     ->multiple()
                //     ->searchable()
                //     ->preload(),

                // SelectFilter::make('teacher')
                //     ->label('Guru')
                //     ->relationship('teacher.user', 'name')
                //     ->multiple()
                //     ->searchable()
                //     ->preload(),
            ])
            ->actions([
                // ViewAction::make('view')
                //     ->label('Detail')
                //     ->icon('heroicon-o-eye')
                //     ->url(fn($record) => TeachingScheduleResource::getUrl('view', ['record' => $record->id])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
