<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Siswa')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('rfid')
                            ->label('RFID')
                            ->placeholder('-')
                            ->badge()
                            ->color('warning'),

                        TextEntry::make('nisn')
                            ->label('NISN')
                            ->placeholder('-')
                            ->badge()
                            ->color('success'),

                        TextEntry::make('gender')
                            ->label('Jenis Kelamin')
                            ->badge()
                            ->color(fn($state) => $state === 'L' ? 'primary' : 'pink')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'l' => 'Laki-laki',
                                'p' => 'Perempuan',
                                default => '-',
                            }),

                        TextEntry::make('birth_place')
                            ->label('Tempat Lahir')
                            ->placeholder('-'),

                        TextEntry::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('phone')
                            ->label('No. HP')
                            ->placeholder('-'),

                        TextEntry::make('address')
                            ->label('Alamat')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Status & Sistem')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),

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
                    ->collapsible(),
            ]);
    }
}
