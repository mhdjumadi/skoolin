<?php

namespace App\Filament\Resources\HomeroomTeachers\Schemas;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Teacher;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomeroomTeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('teacher_id')
                            ->label('Guru')
                            ->options(
                                Teacher::with('user')
                                    ->get()
                                    ->pluck('user.name', 'id')
                            )
                            ->columnSpanFull()
                            ->required(),
                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(Classes::all()->pluck('name', 'id')->toArray())
                            ->required(),
                        Select::make('academic_year_id')
                            ->label('Academic Year')
                            ->options(AcademicYear::all()->pluck('name', 'id')->toArray())
                            ->required(),
                    ])
                    ->columns('2')
                    ->columnSpanFull()
            ]);
    }
}
