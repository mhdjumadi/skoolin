<?php

namespace App\Filament\Resources\HomeroomTeachers;

use App\Filament\Resources\HomeroomTeachers\Pages\CreateHomeroomTeacher;
use App\Filament\Resources\HomeroomTeachers\Pages\EditHomeroomTeacher;
use App\Filament\Resources\HomeroomTeachers\Pages\ListHomeroomTeachers;
use App\Filament\Resources\HomeroomTeachers\Pages\ViewHomeroomTeacher;
use App\Filament\Resources\HomeroomTeachers\Schemas\HomeroomTeacherForm;
use App\Filament\Resources\HomeroomTeachers\Schemas\HomeroomTeacherInfolist;
use App\Filament\Resources\HomeroomTeachers\Tables\HomeroomTeachersTable;
use App\Models\HomeroomTeacher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HomeroomTeacherResource extends Resource
{
    protected static ?string $model = HomeroomTeacher::class;
    protected static ?string $navigationLabel = 'Wali Kelas';
    protected static ?string $modelLabel = 'Wali Kelas';
    protected static ?string $pluralModelLabel = 'Wali Kelas';

    protected static string|UnitEnum|null $navigationGroup = 'Master';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return HomeroomTeacherForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HomeroomTeacherInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomeroomTeachersTable::configure($table);
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
            'index' => ListHomeroomTeachers::route('/'),
            'create' => CreateHomeroomTeacher::route('/create'),
            'view' => ViewHomeroomTeacher::route('/{record}'),
            'edit' => EditHomeroomTeacher::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
