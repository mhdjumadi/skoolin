<?php

namespace App\Filament\Resources\TeachingSchedules\Schemas;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Day;
use App\Models\LessonPeriod;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeachingSchedule;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class TeachingScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        // Select::make('academic_year_id')
                        //     ->label('Tahun Akademik')
                        //     ->options(AcademicYear::all()->pluck('name', 'id')->toArray())
                        //     ->required()
                        //     ->rules([
                        //         fn($get, $record) => Rule::unique(TeachingSchedule::class)
                        //             ->where('academic_year_id', $get('academic_year_id'))
                        //             ->where('class_id', $get('class_id'))
                        //             ->where('day_id', $get('day_id'))
                        //             ->where('start_period_id', $get('start_period_id'))
                        //             ->where('end_period_id', $get('end_period_id'))
                        //             ->ignore($record),
                        //     ])
                        //     ->validationMessages([
                        //         'unique' => 'Jadwal kelas dengan rentang waktu tersebut sudah ada.',
                        //     ]),
                        Select::make('academic_year_id')
                            ->label('Tahun Akademik')
                            ->options(AcademicYear::all()->pluck('name', 'id')->toArray())
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'academic_year_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Jadwal sudah ada pada waktu tersebut.',
                            ]),

                        Select::make('class_id')
                            ->label('Kelas')
                            ->options(Classes::all()->pluck('name', 'id')->toArray())
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'class_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Kelas sudah ada pada waktu tersebut.',
                            ]),

                        // Select::make('teacher_id')
                        //     ->label('Guru')
                        //     ->options(Teacher::with('user')->get()->pluck('user.name', 'id')->toArray())
                        //     ->required(),

                        Select::make('teacher_id')
                            ->label('Guru')
                            ->options(Teacher::with('user')->get()->pluck('user.name', 'id')->toArray())
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'teacher_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Guru sudah ada pada waktu tersebut.',
                            ]),

                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(Subject::all()->pluck('name', 'id')->toArray())
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'subject_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Mata Pelajaran sudah ada pada waktu tersebut.',
                            ]),

                        Select::make('start_period_id')
                            ->label('Jam Mulai Mengajar')
                            ->options(
                                LessonPeriod::all()->mapWithKeys(function ($p) {
                                    return [
                                        $p->id => $p->period_number . $p->number . ' (' . $p->start_time . '-' . $p->end_time . ')'
                                    ];
                                })->toArray()
                            )
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'start_period_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Mata Pelajaran sudah ada pada waktu tersebut.',
                            ]),

                        Select::make('end_period_id')
                            ->label('Jam Berakhir Mengajar')
                            ->options(
                                LessonPeriod::all()->mapWithKeys(function ($p) {
                                    return [
                                        $p->id => $p->period_number . $p->number . ' (' . $p->start_time . '-' . $p->end_time . ')'
                                    ];
                                })->toArray()
                            )
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'end_period_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Mata Pelajaran sudah ada pada waktu tersebut.',
                            ]),

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
                            ->required()
                            ->unique(
                                table: TeachingSchedule::class,
                                column: 'day_id',
                                ignoreRecord: true,
                                modifyRuleUsing: function (Unique $rule, callable $get) {
                                    return $rule->where(function ($query) use ($get) {
                                        $query
                                            ->where('academic_year_id', $get('academic_year_id'))
                                            ->where('class_id', $get('class_id'))
                                            ->where('subject_id', $get('subject_id'))
                                            ->where('teacher_id', $get('teacher_id'))
                                            ->where('day_id', $get('day_id'))
                                            ->where('start_period_id', $get('start_period_id'))
                                            ->where('end_period_id', $get('end_period_id'));
                                    });
                                }
                            )
                            ->validationMessages([
                                'unique' => 'Mata Pelajaran sudah ada pada waktu tersebut.',
                            ]),


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
