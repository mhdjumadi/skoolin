<?php

namespace App\Filament\Resources\RfidMasters\Pages;

use App\Filament\Resources\RfidMasters\RfidMasterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRfidMasters extends ListRecords
{
    protected static string $resource = RfidMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Kartu baru'),
        ];
    }
}
