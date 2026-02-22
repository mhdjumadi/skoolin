<?php

namespace App\Filament\Resources\TeachingJournals\Tables;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use Auth;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                TextColumn::make('teachingSchedule.startPeriod.number')
                    ->label('Jam Mengajar')
                    ->formatStateUsing(function ($state, $record) {

                        $schedule = $record->teachingSchedule;

                        $startNumber = $schedule?->startPeriod?->number;
                        $endNumber = $schedule?->endPeriod?->number;

                        $startTime = $schedule?->startPeriod?->start_time;
                        $endTime = $schedule?->endPeriod?->end_time;

                        if (!$startNumber || !$endNumber || !$startTime || !$endTime) {
                            return '-';
                        }

                        $rangeNumber = $startNumber == $endNumber
                            ? $startNumber
                            : "{$startNumber}-{$endNumber}";

                        $startFormatted = Carbon::parse($startTime)->format('H.i');
                        $endFormatted = Carbon::parse($endTime)->format('H.i');

                        return "{$rangeNumber} - ({$startFormatted} - {$endFormatted})";
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
                SelectFilter::make('day_id')
                    ->label('Hari')
                    ->relationship('teachingSchedule.day', 'name'),


                SelectFilter::make('teacher_id')
                    ->label('Guru')
                    ->relationship('teachingSchedule.teacher.user', 'name'),

                SelectFilter::make('class_id')
                    ->label('Kelas')
                    ->relationship('teachingSchedule.class', 'name'),

                SelectFilter::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship('teachingSchedule.subject', 'name'),

            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->hasRole('super_admin')),
                ]),
            ]);
    }
}
