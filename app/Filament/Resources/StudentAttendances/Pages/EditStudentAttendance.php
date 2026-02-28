<?php

namespace App\Filament\Resources\StudentAttendances\Pages;

use App\Filament\Resources\StudentAttendances\StudentAttendanceResource;
use App\Models\StudentAttendance;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditStudentAttendance extends EditRecord
{
    protected static string $resource = StudentAttendanceResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
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
