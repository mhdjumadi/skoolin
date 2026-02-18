<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;
    protected static ?string $title = 'Edit Guru';


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;

        if ($user) {
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['phone'] = $user->phone;
            $data['gender'] = $user->gender;
            $data['address'] = $user->address;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $record->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $record->update([
            'nip' => $data['nip'],
            'nuptk' => $data['nuptk'] ?? null,
            'status' => $data['status'],
        ]);

        $record->user->syncRoles(['teacher']);

        return $record;
    }
}
