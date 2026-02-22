<?php

namespace App\Filament\Resources\TeachingSchedules\Schemas;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Day;
use App\Models\LessonPeriod;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
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

                        Select::make('start_period_id')
                            ->label('Jam Mulai Mengajar')
                            ->options(
                                LessonPeriod::all()->mapWithKeys(function ($p) {
                                    return [
                                        $p->id => $p->period_number . $p->number . ' (' . $p->start_time . '-' . $p->end_time . ')'
                                    ];
                                })->toArray()
                            )
                            ->required(),

                        Select::make('end_period_id')
                            ->label('Jam Berakhir Mengajar')
                            ->options(
                                LessonPeriod::all()->mapWithKeys(function ($p) {
                                    return [
                                        $p->id => $p->period_number . $p->number . ' (' . $p->start_time . '-' . $p->end_time . ')'
                                    ];
                                })->toArray()
                            )
                            ->required(),

                        Radio::make('day_id')
                            ->label('Hari')
                            ->options(
                                Day::all()->mapWithKeys(function ($p) {
                                    return [
                                        $p->id => $p->name
                                    ];
                                })->toArray()
                            )
                            ->columns(2)
                            ->required(),


                        // Select::make('day_id')
                        //     ->label('Hari')
                        //     ->options(Day::orderBy('number')->pluck('name', 'id')->toArray())
                        //     ->required(),
                    ])
                    ->columns('2')
                    ->columnSpanFull()
            ]);
    }
}
