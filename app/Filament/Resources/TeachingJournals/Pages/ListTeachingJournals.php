<?php

namespace App\Filament\Resources\TeachingJournals\Pages;

use App\Filament\Exports\TeachingJournalExporter;
use App\Filament\Resources\TeachingJournals\TeachingJournalResource;
use App\Models\Classes;
use App\Models\JournalAttendance;
use App\Models\TeachingJournal;
use App\Models\TeachingSchedule;
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
            ->with('lessonPeriod')
            ->get();

        $now = now()->format('H:i:s');
        $currentSchedule = $schedules->first(fn($s) => $now >= $s->lessonPeriod->start_time && $now <= $s->lessonPeriod->end_time);

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
                // Update end_time jika belum ada
                $this->jurnal->update([
                    'end_time' => now()->format('H:i:s'),
                ]);

                return Action::make('info')
                    ->label('Info')
                    ->modalHeading('Terima Kasih')
                    ->modalSubmitAction(false)
                    ->modalContent(fn() => new HtmlString(
                        "<p class='text-sm text-gray-700'>Sesi mengajar telah selesai.</p>"
                    ));
            }

            return Action::make('info')
                ->label('Info')
                ->modalHeading('Jurnal Sudah Dibuat')
                ->modalSubmitAction(false)
                ->modalContent(fn() => new HtmlString(
                    "<p class='text-sm text-gray-700'>Jurnal untuk sesi ini sudah dibuat sebelumnya.</p>"
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
                        TeachingSchedule::with(['day', 'lessonPeriod', 'subject', 'teacher'])
                            ->get()
                            ->pluck('full_label', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->required()
                    ->default($currentSchedule->id),

                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),

                TimePicker::make('start_time')
                    ->label('Mulai')
                    ->required()
                    ->default(now()),

                TimePicker::make('end_time')
                    ->label('Selesai'),

                Textarea::make('material')
                    ->label('Materi'),
                Textarea::make('activities')
                    ->label('Kegiatan'),
                Textarea::make('assessment')
                    ->label('Penilaian'),
                Textarea::make('notes')
                    ->label('Catatan'),
            ])
            ->action(function (array $data) use ($kelas) {

                // --- 5. Buat jurnal baru ---
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


                // --- 6. Insert attendance siswa ---
                $students = $kelas->students;
                foreach ($students as $student) {
                    JournalAttendance::create([
                        'teaching_journal_id' => $this->jurnal->id,
                        'student_id' => $student->id,
                        'status' => 'hadir', // default hadir 
                        'notes' => null,
                    ]);
                }

                // --- 7. Notifikasi sukses ---
                Notification::make()
                    ->title('Jurnal berhasil disimpan')
                    ->success()
                    ->send();

                // --- 8. Redirect ke halaman view jurnal ---
                return redirect()->to(
                    TeachingJournalResource::getUrl('view', ['record' => $this->jurnal->getKey()])
                );
            });
    }
}
