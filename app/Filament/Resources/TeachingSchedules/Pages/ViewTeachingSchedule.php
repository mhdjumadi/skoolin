<?php

namespace App\Filament\Resources\TeachingSchedules\Pages;

use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTeachingSchedule extends ViewRecord
{
    protected static string $resource = TeachingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
