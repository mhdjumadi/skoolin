<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class TeachingJournal extends Model
{
    use HasUuids;

    protected $table = 'teaching_journals';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teaching_schedule_id',
        'date',
        'material',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function teachingSchedule()
    {
        return $this->belongsTo(TeachingSchedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(JournalAttendance::class, 'teaching_journal_id');
    }
}
