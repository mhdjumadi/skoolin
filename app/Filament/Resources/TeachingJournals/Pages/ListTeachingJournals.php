<?php

namespace App\Filament\Resources\TeachingJournals\Pages;

use App\Filament\Resources\TeachingJournals\TeachingJournalResource;
use App\Models\Classes;
use App\Models\TeachingJournal;
use App\Models\TeachingSchedule;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Illuminate\Support\HtmlString;

class ListTeachingJournals extends ListRecords
{
    protected static string $resource = TeachingJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Jurnal baru')
        ];
    }

    public $defaultActionArguments;
    public ?string $classId = null;
    public ?TeachingJournal $jurnal = null;

    public function mount(): void
    {
        $this->classId = request()->query('token');

        // Jangan pakai firstOrFail
        $kelas = Classes::first();

        if ($kelas) {
            $this->classId = $kelas->id;
        }
    }


    public function onboardingAction(): Action
    {
        $kelas = Classes::findOrFail($this->classId);
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return Action::make('info')
                ->label('Info')
                ->modalHeading('Akses ditolak')
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString(
                    "<p class='text-sm text-gray-700'>Hanya guru yang dapat melakukan check-in jurnal.</p>"
                ));
        }

        // 1. Ambil schedule hari ini
        $todayDay = now()->format('l');
        $schedules = TeachingSchedule::where('class_id', $kelas->id)
            ->where('teacher_id', $teacher->id)
            ->where('day_name', $todayDay)
            ->get();

        $now = now()->format('H:i:s');

        $currentSchedule = $schedules->first(function ($s) use ($now) {
            return $now >= $s->lessonPeriod->start_time && $now <= $s->lessonPeriod->end_time;
        });

        if (!$currentSchedule) {
            return Action::make('info')
                ->label('Info')
                ->modalHeading('Maaf, Jadwal Tidak Tersedia!')
                ->modalWidth(Width::ExtraLarge)
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString("<p class='text-sm text-gray-700'>Saat ini anda tidak ada jadwal mengajar untuk kelas ini.</p>"));
        }

        // Ambil atau create jurnal hari ini untuk schedule
        $this->jurnal = TeachingJournal::where('teaching_schedule_id', $currentSchedule->id)
            ->whereDate('date', now())
            ->first();

        return Action::make('checkin')
            ->label($this->jurnal && !$this->jurnal->jam_selesai ? 'Selesaikan Sekarang' : 'Mulai Sekarang')
            ->modalHeading('Jurnal Mengajar')
            ->modalContent(fn() => view('filament.pages.journals', [
                'record' => $this->jurnal,
                'kelas' => $kelas,
                'teacher' => $teacher,
                'currentTime' => now(),
            ]))

            ->action(function () use ($currentSchedule) {
                if ($this->jurnal) {
                    if (!$this->jurnal->jam_selesai) {
                        $this->jurnal->jam_selesai = now();
                        $this->jurnal->save();
                        Notification::make()
                            ->title('Jurnal berhasil diupdate')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Jurnal sudah disimpan')
                            ->success()
                            ->send();
                    }
                } else {
                    $this->jurnal = TeachingJournal::create([
                        'teaching_schedule_id' => $currentSchedule->id,
                        'date' => now(),
                        'jam_mulai' => now(),
                    ]);

                    Notification::make()
                        ->title('Jurnal berhasil disimpan')
                        ->success()
                        ->send();
                }

                return redirect()->route(
                    'filament.admin.resources.teaching-journals.view',
                    ['record' => $this->jurnal->id]
                );
            });
    }
}
