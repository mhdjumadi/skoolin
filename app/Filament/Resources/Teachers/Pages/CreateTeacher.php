<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;
    protected static ?string $title = 'Guru Baru';

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => bcrypt($data['nip']),
            'role' => 'teacher',
        ]);

        $user->assignRole('teacher');

        return Teacher::create([
            'user_id' => $user->id,
            'nip' => $data['nip'],
            'nuptk' => $data['nuptk'] ?? null,
            'status' => $data['status'] ?? 'honorer',
        ]);
    }
}
