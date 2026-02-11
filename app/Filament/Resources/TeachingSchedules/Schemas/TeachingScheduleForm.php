<?php

namespace App\Filament\Resources\TeachingSchedules\Schemas;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Day;
use App\Models\LessonPeriod;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TeachingScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->label('Academic Year')
                    ->options(AcademicYear::all()->pluck('name', 'id')->toArray())
                    ->required(),

                Select::make('class_id')
                    ->label('Class')
                    ->options(Classes::all()->pluck('name', 'id')->toArray())
                    ->required(),

                Select::make('teacher_id')
                    ->label('Teacher')
                    ->options(Teacher::with('user')->get()->pluck('user.name', 'id')->toArray())
                    ->required(),

                Select::make('subject_id')
                    ->label('Subject')
                    ->options(Subject::all()->pluck('name', 'id')->toArray())
                    ->required(),

                Select::make('lesson_period_id')
                    ->label('Lesson Period')
                    ->options(
                        LessonPeriod::all()->mapWithKeys(function ($p) {
                            return [
                                $p->id => $p->period_number . ' Jam ke - ' . $p->number . ' (' . $p->start_time . '-' . $p->end_time . ')'
                            ];
                        })->toArray()
                    )
                    ->required(),


                Select::make('day_id')
                    ->label('Day')
                    ->options(Day::orderBy('number')->pluck('name', 'id')->toArray())
                    ->required(),
            ]);
    }
}
