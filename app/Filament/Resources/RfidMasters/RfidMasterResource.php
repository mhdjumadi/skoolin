<?php

namespace App\Filament\Resources\RfidMasters;

use App\Filament\Resources\RfidMasters\Pages\CreateRfidMaster;
use App\Filament\Resources\RfidMasters\Pages\EditRfidMaster;
use App\Filament\Resources\RfidMasters\Pages\ListRfidMasters;
use App\Filament\Resources\RfidMasters\Pages\ViewRfidMaster;
use App\Filament\Resources\RfidMasters\Schemas\RfidMasterForm;
use App\Filament\Resources\RfidMasters\Schemas\RfidMasterInfolist;
use App\Filament\Resources\RfidMasters\Tables\RfidMastersTable;
use App\Models\RfidMaster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class RfidMasterResource extends Resource
{
    protected static ?string $model = RfidMaster::class;

    protected static ?string $navigationLabel = 'RFID Master';
    protected static ?string $modelLabel = 'RFID Master';
    protected static ?string $pluralModelLabel = 'RFID Master';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    public static function form(Schema $schema): Schema
    {
        return RfidMasterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RfidMasterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RfidMastersTable::configure($table);
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
            'index' => ListRfidMasters::route('/'),
            'create' => CreateRfidMaster::route('/create'),
            'view' => ViewRfidMaster::route('/{record}'),
            'edit' => EditRfidMaster::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
