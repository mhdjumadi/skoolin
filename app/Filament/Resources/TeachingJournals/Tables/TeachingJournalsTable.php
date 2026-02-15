<?php

namespace App\Filament\Resources\TeachingJournals\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeachingJournalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Hari / Tanggal')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->teachingSchedule->day->name
                            . ' - ' .
                            Carbon::parse($state)->translatedFormat('d M Y');
                    })
                    ->sortable(),
                TextColumn::make('teachingSchedule.lessonPeriod.number')
                    ->label('Jam ke')
                    ->searchable(),
                TextColumn::make('teachingSchedule.lessonPeriod.number')
                    ->label('Jam Mengajar')
                    ->formatStateUsing(function ($state, $record) {
                        $start = Carbon::parse($record->teachingSchedule->lessonPeriod->start_time)->format('H.i');
                        $end = Carbon::parse($record->teachingSchedule->lessonPeriod->end_time)->format('H.i');

                        return "{$state} - ({$start} - {$end})";
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teachingSchedule.teacher.user.name')
                    ->label('Guru')
                    ->searchable(),
                TextColumn::make('teachingSchedule.subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('material')
                    ->label('Materi')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('present_count')
                    ->label('Hadir')
                    ->badge()
                    ->color('success'),

                TextColumn::make('sick_count')
                    ->label('Sakit')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('excused_count')
                    ->label('Izin')
                    ->badge()
                    ->color('info'),

                TextColumn::make('absent_count')
                    ->label('Alpha')
                    ->badge()
                    ->color('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
