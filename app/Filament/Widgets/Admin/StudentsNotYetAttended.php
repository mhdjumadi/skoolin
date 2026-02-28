<?php

namespace App\Filament\Widgets\Admin;

use App\Models\AcademicYear;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\TableWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Student;
use App\Models\StudentAttendance;

class StudentsNotYetAttended extends TableWidget
{
    use HasWidgetShield;
    protected static ?int $sort = 5;

    protected static ?string $heading = 'Siswa Belum Absen Hari Ini';

    public function table(Table $table): Table
    {
        $today = now()->toDateString();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return $table
            ->query(
                fn(): Builder => Student::query()
                    ->select('students.*', 'classes.name as class_name')
                    ->leftJoin('student_classes', function ($join) use ($activeYear) {
                        $join->on('students.id', '=', 'student_classes.student_id')
                            ->where('student_classes.academic_year_id', $activeYear->id);
                    })
                    ->leftJoin('classes', function ($join) {
                        $join->on('student_classes.class_id', '=', 'classes.id');
                        // ->where('classes.is_active', true);
                    })
                    ->where('students.is_active', true)
                    ->whereNotIn('students.id', function ($q) use ($today) {
                        $q->select('student_id')
                            ->from('student_attendances')
                            ->whereDate('date', $today);
                    })
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->sortable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->sortable(),
                TextColumn::make('class_name')
                    ->label('Kelas')
                    ->sortable(),
            ])
            ->filters([]);
    }
}