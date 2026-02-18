<?php

namespace App\Filament\Resources\WhatsappSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WhatsappSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengaturan Notifikasi')
                    ->description('Informasi data lengkap pengaturan notifikasi')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TextEntry::make('token')
                            ->columnSpanFull(),
                        TextEntry::make('api_url')
                            ->label('URL')
                            ->columnSpanFull(),
                        TextEntry::make('message')
                            ->label('Pesan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
