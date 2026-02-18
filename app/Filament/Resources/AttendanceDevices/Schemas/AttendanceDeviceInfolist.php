<?php

namespace App\Filament\Resources\AttendanceDevices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceDeviceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Perangkat Presensi')
                    ->description('Informasi data lengkap perangkat presensi')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TextEntry::make('serial_number')
                            ->label('No Seri'),
                        TextEntry::make('build')
                            ->label('Didaftarkan')
                            ->date(),
                        TextEntry::make('name')
                            ->label('Nama'),
                        TextEntry::make('location')
                            ->label('Lokasi'),
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
