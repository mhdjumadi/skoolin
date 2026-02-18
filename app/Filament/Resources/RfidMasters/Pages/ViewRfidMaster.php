<?php

namespace App\Filament\Resources\RfidMasters\Pages;

use App\Filament\Resources\RfidMasters\RfidMasterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRfidMaster extends ViewRecord
{
    protected static string $resource = RfidMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
