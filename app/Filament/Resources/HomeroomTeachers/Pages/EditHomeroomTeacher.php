<?php

namespace App\Filament\Resources\HomeroomTeachers\Pages;

use App\Filament\Resources\HomeroomTeachers\HomeroomTeacherResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeroomTeacher extends EditRecord
{
    protected static string $resource = HomeroomTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
