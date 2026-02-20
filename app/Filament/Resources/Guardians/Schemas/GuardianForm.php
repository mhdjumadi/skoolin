<?php

namespace App\Filament\Resources\Guardians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuardianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Fieldset::make('Data Akun Wali')
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
                                    ])
                                    ->required(),

                                TextInput::make('phone')
                                    ->label('No Hp')
                                    ->tel()
                                    ->prefix('62')
                                    ->required(),

                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Fieldset::make('Pengaturan Notifikasi')
                            ->schema([
                                Toggle::make('is_notif')
                                    ->label('Terima Notifikasi')
                                    ->default(true),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);

    }
}
