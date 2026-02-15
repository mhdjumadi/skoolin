<?php

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\TextSize;

class AcademicYearInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tahun Akademik')
                    ->description('Detail lengkap tahun akademik')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Tahun Akademik')
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->placeholder('-')
                            ->icon('heroicon-o-tag')
                            ->iconPosition(IconPosition::Before)
                            ->columnSpanFull(),

                        TextEntry::make('start_date')
                            ->label('Tanggal Mulai')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-play'),

                        TextEntry::make('end_date')
                            ->label('Tanggal Selesai')
                            ->dateTime('d M Y, H:i')
                            ->icon('heroicon-o-stop'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->since() // jadi "2 days ago"
                            ->placeholder('-')
                            ->icon('heroicon-o-plus-circle'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->since()
                            ->placeholder('-')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
