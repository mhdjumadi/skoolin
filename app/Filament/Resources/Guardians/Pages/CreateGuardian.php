<?php

namespace App\Filament\Resources\Guardians\Pages;

use App\Filament\Resources\Guardians\GuardianResource;
use App\Models\Guardian;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateGuardian extends CreateRecord
{
    protected static string $resource = GuardianResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => bcrypt($data['phone']),
        ]);

        $user->assignRole('guardian');

        return Guardian::create([
            'user_id' => $user->id,
            'is_notif' => $data['is_notif'],
        ]);
    }
}
