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
                Section::make('Informasi Jadwal Mengajar')
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
                            ->label('Guru')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-user'),

                        TextEntry::make('subject.name')
                            ->label('Mata Pelajaran')
                            ->badge()
                            ->color('warning')
                            ->icon('heroicon-o-book-open'),

                        TextEntry::make('startPeriod.number')
                            ->label('Jam Mengajar')
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-clock')
                            ->formatStateUsing(function ($state, $record) {
                                $startNumber = $record->startPeriod?->number;
                                $endNumber = $record->endPeriod?->number;

                                $startTime = $record->startPeriod?->start_time
                                    ? \Carbon\Carbon::parse($record->startPeriod->start_time)->format('H:i')
                                    : '-';
                                $endTime = $record->endPeriod?->end_time
                                    ? \Carbon\Carbon::parse($record->endPeriod->end_time)->format('H:i')
                                    : '-';

                                if (!$startNumber || !$endNumber) {
                                    return "- ({$startTime} - {$endTime})";
                                }

                                // Buat range nomor jam: kalau start=end, tampilkan satu angka; kalau beda, tampilkan start-end
                                $numberRange = $startNumber === $endNumber
                                    ? "{$startNumber}"
                                    : "{$startNumber}-{$endNumber}";

                                return "{$numberRange} ({$startTime} - {$endTime})";
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
