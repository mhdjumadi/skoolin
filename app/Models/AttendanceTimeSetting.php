<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AttendanceTimeSetting extends Model
{
    use HasUuids;
    //
    protected $fillable = [
        'in_start',
        'in_end',
        'out_start',
        'out_end',
    ];
}
