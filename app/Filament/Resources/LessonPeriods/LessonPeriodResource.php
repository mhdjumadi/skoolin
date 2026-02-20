<?php

namespace App\Filament\Resources\LessonPeriods;

use App\Filament\Resources\LessonPeriods\Pages\CreateLessonPeriod;
use App\Filament\Resources\LessonPeriods\Pages\EditLessonPeriod;
use App\Filament\Resources\LessonPeriods\Pages\ListLessonPeriods;
use App\Filament\Resources\LessonPeriods\Pages\ViewLessonPeriod;
use App\Filament\Resources\LessonPeriods\Schemas\LessonPeriodForm;
use App\Filament\Resources\LessonPeriods\Schemas\LessonPeriodInfolist;
use App\Filament\Resources\LessonPeriods\Tables\LessonPeriodsTable;
use App\Models\LessonPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LessonPeriodResource extends Resource
{
    protected static ?string $model = LessonPeriod::class;

    protected static ?string $navigationLabel = 'Jam Mengajar';
    protected static ?string $modelLabel = 'Jam Mengajar';
    protected static ?string $pluralModelLabel = 'Jam Mengajar';

    protected static string|UnitEnum|null $navigationGroup = 'Master';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    public static function form(Schema $schema): Schema
    {
        return LessonPeriodForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LessonPeriodInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LessonPeriodsTable::configure($table);
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
            'index' => ListLessonPeriods::route('/'),
            'create' => CreateLessonPeriod::route('/create'),
            'view' => ViewLessonPeriod::route('/{record}'),
            'edit' => EditLessonPeriod::route('/{record}/edit'),
        ];
    }
}
