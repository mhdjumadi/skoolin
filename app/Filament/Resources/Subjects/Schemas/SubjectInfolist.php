<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class SubjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mata Pelajaran')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Mata Pelajaran')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('code')
                            ->label('Kode Mapel')
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-qr-code')
                            ->placeholder('-'),

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
