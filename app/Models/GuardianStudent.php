<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GuardianStudent extends Pivot
{
    use HasFactory;

    protected $table = 'guardian_students';
    protected $fillable = [
        'guardian_id',
        'student_id',
        'relationship',
    ];
}