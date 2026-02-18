<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Pages;

use App\Filament\Resources\AttendanceTimeSettings\AttendanceTimeSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceTimeSetting extends EditRecord
{
    protected static string $resource = AttendanceTimeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
