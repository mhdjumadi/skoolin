<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rfid'),
                TextInput::make('nisn')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('gender'),
                TextInput::make('birth_place')
                    ->required(),
                DatePicker::make('birth_date'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('address')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
