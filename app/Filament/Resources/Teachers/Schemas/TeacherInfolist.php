<?php

namespace App\Filament\Resources\Teacher\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class TeacherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Guru')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Lengkap')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->columnSpanFull(),

                        TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->placeholder('-'),

                        TextEntry::make('user.gender')
                            ->label('Jenis Kelamin')
                            ->badge()
                            ->color(fn($state) => $state === 'L' ? 'primary' : 'pink')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'l' => 'Laki-laki',
                                'p' => 'Perempuan',
                                default => '-',
                            }),

                        TextEntry::make('user.phone')
                            ->label('No. HP')
                            ->placeholder('-'),

                        TextEntry::make('user.address')
                            ->label('Alamat')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Informasi Kepegawaian')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        TextEntry::make('nip')
                            ->label('NIP')
                            ->badge()
                            ->color('primary')
                            ->placeholder('-'),

                        TextEntry::make('nuptk')
                            ->label('NUPTK')
                            ->badge()
                            ->color('success')
                            ->placeholder('-'),

                        TextEntry::make('status')
                            ->label('Status Kepegawaian')
                            ->badge()
                            ->color(fn($state) => $state === 'pns' ? 'success' : 'warning')
                            ->formatStateUsing(fn($state) => ucfirst($state ?? '-')),

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->since()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->since()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
