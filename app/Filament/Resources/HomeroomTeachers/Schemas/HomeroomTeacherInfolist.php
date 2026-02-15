<?php

namespace App\Filament\Resources\HomeroomTeachers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class HomeroomTeacherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Wali Kelas')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('teacher.user.name')
                            ->label('Nama Guru')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->placeholder('-')
                            ->icon('heroicon-o-identification')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Detail Akademik')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        TextEntry::make('class.name')
                            ->label('Kelas')
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-home-modern'),

                        TextEntry::make('academicYear.name')
                            ->label('Tahun Ajaran')
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-calendar'),

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
