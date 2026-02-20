<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use PhpParser\Node\Stmt\Label;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Fieldset::make('Informasi Siswa')
                            ->schema([
                                TextInput::make('nisn')
                                    ->label('NISN')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),

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

                                Grid::make(2)->schema([
                                    TextInput::make('birth_place')
                                        ->label('Tempat Lahir')
                                        ->required(),

                                    DatePicker::make('birth_date')
                                        ->label('Tanggal Lahir')
                                        ->required(),
                                ])
                                    ->columnSpanFull(),

                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),

                                Toggle::make('is_active')
                                    ->default(true),
                            ])
                            ->columns(2),

                        Fieldset::make('Orang Tua Murid')
                            ->schema([
                                Select::make('guardians')
                                    ->label('Orang Tua')
                                    ->relationship('guardians', 'id')
                                    ->getOptionLabelFromRecordUsing(
                                        fn($record) =>
                                        $record->user?->name ?? '-'
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->columnSpanFull()

                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nama')
                                            ->required(),

                                        TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->unique('users', 'email'),

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
                                            ->label('Alamat'),

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

                                        $user->assignRole('guardian');

                                        // 2️⃣ Buat guardian
                                        $guardian = \App\Models\Guardian::create([
                                            'user_id' => $user->id,
                                            'is_notify' => $data['is_notify'] ?? true,
                                        ]);

                                        return $guardian->id; // penting!
                                    })

                            ])
                    ])
                    ->columnSpanFull()
            ]);

    }
}
