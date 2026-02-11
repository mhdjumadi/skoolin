<?php

namespace App\Filament\Resources\Days;

use App\Filament\Resources\Days\Pages\CreateDay;
use App\Filament\Resources\Days\Pages\EditDay;
use App\Filament\Resources\Days\Pages\ListDays;
use App\Filament\Resources\Days\Pages\ViewDay;
use App\Filament\Resources\Days\Schemas\DayForm;
use App\Filament\Resources\Days\Schemas\DayInfolist;
use App\Filament\Resources\Days\Tables\DaysTable;
use App\Models\Day;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;


class DayResource extends Resource
{
    protected static ?string $model = Day::class;
    protected static ?string $navigationLabel = 'Hari';

    protected static string|UnitEnum|null $navigationGroup = 'Master';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    public static function form(Schema $schema): Schema
    {
        return DayForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DayInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DaysTable::configure($table);
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
            'index' => ListDays::route('/'),
            'create' => CreateDay::route('/create'),
            'view' => ViewDay::route('/{record}'),
            'edit' => EditDay::route('/{record}/edit'),
        ];
    }
}
