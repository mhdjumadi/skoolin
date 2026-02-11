<?php

namespace App\Filament\Resources\Days\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('order')
                    ->required()
                    ->numeric(),
            ]);
    }
}
