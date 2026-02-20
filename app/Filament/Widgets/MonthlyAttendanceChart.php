<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
use App\Models\Student;
use Filament\Widgets\ChartWidget;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class MonthlyAttendanceChart extends ChartWidget
{
    protected ?string $heading = 'Kehadiran Siswa 7 Hari Terakhir';

    protected function getData(): array
    {
        $startDate = now()->subDays(6)->startOfDay(); // 7 hari terakhir
        $endDate = now()->endOfDay();

        // Total siswa aktif
        $totalStudents = Student::where('is_active', true)->count();

        // Ambil data attendance 7 hari terakhir
        $records = StudentAttendance::selectRaw("
            DATE(date) as attendance_date,
            status,
            COUNT(*) as total
        ")
            ->whereBetween('date', [$startDate, $endDate])
            ->groupByRaw('DATE(date), status')
            ->get()
            ->groupBy('attendance_date');

        // Generate 7 hari terakhir
        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $dates->push(now()->subDays(6 - $i)->format('Y-m-d'));
        }

        $present = [];
        $sick = [];
        $excused = [];
        $absent = [];

        foreach ($dates as $date) {

            $dayData = $records->get($date, collect());

            $presentCount = $dayData->where('status', 'hadir')->sum('total');
            $sickCount = $dayData->where('status', 'sakit')->sum('total');
            $excusedCount = $dayData->where('status', 'izin')->sum('total');

            $present[] = $presentCount;
            $sick[] = $sickCount;
            $excused[] = $excusedCount;

            // Alfa dihitung dari siswa aktif - yang tercatat hadir/sakit/izin
            $absent[] = max(
                $totalStudents - ($presentCount + $sickCount + $excusedCount),
                0
            );
        }

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $present,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => '#22c55e',
                ],
                [
                    'label' => 'Sakit',
                    'data' => $sick,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label' => 'Izin',
                    'data' => $excused,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label' => 'Alfa',
                    'data' => $absent,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $dates->map(
                fn($date) => Carbon::parse($date)->format('d M')
            ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}