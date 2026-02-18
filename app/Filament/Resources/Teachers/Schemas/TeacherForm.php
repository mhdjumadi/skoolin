<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Symfony\Contracts\Service\Attribute\Required;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Fieldset::make('Informasi Umum')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required(),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),

                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'l' => 'Laki-laki',
                                        'p' => 'Perempuan',
                                    ]),

                                TextInput::make('phone')
                                    ->label('No Hp')
                                    ->tel()
                                    ->prefix('62')
                                    ->required(),

                                Textarea::make('address')
                                    ->label('Alamat'),
                            ])
                            ->columns(2),

                        Fieldset::make('Detail Guru')
                            ->schema([
                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->required(),

                                TextInput::make('nuptk')
                                    ->label('NUPTK'),

                                TextInput::make('status')
                                    ->default('honorer'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
