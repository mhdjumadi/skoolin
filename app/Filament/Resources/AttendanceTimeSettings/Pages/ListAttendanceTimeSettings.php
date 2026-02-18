<?php

namespace App\Filament\Resources\AttendanceTimeSettings\Pages;

use App\Filament\Resources\AttendanceTimeSettings\AttendanceTimeSettingResource;
use App\Models\AttendanceTimeSetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceTimeSettings extends ListRecords
{
    protected static string $resource = AttendanceTimeSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Waktu presensi baru')
            ->visible(fn() => AttendanceTimeSetting::count() === 0),
        ];
    }
}
