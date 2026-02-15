<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Siswa')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('rfid')
                            ->label('RFID')
                            ->placeholder('Scan kartu...'),

                        TextInput::make('nisn')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),

                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Select::make('gender')
                        ->options([
                            'l' => 'Laki-laki',
                            'p' => 'Perempuan',
                        ])
                        ->required(),

                    Grid::make(2)->schema([
                        TextInput::make('birth_place')
                            ->required(),

                        DatePicker::make('birth_date')
                            ->required(),
                    ]),

                    TextInput::make('phone')
                        ->tel()
                        ->placeholder('08xxxxxxxxxx'),

                    Textarea::make('address')
                        ->rows(3)
                        ->required(),

                    Toggle::make('is_active')
                        ->default(true),
                ]),

            Section::make('Wali Murid')
                ->description('Pilih wali yang sudah ada atau buat baru.')
                ->schema([
                    Select::make('guardians')
                        ->relationship('guardians', 'id')
                        ->getOptionLabelFromRecordUsing(
                            fn($record) =>
                            $record->user?->name ?? '-'
                        )
                        ->searchable()
                        ->preload()
                        ->multiple()

                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Nama Wali')
                                ->required(),

                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique('users', 'email'),

                            TextInput::make('phone')
                                ->required(),

                            TextInput::make('address'),

                            Toggle::make('is_notify')
                                ->label('Terima Notifikasi')
                                ->default(true),
                        ])
                        ->createOptionUsing(function (array $data) {

                            // 1️⃣ Buat user dulu
                            $user = \App\Models\User::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'phone' => $data['phone'],
                                'address' => $data['address'] ?? null,
                                'password' => bcrypt($data['phone']),
                            ]);

                            // 2️⃣ Buat guardian
                            $guardian = \App\Models\Guardian::create([
                                'user_id' => $user->id,
                                'is_notify' => $data['is_notify'] ?? true,
                            ]);

                            return $guardian->id; // penting!
                        })

                ])
        ]);

    }
}
