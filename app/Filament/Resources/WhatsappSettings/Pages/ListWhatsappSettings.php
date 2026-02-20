<?php

namespace App\Filament\Resources\WhatsappSettings\Pages;

use App\Filament\Resources\WhatsappSettings\WhatsappSettingResource;
use App\Models\WhatsappSetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWhatsappSettings extends ListRecords
{
    protected static string $resource = WhatsappSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Pengaturan notifikasi baru')
                ->visible(fn() => WhatsappSetting::count() === 0),
        ];
    }
}
