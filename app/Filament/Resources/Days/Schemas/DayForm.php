<?php

namespace App\Filament\Resources\Days\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Nama hari')
                            ->required(),
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->placeholder('Urutan hari')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
