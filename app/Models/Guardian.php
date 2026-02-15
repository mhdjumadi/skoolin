<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guardian extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'is_notif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'guardian_students', 'guardian_id', 'student_id')
            ->withPivot('relationship')
            ->using(GuardianStudent::class)
            ->withTimestamps();
    }
}
