<?php

namespace App\Filament\Widgets\Guardian;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GuardianStatsOverview extends BaseWidget
{
    use HasWidgetShield;

    protected ?string $heading = 'Statistik Anak';
    protected static ?int $sort = 0;


    protected function getStats(): array
    {
        $user = auth()->user();
        $childrenIds = $user->guardian?->students()->pluck('id') ?? collect();

        $activeYear = AcademicYear::where('is_active', true)->first();

        $presentToday = StudentAttendance::whereIn('student_id', $childrenIds)
            ->whereDate('date', now())
            ->where('status', 'hadir')
            ->count();

        $totalActiveStudents = Student::whereIn('id', $childrenIds)
            ->where('is_active', true)
            ->count();

        return [
            Stat::make('Tahun Ajaran Aktif', $activeYear?->name ?? '-')
                ->description($activeYear ? $activeYear->start_date . ' - ' . $activeYear->end_date : 'Belum ada tahun ajaran aktif')
                ->color('primary')
                ->icon('heroicon-o-calendar'),

            Stat::make('Jumlah Anak', $childrenIds->count())
                ->color('success')
                ->icon('heroicon-o-user-group'),

            Stat::make('Siswa Aktif', $totalActiveStudents)
                ->color('info')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Hadir Hari Ini', $presentToday)
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
