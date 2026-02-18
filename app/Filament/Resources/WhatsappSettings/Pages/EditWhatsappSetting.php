<?php

namespace App\Filament\Resources\WhatsappSettings\Pages;

use App\Filament\Resources\WhatsappSettings\WhatsappSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWhatsappSetting extends EditRecord
{
    protected static string $resource = WhatsappSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
