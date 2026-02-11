<?php

namespace App\Filament\Resources\Days\Pages;

use App\Filament\Resources\Days\DayResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDay extends ViewRecord
{
    protected static string $resource = DayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
