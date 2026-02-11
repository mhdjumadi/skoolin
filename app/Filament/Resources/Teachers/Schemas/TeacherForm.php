<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Fieldset::make('Info umum')
                    ->relationship('user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->columnSpanFull()
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->columnSpanFull()
                            ->email()
                            ->required(),

                        Select::make('gender')
                            ->options(['l' => 'Laki-laki', 'p' => 'Perempuan'])
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->tel()
                            ->prefix('62')
                            ->columnSpanFull(),
                        Textarea::make('address')
                            ->columnSpanFull(),
                    ]),

                Fieldset::make('Detail guru')
                    ->schema([
                        TextInput::make('nip')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('nuptk')
                            ->columnSpanFull(),
                        TextInput::make('status')
                            ->columnSpanFull()
                            ->default('honorer'),
                    ])
            ]);
    }
}
