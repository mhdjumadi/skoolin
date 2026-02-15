<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Student extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nisn',
        'rfid',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'phone',
        'address',
        'is_active',
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'student_classes', 'student_id', 'class_id')
            ->using(StudentClass::class)
            ->withPivot(['academic_year_id'])
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'guardian_students', 'student_id', 'guardian_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }
}
