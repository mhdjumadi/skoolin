<?php

namespace App\Filament\Resources\Days\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;

class DayInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Hari')
                    ->description('Detail data hari')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Hari')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->placeholder('-')
                            ->icon('heroicon-o-calendar-days')
                            ->iconPosition(IconPosition::Before)
                            ->columnSpanFull(),

                        TextEntry::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-bars-3'),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->since()
                            ->placeholder('-')
                            ->icon('heroicon-o-plus-circle'),

                        TextEntry::make('updated_at')
                            ->label('Diupdate')
                            ->since()
                            ->placeholder('-')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(3)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
