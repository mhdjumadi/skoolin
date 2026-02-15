<?php

namespace App\Filament\Resources\Classes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Section Informasi Kelas
                Section::make('Informasi Kelas')
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

                // 🔥 Section QR Code
                Section::make("QR Code")
                    ->description('QR ini digunakan untuk pembuatan jurnal mengajar.')
                    ->schema([
                        ViewEntry::make('qr_code')
                            ->hiddenLabel()
                            ->view('filament.partials.class-qr'),
                    ])
                    ->collapsible() // optional
                    ->collapsed(false),

            ]);
    }
}
