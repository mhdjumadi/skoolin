<?php

namespace App\Filament\Resources\JournalAttendances;

use App\Filament\Resources\JournalAttendances\Pages\CreateJournalAttendance;
use App\Filament\Resources\JournalAttendances\Pages\EditJournalAttendance;
use App\Filament\Resources\JournalAttendances\Pages\ListJournalAttendances;
use App\Filament\Resources\JournalAttendances\Pages\ViewJournalAttendance;
use App\Filament\Resources\JournalAttendances\Schemas\JournalAttendanceForm;
use App\Filament\Resources\JournalAttendances\Schemas\JournalAttendanceInfolist;
use App\Filament\Resources\JournalAttendances\Tables\JournalAttendancesTable;
use App\Models\JournalAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JournalAttendanceResource extends Resource
{
    protected static ?string $model = JournalAttendance::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return JournalAttendanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JournalAttendanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JournalAttendancesTable::configure($table);
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
            'index' => ListJournalAttendances::route('/'),
            'create' => CreateJournalAttendance::route('/create'),
            'view' => ViewJournalAttendance::route('/{record}'),
            'edit' => EditJournalAttendance::route('/{record}/edit'),
        ];
    }
}
