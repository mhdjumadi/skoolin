<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Schemas;

use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceTimeSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TimePicker::make('in_start')
                            ->required(),
                        TimePicker::make('in_end')
                            ->required(),
                        TimePicker::make('out_start')
                            ->required(),
                        TimePicker::make('out_end')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
