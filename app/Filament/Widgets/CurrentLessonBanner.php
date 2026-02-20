<?php

namespace App\Filament\Widgets;

use App\Models\AcademicYear;
use App\Models\TeachingJournal;
use App\Models\TeachingSchedule;
use Filament\Widgets\Widget;

class CurrentLessonBanner extends Widget
{
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

        // Ambil semua jadwal yang sedang berlangsung sekarang
        $currentLessons = TeachingSchedule::query()
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber))
            ->whereHas('lessonPeriod', function ($q) use ($now) {
                $q->whereTime('start_time', '<=', $now->format('H:i:s'))
                    ->whereTime('end_time', '>=', $now->format('H:i:s'));
            })
            ->with(['lessonPeriod', 'class', 'subject', 'teacher.user'])
            ->get();

        if ($currentLessons->isEmpty()) {
            $this->currentLessonText = "Tidak ada pelajaran pada jam ini";
            $this->currentLessonStatus = 'belum';
            $this->currentTime = $now->format('H:i:s');
            return;
        }

        // Ambil semua jurnal hari ini sekali query saja (biar tidak query dalam loop)
        $journalsToday = TeachingJournal::whereDate('date', $today)
            ->whereIn('teaching_schedule_id', $currentLessons->pluck('id'))
            ->whereNull('end_time')
            ->pluck('teaching_schedule_id')
            ->toArray();

        $texts = [];

        foreach ($currentLessons as $lesson) {

            $isTeaching = in_array($lesson->id, $journalsToday);

            $textStatus = $isTeaching
                ? 'Sedang Mengajar'
                : 'Belum Mengajar';

            $texts[] =
                "Kelas {$lesson->class->name} - " .
                "{$lesson->subject->name} - " .
                "{$lesson->teacher->user->name} ({$textStatus})";
        }

        $this->currentLessonText = implode(' | ', $texts);

        // Jika ada minimal 1 yang sedang mengajar → status global = sedang
        $this->currentLessonStatus = count($journalsToday) > 0
            ? 'sedang'
            : 'belum';

        $this->currentTime = $now->format('H:i:s');
    }

    public function getListeners(): array
    {
        return [
            'tick' => 'updateTime', // event Livewire untuk update tiap detik
        ];
    }
}
