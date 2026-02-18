<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Pages;

use App\Filament\Resources\AttendanceTimeSettings\AttendanceTimeSettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendanceTimeSetting extends ViewRecord
{
    protected static string $resource = AttendanceTimeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
