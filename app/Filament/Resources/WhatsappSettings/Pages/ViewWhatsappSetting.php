<?php

namespace App\Filament\Resources\WhatsappSettings\Pages;

use App\Filament\Resources\WhatsappSettings\WhatsappSettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWhatsappSetting extends ViewRecord
{
    protected static string $resource = WhatsappSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
