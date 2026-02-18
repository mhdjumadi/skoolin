<?php

namespace App\Filament\Resources\StudentAttendances\Pages;

use App\Filament\Resources\StudentAttendances\StudentAttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentAttendance extends CreateRecord
{
    protected static string $resource = StudentAttendanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['device'] = auth()->user()->name;
        $data['created_by'] = auth()->id();

        return $data;
    }
}
