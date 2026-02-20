<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Pages;

use App\Filament\Resources\AttendanceTimeSettings\AttendanceTimeSettingResource;
use Cache;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAttendanceTimeSetting extends CreateRecord
{
    protected static string $resource = AttendanceTimeSettingResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        Cache::forget('attendance_time_setting');

        return parent::handleRecordCreation($data);
    }
}
