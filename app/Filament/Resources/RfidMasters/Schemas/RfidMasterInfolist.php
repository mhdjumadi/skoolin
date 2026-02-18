<?php

namespace App\Filament\Resources\RfidMasters\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RfidMasterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi RFID')
                    ->description('Informasi data lengkap RFID')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TextEntry::make('rfid_uid')
                            ->label('ID Kartu'),
                        TextEntry::make('student.name')
                            ->label('Siswa'),
                        TextEntry::make('device')
                            ->label('Perangkat'),
                        TextEntry::make('notes')
                            ->label('Catatan'),
                        TextEntry::make('created_by')
                            ->label('Diupdate'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
