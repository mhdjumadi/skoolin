<?php

namespace App\Filament\Resources\StudentAttendances;

use App\Filament\Resources\StudentAttendances\Pages\CreateStudentAttendance;
use App\Filament\Resources\StudentAttendances\Pages\EditStudentAttendance;
use App\Filament\Resources\StudentAttendances\Pages\ListStudentAttendances;
use App\Filament\Resources\StudentAttendances\Pages\ViewStudentAttendance;
use App\Filament\Resources\StudentAttendances\Schemas\StudentAttendanceForm;
use App\Filament\Resources\StudentAttendances\Schemas\StudentAttendanceInfolist;
use App\Filament\Resources\StudentAttendances\Tables\StudentAttendancesTable;
use App\Models\StudentAttendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StudentAttendanceResource extends Resource
{
    protected static ?string $model = StudentAttendance::class;

    protected static ?string $navigationLabel = 'Presensi Siswa';

    protected static string|UnitEnum|null $navigationGroup = 'Akademik';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFingerPrint;

    public static function form(Schema $schema): Schema
    {
        return StudentAttendanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentAttendanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentAttendancesTable::configure($table);
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
            'index' => ListStudentAttendances::route('/'),
            'create' => CreateStudentAttendance::route('/create'),
            'view' => ViewStudentAttendance::route('/{record}'),
            'edit' => EditStudentAttendance::route('/{record}/edit'),
        ];
    }
}
