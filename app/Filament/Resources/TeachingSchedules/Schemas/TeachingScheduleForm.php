<?php

namespace App\Filament\Resources\TeachingSchedules\Schemas;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Day;
use App\Models\LessonPeriod;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeachingScheduleForm
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

                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(Classes::all()->pluck('name', 'id')->toArray())
                            ->required(),

                        Select::make('teacher_id')
                            ->label('Guru')
                            ->options(Teacher::with('user')->get()->pluck('user.name', 'id')->toArray())
                            ->required(),

                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(Subject::all()->pluck('name', 'id')->toArray())
                            ->required(),

                        Select::make('lesson_period_id')
                            ->label('Jam Mengajar')
                            ->options(
                                LessonPeriod::all()->mapWithKeys(function ($p) {
                                    return [
                                        $p->id => $p->period_number . $p->number . ' (' . $p->start_time . '-' . $p->end_time . ')'
                                    ];
                                })->toArray()
                            )
                            ->required(),


                        Select::make('day_id')
                            ->label('Hari')
                            ->options(Day::orderBy('number')->pluck('name', 'id')->toArray())
                            ->required(),
                    ])
                    ->columns('2')
                    ->columnSpanFull()
            ]);
    }
}
