<?php

namespace App\Filament\Resources\HomeroomTeachers\Pages;

use App\Filament\Resources\HomeroomTeachers\HomeroomTeacherResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHomeroomTeacher extends ViewRecord
{
    protected static string $resource = HomeroomTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
