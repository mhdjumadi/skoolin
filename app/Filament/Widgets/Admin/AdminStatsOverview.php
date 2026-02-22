<?php

namespace App\Filament\Widgets\Admin;

use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\Teacher;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    use HasWidgetShield;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Hari ini dan kemarin
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();


        // Jumlah siswa hadir hari ini dan kemarin
        $presentToday = StudentAttendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->count();

        $presentYesterday = StudentAttendance::whereDate('date', $yesterday)
            ->where('status','hadir')
            ->count();

            // dd($presentYesterday);

        // Hitung persentase perubahan
        $percentChange = $presentYesterday > 0
            ? round((($presentToday - $presentYesterday) / $presentYesterday) * 100)
            : 0;

        // Tentukan teks deskripsi & ikon
        if ($percentChange > 0) {
            $description = 'Meningkat ' . abs($percentChange) . '%';
            $descriptionIcon = 'heroicon-o-arrow-trending-up';
            $descriptionColor = 'success';
        } elseif ($percentChange < 0) {
            $description ='Menurun '. abs($percentChange).'%';
            $descriptionIcon = 'heroicon-o-arrow-trending-down';
            $descriptionColor = 'danger';
        } else {
            $description = 'Stabil';
            $descriptionIcon = 'heroicon-o-minus';
            $descriptionColor = 'gray';
        }

        $user = auth()->user();
        if ($user->hasRole('teacher')) {

            return [

                Stat::make('Tahun Ajaran Aktif', $activeYear?->name ?? '-')
                    ->description(
                        $activeYear
                        ? $activeYear->start_date . ' - ' . $activeYear->end_date
                        : 'Belum ada tahun ajaran aktif'
                    )
                    ->color('primary')
                    ->icon('heroicon-o-calendar'),

                Stat::make(
                    'Total Siswa Aktif',
                    Student::where('is_active', true)->count()
                )
                    ->color('info')
                    ->icon('heroicon-o-academic-cap'),

                Stat::make('Siswa Hadir Hari Ini', $presentToday)
                    ->description($description)
                    ->descriptionIcon($descriptionIcon)
                    ->descriptionColor($descriptionColor)
                    ->color('success')
                    ->icon('heroicon-o-user-group'),
            ];
        }

        return [

            Stat::make('Tahun Ajaran Aktif', $activeYear?->name ?? '-')
                ->description(
                    $activeYear
                    ? $activeYear->start_date . ' - ' . $activeYear->end_date
                    : 'Belum ada tahun ajaran aktif'
                )
                ->color('primary')
                ->icon('heroicon-o-calendar'),

            Stat::make('Total Guru', Teacher::count())
                ->color('success')
                ->icon('heroicon-o-user-group'),

            Stat::make('Total Siswa Aktif', Student::where('is_active', true)->count())
                ->color('info')
                ->icon('heroicon-o-academic-cap'),

            Stat::make('Total Kelas', Classes::count())
                ->color('warning')
                ->icon('heroicon-o-building-office'),

            Stat::make('Total Mata Pelajaran', Subject::count())
                ->color('gray')
                ->icon('heroicon-o-book-open'),

            Stat::make('Siswa Hadir Hari Ini', $presentToday)
                ->description($description)
                ->descriptionIcon($descriptionIcon)
                ->descriptionColor($descriptionColor)
                ->color('success')
                ->icon('heroicon-o-user-group'),
        ];
    }
}