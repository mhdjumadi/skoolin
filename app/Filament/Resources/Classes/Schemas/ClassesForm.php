<?php

namespace App\Filament\Resources\Classes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClassesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description'),
            ]);
    }
}
