<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class TeachingSchedule extends Model
{
    use HasUuids;

    protected $table = 'teaching_schedules';
    protected $appends = ['full_label'];


    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'academic_year_id',
        'class_id',
        'teacher_id',
        'subject_id',
        'lesson_period_id',
        'day_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lessonPeriod()
    {
        return $this->belongsTo(LessonPeriod::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function journals()
    {
        return $this->hasMany(TeachingJournal::class);
    }

    public function getFullLabelAttribute()
    {
        return $this->day->name . ' - ' .
            $this->lessonPeriod->number . ' (' .
            $this->lessonPeriod->start_time . '-' .
            $this->lessonPeriod->end_time . ') - ' .
            $this->subject->name . ' - ' .
            $this->teacher->user->name;
    }
}
