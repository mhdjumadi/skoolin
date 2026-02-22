<?php

namespace App\Filament\Widgets\Admin;

use App\Models\AcademicYear;
use App\Models\TeachingJournal;
use App\Models\TeachingSchedule;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;

class CurrentLessonBanner extends Widget
{
    use HasWidgetShield;
    protected static ?int $sort = 0;


    protected string $view = 'filament.widgets.current-lesson-banner';

    protected int|string|array $columnSpan = 'full'; // full width

    public $currentTime;
    public $currentLessonText;
    public $currentLessonStatus; // 'belum', 'sedang', 'selesai'

    public function mount()
    {
        $this->updateTime();
    }

    public function updateTime()
    {
        $this->currentTime = now()->format('H:i:s');

        $this->updateCurrentLesson();
    }

    public function updateCurrentLesson()
    {
        $now = now();
        $today = $now->toDateString();
        $todayNumber = $now->dayOfWeekIso;

        $activeYear = AcademicYear::where('is_active', true)->first();

        $user = auth()->user();

        $currentLessons = TeachingSchedule::query()
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))

            // Hari ini
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber))

            // Jam yang sedang berlangsung (antara startPeriod & endPeriod)
            ->whereHas(
                'startPeriod',
                fn($q) =>
                $q->whereTime('start_time', '<=', $now->format('H:i:s'))
            )
            ->whereHas(
                'endPeriod',
                fn($q) =>
                $q->whereTime('end_time', '>=', $now->format('H:i:s'))
            )

            // Jika login sebagai guru, batasi hanya jadwal dia
            ->when(
                $user->hasRole('teacher') && $user->teacher,
                fn($q) => $q->where('teacher_id', $user->teacher->id)
            )

            ->with(['startPeriod', 'endPeriod', 'class', 'subject', 'teacher.user'])
            ->get();

        if ($currentLessons->isEmpty()) {
            $this->currentLessonText = "Tidak ada pelajaran pada jam ini!";
            $this->currentLessonStatus = 'belum';
            $this->currentTime = $now->format('H:i:s');
            return;
        }

        // Ambil semua jurnal hari ini sekaligus
        $journalsToday = TeachingJournal::whereDate('date', $today)
            ->whereIn('teaching_schedule_id', $currentLessons->pluck('id'))
            ->whereNull('end_time')
            ->pluck('teaching_schedule_id')
            ->toArray();

        $texts = [];

        // foreach ($currentLessons as $lesson) {
        //     $isTeaching = in_array($lesson->id, $journalsToday);

        //     $textStatus = $isTeaching ? 'Sedang Mengajar' : 'Belum Mengajar';

        //     // Optional: gabungkan start & end period
        //     $timeRange = "{$lesson->startPeriod->start_time} - {$lesson->endPeriod->end_time}";

        //     $texts[] =
        //         "Kelas {$lesson->class->name} - " .
        //         "{$lesson->subject->name} - " .
        //         "{$lesson->teacher->user->name} ({$textStatus}, {$timeRange})";
        // }

        foreach ($currentLessons as $lesson) {
            $jurnal = TeachingJournal::where('teaching_schedule_id', $lesson->id)
                ->whereDate('date', $today)
                ->first();

            if (!$jurnal) {
                $textStatus = 'Belum Mengajar';
            } elseif (!$jurnal->end_time) {
                $textStatus = 'Sedang Mengajar';
            } else {
                $textStatus = 'Selesai';
            }

            $startTime = $lesson->startPeriod?->start_time ?? '-';
            $endTime = $lesson->endPeriod?->end_time ?? '-';
            $timeRange = "{$startTime} - {$endTime}";

            $texts[] =
                "Kelas {$lesson->class->name} - " .
                "{$lesson->subject->name} - " .
                "{$lesson->teacher->user->name} ({$textStatus}, {$timeRange})";
        }

        $this->currentLessonText = implode(' | ', $texts);

        // Jika ada minimal 1 yang sedang mengajar → status global = sedang
        $this->currentLessonStatus = count($journalsToday) > 0 ? 'sedang' : 'belum';

        $this->currentTime = $now->format('H:i:s');
    }

    public function getListeners(): array
    {
        return [
            'tick' => 'updateTime', // event Livewire untuk update tiap detik
        ];
    }
}
