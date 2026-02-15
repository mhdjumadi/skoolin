<?php

namespace App\Filament\Resources\Guardians\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuardianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Akun Wali')
                ->relationship('user')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Wali')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(
                            table: 'users',
                            column: 'email',
                            ignoreRecord: true
                        ),

                    TextInput::make('phone')
                        ->tel()
                        ->required(),

                    Textarea::make('address')
                        ->rows(3),

                    TextInput::make('password')
                        ->password()
                        ->label('Password Baru')
                        ->columnSpanFull()
                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn($state) => filled($state))
                        ->helperText('Kosongkan jika tidak ingin mengubah password'),
                ])
                ->columns(2),

            Section::make('Pengaturan Notifikasi')
                ->schema([
                    Toggle::make('is_notif')
                        ->label('Terima Notifikasi')
                        ->default(true),
                ]),
        ]);

    }
}
