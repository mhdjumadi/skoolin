<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Pages;

use App\Filament\Resources\AttendanceTimeSettings\AttendanceTimeSettingResource;
use Cache;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Hapus cache lama supaya nanti di-load ulang
        Cache::forget('attendance_time_setting');

        return $record;
    }
}
