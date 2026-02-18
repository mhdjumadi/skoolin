<?php

namespace App\Filament\Resources\LessonPeriods\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LessonPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('number')
                            ->label('Jam ke')
                            ->numeric()
                            ->columnSpanFull()
                            ->required(),
                        TimePicker::make('start_time')
                            ->label('Jam mulai')
                            ->required(),
                        TimePicker::make('end_time')
                            ->label('Jam selesai')
                            ->required(),
                    ])
                    ->columns('2')
                    ->columnSpanFull()
            ]);
    }
}
