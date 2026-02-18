<?php

namespace App\Filament\Resources\Classes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelas')
                    ->description('Informasi data lengkap hari')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Fieldset::make('Informasi')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Kelas'),

                                TextEntry::make('description')
                                    ->placeholder('-'),

                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ])
                            ->columns(2),

                        // 🔥 Fieldset QR Code
                        Fieldset::make("QR Code")
                            ->schema([
                                ViewEntry::make('qr_code')
                                    ->hiddenLabel()
                                    ->view('filament.partials.class-qr')
                                    ->columnSpanFull(),
                            ])
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
