<?php

namespace App\Filament\Resources\TeachingJournals\Pages;

use App\Filament\Exports\TeachingJournalExporter;
use App\Filament\Resources\TeachingJournals\TeachingJournalResource;
use App\Models\Classes;
use App\Models\JournalAttendance;
use App\Models\TeachingJournal;
use App\Models\TeachingSchedule;
use Auth;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
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
                ->label('Jurnal baru'),
            ExportAction::make()
                ->label('Export jurnal')
                ->exporter(TeachingJournalExporter::class),
        ];
    }

    public $defaultActionArguments;
    public ?string $classId = null;
    public ?TeachingJournal $jurnal = null;

    public function mount(): void
    {
        $this->classId = request()->query('token');
    }


    public function onboardingAction(): Action
    {
        $kelas = Classes::find($this->classId);
        $teacher = auth()->user()->teacher;

        // --- 1. Validasi kelas & guru ---
        if (!$kelas) {
            return Action::make('info')
                ->label('Info')
                ->modalHeading('Maaf, Jadwal Tidak Tersedia!')
                ->modalWidth('xl')
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString(
                    "<p class='text-sm text-gray-700'>Saat ini Anda tidak memiliki jadwal mengajar untuk kelas ini.</p>"
                ));
        }

        if (!$teacher) {
            return Action::make('info')
                ->label('Info')
                ->modalHeading('Akses Ditolak')
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString(
                    "<p class='text-sm text-gray-700'>Hanya guru yang dapat melakukan check-in jurnal.</p>"
                ));
        }

        // --- 2. Ambil jadwal hari ini ---
        $todayNumber = now()->dayOfWeekIso;
        $schedules = TeachingSchedule::where('class_id', $kelas->id)
            ->where('teacher_id', $teacher->id)
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber))
            ->with('startPeriod', 'endPeriod')
            ->get();

        $now = now();
        $currentSchedule = $schedules->first(function ($s) use ($now) {
            if (!$s->startPeriod || !$s->endPeriod) {
                return false;
            }

            $start = Carbon::parse($s->startPeriod->start_time);
            $end = Carbon::parse($s->endPeriod->end_time);

            return $now->between($start, $end);
        });

        if (!$currentSchedule) {
            return Action::make('info')
                ->label('Info')
                ->modalHeading('Maaf, Jadwal Tidak Tersedia!')
                ->modalWidth('xl')
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString(
                    "<p class='text-sm text-gray-700'>Saat ini Anda tidak memiliki jadwal mengajar untuk kelas ini.</p>"
                ));
        }

        // --- 3. Cek jurnal hari ini ---
        $this->jurnal = TeachingJournal::where('teaching_schedule_id', $currentSchedule->id)
            ->whereDate('date', now())
            ->first();

        if ($this->jurnal) {

            if (!$this->jurnal->end_time) {

                if (!$currentSchedule->endPeriod) {
                    return null; // safety guard kalau relasi tidak ada
                }

                $now = now();
                $scheduleEnd = Carbon::parse($currentSchedule->endPeriod->end_time);

                $diffSeconds = $now->diffInSeconds($scheduleEnd, false);
                $absSeconds = abs($diffSeconds);

                $hours = intdiv($absSeconds, 3600);
                $minutes = intdiv($absSeconds % 3600, 60);

                // Format waktu
                if ($absSeconds < 60) {
                    $timeText = 'kurang dari 1 menit';
                } elseif ($hours > 0) {
                    $timeText = $hours . ' jam' . ($minutes > 0 ? " {$minutes} menit" : '');
                } else {
                    $timeText = $minutes . ' menit';
                }

                // Tentukan status waktu
                if ($diffSeconds < 0) {
                    $message = "Jam pelajaran sudah lewat <strong>{$timeText}</strong>.";
                    $color = "text-red-600";
                } elseif ($diffSeconds > 0) {
                    $message = "Masih ada sisa waktu <strong>{$timeText}</strong>.";
                    $color = "text-green-600";
                } else {
                    $message = "Tepat di akhir jam pelajaran.";
                    $color = "text-gray-700";
                }

                return Action::make('confirmEnd')
                    ->label('Konfirmasi Selesai')
                    ->modalHeading('Konfirmasi Akhiri Sesi')
                    ->modalWidth('lg')
                    ->modalSubmitActionLabel('OK')
                    ->modalCancelActionLabel('Cancel')
                    ->modalContent(fn() => new HtmlString(
                        "<p class='text-sm {$color} mb-2'>{$message}</p>
                 <p class='text-sm text-gray-500'>Apakah ingin mengakhiri sesi sekarang?</p>"
                    ))
                    ->action(function () {
                        $this->jurnal->update([
                            'end_time' => now()->format('H:i:s'),
                        ]);
                    });
            }

            return Action::make('info')
                ->label('Info')
                ->modalHeading('Jurnal Sudah Ditutup')
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString(
                    "<p class='text-sm text-gray-700'>Jurnal untuk sesi ini sudah ditutup sebelumnya.</p>"
                ));
        }

        // --- 4. Jika belum ada, tampilkan form untuk create jurnal ---
        return Action::make('checkin')
            ->label('Mulai Sekarang')
            ->modalHeading('Jurnal Mengajar')
            ->form([
                Select::make('teaching_schedule_id')
                    ->label('Jadwal Mengajar')
                    ->options(
                        TeachingSchedule::with([
                            'day',
                            'startPeriod',
                            'endPeriod',
                            'subject',
                            'teacher.user'
                        ])
                            ->get()
                            ->pluck('full_label', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->required()
                    ->default($currentSchedule?->id),

                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required()
                    ->readOnly(fn() => Auth::user()->hasRole('teacher'))
                    ->default(now()),

                TimePicker::make('start_time')
                    ->label('Mulai')
                    ->required()
                    ->readOnly(fn() => Auth::user()->hasRole('teacher'))
                    ->default(fn () => now()->format('H:i:s')),

                TimePicker::make('end_time')
                    ->label('Selesai')
                    ->readOnly(fn() => Auth::user()->hasRole('teacher')),

                Textarea::make('material')->label('Materi'),
                Textarea::make('activities')->label('Kegiatan'),
                Textarea::make('assessment')->label('Penilaian'),
                Textarea::make('notes')->label('Catatan'),
            ])
            ->action(function (array $data) use ($kelas) {

                // 5️⃣ Buat jurnal baru
                $this->jurnal = TeachingJournal::create([
                    'teaching_schedule_id' => $data['teaching_schedule_id'],
                    'date' => $data['date'],
                    'start_time' => $data['start_time'] ?? now()->format('H:i:s'),
                    'end_time' => $data['end_time'] ?? null,
                    'material' => $data['material'] ?? null,
                    'activities' => $data['activities'] ?? null,
                    'assessment' => $data['assessment'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);

                // 6️⃣ Insert attendance siswa
                foreach ($kelas->students as $student) {
                    JournalAttendance::create([
                        'teaching_journal_id' => $this->jurnal->id,
                        'student_id' => $student->id,
                        'status' => 'hadir',
                        'notes' => null,
                    ]);
                }

                // 7️⃣ Notifikasi sukses
                Notification::make()
                    ->title('Jurnal berhasil disimpan')
                    ->success()
                    ->send();

                // 8️⃣ Redirect ke halaman view jurnal
                return redirect()->to(
                    TeachingJournalResource::getUrl('view', [
                        'record' => $this->jurnal->getKey()
                    ])
                );
            });
    }
}
