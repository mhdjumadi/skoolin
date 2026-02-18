<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceTimeSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengaturan Waktu Presensi')
                    ->description('Informasi data lengkap pengaturan waktu presensi')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        TextEntry::make('in_start')
                            ->time(),
                        TextEntry::make('in_end')
                            ->time(),
                        TextEntry::make('out_start')
                            ->time(),
                        TextEntry::make('out_end')
                            ->time(),
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
