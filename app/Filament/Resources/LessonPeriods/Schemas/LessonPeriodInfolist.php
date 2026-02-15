<?php

namespace App\Filament\Resources\LessonPeriods\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class LessonPeriodInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jam Mengajar')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextEntry::make('number')
                            ->label('Jam Ke')
                            ->badge()
                            ->color('primary')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-list-bullet'),

                        TextEntry::make('start_time')
                            ->label('Waktu Mulai')
                            ->time('H:i')
                            ->icon('heroicon-o-play'),

                        TextEntry::make('end_time')
                            ->label('Waktu Selesai')
                            ->time('H:i')
                            ->icon('heroicon-o-stop'),

                        TextEntry::make('duration')
                            ->label('Durasi')
                            ->state(function ($record) {
                                if (!$record->start_time || !$record->end_time) {
                                    return '-';
                                }

                                return \Carbon\Carbon::parse($record->start_time)
                                    ->diff(\Carbon\Carbon::parse($record->end_time))
                                    ->format('%h jam %i menit');
                            })
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->since()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->since()
                            ->placeholder('-'),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
