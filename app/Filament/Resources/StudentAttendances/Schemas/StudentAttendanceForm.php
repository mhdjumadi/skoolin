<?php

namespace App\Filament\Resources\StudentAttendances\Schemas;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('academic_year_id')
                            ->label('Tahun Akademik')
                            ->options(AcademicYear::all()->pluck('name', 'id')->toArray())
                            ->required(),
                        Select::make('student_id')
                            ->label('Siswa')
                            ->options(Student::all()->pluck('name', 'id')->toArray())
                            ->required(),
                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(Classes::all()->pluck('name', 'id')->toArray())
                            ->required(),
                        DatePicker::make('date')
                            ->label('Tanggal')
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
                            ->label('Catatan')
                    ])
                    ->columns('2')
                    ->columnSpanFull()
            ]);
    }
}
