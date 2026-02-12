<?php

namespace App\Filament\Resources\TeachingJournals\Pages;

use App\Filament\Resources\TeachingJournals\TeachingJournalResource;
use App\Models\JournalAttendance;
use App\Models\StudentClass;
use Filament\Resources\Pages\CreateRecord;

class CreateTeachingJournal extends CreateRecord
{
    protected static string $resource = TeachingJournalResource::class;

    protected function afterCreate(): void
    {
        $journal = $this->record;

        // Ambil schedule
        $schedule = $journal->teachingSchedule;

        // Ambil semua siswa di kelas tersebut sesuai tahun ajaran
        $students = StudentClass::where('class_id', $schedule->class_id)
            ->where('academic_year_id', $schedule->academic_year_id)
            ->get();

        foreach ($students as $student) {
            JournalAttendance::create([
                'teaching_journal_id' => $journal->id,
                'student_id' => $student->student_id,
                'status' => 'hadir', // default
                'notes' => null,
            ]);
        }
    }
}
