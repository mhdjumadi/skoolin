<?php

namespace App\Filament\Resources\TeachingJournals;

use App\Filament\Resources\TeachingJournals\Pages\CreateTeachingJournal;
use App\Filament\Resources\TeachingJournals\Pages\EditTeachingJournal;
use App\Filament\Resources\TeachingJournals\Pages\ListTeachingJournals;
use App\Filament\Resources\TeachingJournals\Pages\ViewTeachingJournal;
use App\Filament\Resources\TeachingJournals\Schemas\TeachingJournalForm;
use App\Filament\Resources\TeachingJournals\Schemas\TeachingJournalInfolist;
use App\Filament\Resources\TeachingJournals\Tables\TeachingJournalsTable;
use App\Models\TeachingJournal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TeachingJournalResource extends Resource
{
    protected static ?string $model = TeachingJournal::class;
    protected static ?string $navigationLabel = 'Jurnal Mengajar';

    protected static string|UnitEnum|null $navigationGroup = 'Akademik';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    public static function form(Schema $schema): Schema
    {
        return TeachingJournalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeachingJournalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeachingJournalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
                //
            RelationManagers\AttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeachingJournals::route('/'),
            'create' => CreateTeachingJournal::route('/create'),
            'view' => ViewTeachingJournal::route('/{record}'),
            'edit' => EditTeachingJournal::route('/{record}/edit'),
        ];
    }
}
