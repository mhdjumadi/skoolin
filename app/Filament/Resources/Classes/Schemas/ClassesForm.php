<?php

namespace App\Filament\Resources\Classes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->placeholder('Nama kelas')
                            ->required(),
                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Deskripsi kelas'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
