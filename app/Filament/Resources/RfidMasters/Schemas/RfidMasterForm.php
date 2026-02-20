<?php

namespace App\Filament\Resources\RfidMasters\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RfidMasterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('rfid_uid')
                            ->label('ID Kartu')
                            ->required(),
                        // ->readOnly(),
                        TextInput::make('device')
                            ->label('Perangkat')
                            ->required(),
                        // Select::make('student_id')
                        //     ->label('Siswa')
                        //     ->options(Student::all()->pluck('name', 'id')->toArray())
                        //     ->required(),
                        Select::make('student_id')
                            ->label('Siswa')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
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
                                    ->required(),

                                Toggle::make('is_active')
                                    ->default(true),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $data['password'] = bcrypt($data['nisn']);

                                return Student::create($data)->getKey();
                            }),
                        Textarea::make('notes')
                            ->label('Catatan'),
                        Toggle::make(name: 'is_active')
                            ->label('Kartu Aktif')
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
