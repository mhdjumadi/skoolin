<?php

namespace App\Filament\Resources\RfidMasters\Pages;

use App\Filament\Resources\RfidMasters\RfidMasterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRfidMaster extends EditRecord
{
    protected static string $resource = RfidMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();
        return $data;
    }
}
