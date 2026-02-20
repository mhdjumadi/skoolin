<?php

namespace App\Filament\Resources\Guardians\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class GuardianInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Wali')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Wali')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->placeholder('-')
                            ->icon('heroicon-o-identification')
                            ->columnSpanFull(),

                        TextEntry::make('user.email')
                            ->label('Email')
                            ->placeholder('-')
                            ->icon('heroicon-o-envelope'),

                        TextEntry::make('user.phone')
                            ->label('No Hp')
                            ->placeholder('-'),

                        IconEntry::make('is_notif')
                            ->label('Notifikasi Aktif')
                            ->boolean()
                            ->trueIcon('heroicon-o-bell')
                            ->falseIcon('heroicon-o-bell-slash')
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
                    ->columns(2),

                Section::make('Daftar Siswa')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        RepeatableEntry::make('students')
                            ->label('Anak')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Siswa')
                                    ->weight(FontWeight::Medium)
                                    ->icon('heroicon-o-user'),

                                TextEntry::make('pivot.relationship')
                                    ->label('Hubungan')
                                    ->badge()
                                    ->color('primary')
                                    ->placeholder('-'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
