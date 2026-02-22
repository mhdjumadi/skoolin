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
        'day_id',
        'start_period_id',
        'end_period_id',
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

    public function startPeriod()
    {
        return $this->belongsTo(LessonPeriod::class, 'start_period_id');
    }

    public function endPeriod()
    {
        return $this->belongsTo(LessonPeriod::class, 'end_period_id');
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
        $dayName = $this->day?->name ?? 'Unknown Day';
        $startNumber = $this->startPeriod?->number;
        $endNumber = $this->endPeriod?->number;
        $startTime = $this->startPeriod?->start_time ?? '-';
        $endTime = $this->endPeriod?->end_time ?? '-';
        $subjectName = $this->subject?->name ?? 'Unknown Subject';
        $teacherName = $this->teacher?->user?->name ?? 'Unknown Teacher';

        // Buat range nomor jam
        if ($startNumber && $endNumber) {
            $numberRange = $startNumber === $endNumber
                ? "{$startNumber}"
                : "{$startNumber}-{$endNumber}";
        } else {
            $numberRange = '-';
        }

        return "{$dayName} - {$numberRange} ({$startTime} - {$endTime}) - {$subjectName} - {$teacherName}";
    }
}
