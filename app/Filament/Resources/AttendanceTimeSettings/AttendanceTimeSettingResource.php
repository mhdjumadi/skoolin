<?php

namespace App\Filament\Resources\AttendanceTimeSettings;

use App\Filament\Resources\AttendanceTimeSettings\Pages\CreateAttendanceTimeSetting;
use App\Filament\Resources\AttendanceTimeSettings\Pages\EditAttendanceTimeSetting;
use App\Filament\Resources\AttendanceTimeSettings\Pages\ListAttendanceTimeSettings;
use App\Filament\Resources\AttendanceTimeSettings\Pages\ViewAttendanceTimeSetting;
use App\Filament\Resources\AttendanceTimeSettings\Schemas\AttendanceTimeSettingForm;
use App\Filament\Resources\AttendanceTimeSettings\Schemas\AttendanceTimeSettingInfolist;
use App\Filament\Resources\AttendanceTimeSettings\Tables\AttendanceTimeSettingsTable;
use App\Models\AttendanceTimeSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class AttendanceTimeSettingResource extends Resource
{
    protected static ?string $model = AttendanceTimeSetting::class;

    protected static ?string $navigationLabel = 'Waktu Presensi';
    protected static ?string $modelLabel = 'Waktu Presensi';
    protected static ?string $pluralModelLabel = 'Waktu Presensi';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return AttendanceTimeSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendanceTimeSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceTimeSettingsTable::configure($table);
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
            'index' => ListAttendanceTimeSettings::route('/'),
            'create' => CreateAttendanceTimeSetting::route('/create'),
            'view' => ViewAttendanceTimeSetting::route('/{record}'),
            'edit' => EditAttendanceTimeSetting::route('/{record}/edit'),
        ];
    }
}
