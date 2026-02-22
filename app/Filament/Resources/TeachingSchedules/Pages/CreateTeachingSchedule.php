<?php

namespace App\Filament\Resources\TeachingSchedules\Pages;

use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeachingSchedule extends CreateRecord
{
    protected static string $resource = TeachingScheduleResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {

    //     dd($data);

    //     return $data;
    // }
}
