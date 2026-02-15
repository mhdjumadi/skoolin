<?php

namespace App\Filament\Resources\TeachingSchedules\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeachingSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day.name')
                    ->label('Hari')
                    ->searchable(),
                TextColumn::make('lessonPeriod.number')
                    ->label('Jam Mengajar')
                    ->formatStateUsing(function ($state, $record) {
                        $start = Carbon::parse($record->lessonPeriod->start_time)->format('H.i');
                        $end = Carbon::parse($record->lessonPeriod->end_time)->format('H.i');

                        return "{$state} - ({$start} - {$end})";
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('teacher.user.name')
                    ->label('Guru')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('academicYear.name')
                    ->label('Tahun Ajaran')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
