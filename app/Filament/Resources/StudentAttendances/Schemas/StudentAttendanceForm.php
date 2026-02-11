<?php

namespace App\Filament\Resources\StudentAttendances\Schemas;

use App\Models\AcademicYear;
use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class StudentAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Siswa')
                    ->options(Student::all()->pluck('name', 'id')->toArray())
                    ->required(),
                Select::make('academic_year_id')
                    ->label('Tahun Ajaran')
                    ->options(AcademicYear::all()->pluck('name', 'id')->toArray())
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('check_in'),
                TimePicker::make('check_out'),
                Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'terlambat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'dispensasi' => 'Dispensasi',
                    ])
                    ->default('hadir')
                    ->required(),
                Textarea::make('note')
                    ->columnSpanFull(),
                TextInput::make('device'),
            ]);
    }
}
