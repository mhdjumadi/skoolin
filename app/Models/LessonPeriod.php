<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LessonPeriod extends Model
{
    use HasUuids;

    protected $table = 'lesson_periods';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'number',
        'start_time',
        'end_time'
    ];
}
