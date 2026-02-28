<?php

namespace App\Filament\Resources\StudentAttendances\Pages;

use App\Filament\Resources\StudentAttendances\StudentAttendanceResource;
use App\Models\StudentAttendance;
use App\Models\TeachingSchedule;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateStudentAttendance extends CreateRecord
{
    protected static string $resource = StudentAttendanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['device'] = auth()->user()->name;
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function beforeCreate(): void
    {
        // dd($this->data);
        try {
            $this->validateSchedule(); // manual composite unique check
        } catch (ValidationException $e) {
            // tampilkan notif tambahan ke UI
            Notification::make()
                ->title('Jadwal Sudah Ada!')
                ->body($e->errors())
                ->danger()
                ->send();

            // throw exception supaya form tidak submit
            throw $e;
        }
    }

    protected function validateSchedule(): void
    {
        $data = $this->data; // ambil semua field form

        $attendanceConflict = StudentAttendance::where('student_id', $data['student_id'])
            ->whereDate('date', $data['date'])
            ->when($this->record, fn($q) => $q->where('id', '!=', $this->record->id))
            ->exists();

            // dd($attendanceConflict);

        if ($attendanceConflict) {
            Notification::make()
                ->title('Presensi Sudah Ada!')
                ->body('Siswa sudah memiliki presensi pada waktu tersebut.')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
