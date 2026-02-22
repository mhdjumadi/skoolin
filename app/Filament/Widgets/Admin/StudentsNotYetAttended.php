<?php

namespace App\Filament\Widgets\Admin;

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

        // Ambil semua absensi siswa hari ini
        $attendedIds = StudentAttendance::whereDate('date', $today)
            ->pluck('student_id')
            ->toArray();

        return $table
            ->query(
                fn(): Builder => Student::query()
                    ->where('is_active', true)
                    ->whereNotIn('id', $attendedIds)
                    ->with('classes')
            )
            ->columns([
                TextColumn::make('nisn')
                    ->label('NISN'),
                TextColumn::make('name')
                    ->label('Nama Siswa'),
                TextColumn::make('classes.name')
                    ->label('Kelas')
                    ->sortable(),
            ])
            ->filters([
            ]);
    }
}