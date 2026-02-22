<?php

namespace App\Filament\Widgets\Admin;

use App\Models\AcademicYear;
use App\Models\TeachingJournal;
use App\Models\TeachingSchedule;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class TeacherAttendanceWeeklyChart extends ChartWidget
{
    use HasWidgetShield;
    protected static ?int $sort = 3;

    protected ?string $heading = 'Kehadiran Guru Mengajar';

    protected function getData(): array
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->toDateString());
        }

        $percentage = [];
        $user = auth()->user();
        foreach ($dates as $date) {
            $todayNumber = Carbon::parse($date)->dayOfWeekIso; // 1=Mon ... 7=Sun

            $schedulesQuery = TeachingSchedule::query()

                // Filter tahun ajaran aktif
                ->when(
                    $activeYear,
                    fn($q) => $q->where('academic_year_id', $activeYear->id)
                )

                // Filter hari
                ->whereHas('day', fn($q) => $q->where('order', $todayNumber));

            // ==============================
            // Jika login sebagai Guru
            // ==============================
            if ($user->hasRole('teacher') && $user->teacher) {
                $schedulesQuery->where('teacher_id', $user->teacher->id);
            }

            $schedules = $schedulesQuery->get();

            $totalSchedules = $schedules->count();

            if ($totalSchedules === 0) {
                $percentage[] = 0;
                continue;
            }

            $filledJournals = TeachingJournal::whereDate('date', $date)
                ->whereIn('teaching_schedule_id', $schedules->pluck('id'))
                ->count();

            $percentage[] = round(($filledJournals / $totalSchedules) * 100);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Persentase Kehadiran Guru',
                    'data' => $percentage,
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $dates->map(fn($d) => Carbon::parse($d)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
