<?php

namespace App\Filament\Resources\TeachingSchedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class TeachingScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Jadwal Mengajar')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TextEntry::make('academicYear.name')
                            ->label('Tahun Ajaran')
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('class.name')
                            ->label('Kelas')
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-home-modern'),

                        TextEntry::make('teacher.user.name')
                            ->label('Guru Pengampu')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-user'),

                        TextEntry::make('subject.name')
                            ->label('Mata Pelajaran')
                            ->badge()
                            ->color('warning')
                            ->icon('heroicon-o-book-open'),

                        TextEntry::make('lessonPeriod.number')
                            ->label('Jam Mengajar')
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-clock')
                            ->formatStateUsing(function ($state, $record) {
                                if (!$record->lessonPeriod?->start_time || !$record->lessonPeriod?->end_time) {
                                    return "Jam {$state}";
                                }

                                $start = \Carbon\Carbon::parse($record->lessonPeriod->start_time)->format('H:i');
                                $end = \Carbon\Carbon::parse($record->lessonPeriod->end_time)->format('H:i');

                                return "{$state} ({$start} - {$end})";
                            }),

                        TextEntry::make('day.name')
                            ->label('Hari')
                            ->badge()
                            ->color('secondary')
                            ->icon('heroicon-o-calendar-days'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->since()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->since()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
