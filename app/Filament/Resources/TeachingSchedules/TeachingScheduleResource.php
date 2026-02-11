<?php

namespace App\Filament\Resources\TeachingSchedules;

use App\Filament\Resources\TeachingSchedules\Pages\CreateTeachingSchedule;
use App\Filament\Resources\TeachingSchedules\Pages\EditTeachingSchedule;
use App\Filament\Resources\TeachingSchedules\Pages\ListTeachingSchedules;
use App\Filament\Resources\TeachingSchedules\Pages\ViewTeachingSchedule;
use App\Filament\Resources\TeachingSchedules\Schemas\TeachingScheduleForm;
use App\Filament\Resources\TeachingSchedules\Schemas\TeachingScheduleInfolist;
use App\Filament\Resources\TeachingSchedules\Tables\TeachingSchedulesTable;
use App\Models\TeachingSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TeachingScheduleResource extends Resource
{
    protected static ?string $model = TeachingSchedule::class;

    protected static ?string $navigationLabel = 'Jadwal Mengajar';

    protected static string|UnitEnum|null $navigationGroup = 'Akademik';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;

    public static function form(Schema $schema): Schema
    {
        return TeachingScheduleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeachingScheduleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeachingSchedulesTable::configure($table);
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
            'index' => ListTeachingSchedules::route('/'),
            'create' => CreateTeachingSchedule::route('/create'),
            'view' => ViewTeachingSchedule::route('/{record}'),
            'edit' => EditTeachingSchedule::route('/{record}/edit'),
        ];
    }
}
