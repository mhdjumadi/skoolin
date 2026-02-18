<?php

namespace App\Filament\Resources\StudentAttendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentAttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Presensi Siswa')
                    ->description('Informasi lengkap presensi siswa')
                    ->icon('heroicon-o-finger-print')
                    ->schema([
                        TextEntry::make('student.name')
                            ->label('Nama'),
                        TextEntry::make('academicYear.name')
                            ->label('Tahun Akademik'),
                        TextEntry::make('date')
                            ->label('Tanggal')
                            ->date(),
                        TextEntry::make('check_in')
                            ->time()
                            ->placeholder('-'),
                        TextEntry::make('check_out')
                            ->time()
                            ->placeholder('-'),
                        TextEntry::make('status'),
                        TextEntry::make('note')
                            ->label('Catatan')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('device')
                            ->label('Nama Perangkat')
                            ->placeholder('-'),
                        TextEntry::make('created_by')
                            ->placeholder('-'),
                        TextEntry::make('updated_by')
                            ->placeholder('-'),
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
