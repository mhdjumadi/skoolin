<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudent;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Schemas\StudentInfolist;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $modelLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';

    protected static string|UnitEnum|null $navigationGroup = 'Master';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ClassesRelationManager::class,
            RelationManagers\GuardiansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();
        $model = static::getModel();

        // Base query
        $query = $model::query();

        // Guardian → hanya anaknya
        if ($user->hasRole('guardian')) {
            $studentIds = $user->guardian->students()->pluck('id');
            $query->whereHas(
                'attendances',
                fn($q) =>
                $q->whereIn('student_id', $studentIds)
            );
        }

        return $query->count();
    }


    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // Base query dengan semua count
        $query = parent::getEloquentQuery();

        // Jika guardian, tampilkan hanya anaknya
        if ($user->hasRole('guardian')) {
            $studentIds = $user->guardian->students()->pluck('id')->toArray();

            return $query->whereHas(
                'attendances',
                fn($q) =>
                $q->whereIn('student_id', $studentIds)
            );
        }

        // Default (misal admin), tampilkan semua
        return $query;
    }
}
