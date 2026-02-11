<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classes', 'class_id', 'student_id')
            ->using(StudentClass::class)
            ->withPivot(['academic_year_id'])
            ->withTimestamps();
    }


    // public function teachers()
    // {
    //     return $this->belongsToMany(Teacher::class, 'teacher_classes', 'class_id', 'teacher_id')
    //         ->using(TeacherClass::class)
    //         ->withPivot(['academic_year_id', 'is_active'])
    //         ->withTimestamps();
    // }
}
