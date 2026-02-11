<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class JournalAttendance extends Model
{
    use HasUuids;

    protected $table = 'journal_attendances';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teaching_journal_id',
        'student_id',
        'status',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function teachingJournal()
    {
        return $this->belongsTo(TeachingJournal::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
