<?php

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Tahun Ajaran')
                            ->default(now()->year . '/' . now()->addYear()->year)
                            ->placeholder('Contoh: 2026/2027')
                            ->required(),

                        Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required(),

                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required(),
                        ]),

                        Toggle::make('is_active')
                            ->label('Aktifkan Tahun Ajaran')
                            ->onColor('primary')
                            ->offColor('danger')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
            ]);
    }
}
