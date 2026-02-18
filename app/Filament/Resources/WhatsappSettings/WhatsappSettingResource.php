<?php

namespace App\Filament\Resources\WhatsappSettings;

use App\Filament\Resources\WhatsappSettings\Pages\CreateWhatsappSetting;
use App\Filament\Resources\WhatsappSettings\Pages\EditWhatsappSetting;
use App\Filament\Resources\WhatsappSettings\Pages\ListWhatsappSettings;
use App\Filament\Resources\WhatsappSettings\Pages\ViewWhatsappSetting;
use App\Filament\Resources\WhatsappSettings\Schemas\WhatsappSettingForm;
use App\Filament\Resources\WhatsappSettings\Schemas\WhatsappSettingInfolist;
use App\Filament\Resources\WhatsappSettings\Tables\WhatsappSettingsTable;
use App\Models\WhatsappSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WhatsappSettingResource extends Resource
{
    protected static ?string $model = WhatsappSetting::class;

    protected static ?string $navigationLabel = 'Notifikasi';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    public static function form(Schema $schema): Schema
    {
        return WhatsappSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WhatsappSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WhatsappSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWhatsappSettings::route('/'),
            'create' => CreateWhatsappSetting::route('/create'),
            'view' => ViewWhatsappSetting::route('/{record}'),
            'edit' => EditWhatsappSetting::route('/{record}/edit'),
        ];
    }
}
