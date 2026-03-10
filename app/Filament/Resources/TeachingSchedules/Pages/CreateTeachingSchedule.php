<?php

namespace App\Filament\Resources\TeachingSchedules\Pages;

use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use App\Models\TeachingSchedule;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateTeachingSchedule extends CreateRecord
{
    protected static string $resource = TeachingScheduleResource::class;

    protected function beforeCreate(): void
    {
        try {
            $this->validateSchedule();
        } catch (ValidationException $e) {
            Notification::make()
                ->title('Jadwal Sudah Ada!')
                ->body($e->errors())
                ->danger()
                ->send();
            throw $e;
        }
    }

    protected function validateSchedule(): void
    {
        $data = $this->data;

        // 1️⃣ Cek duplicate untuk kelas (class schedule)
        $classConflict = TeachingSchedule::where([
            'academic_year_id' => $data['academic_year_id'],
            'class_id' => $data['class_id'],
            'day_id' => $data['day_id'],
            'start_period_id' => $data['start_period_id'],
            'end_period_id' => $data['end_period_id'],
        ])
            ->when($this->record, fn($q) => $q->where('id', '!=', $this->record->id))
            ->exists();

        if ($classConflict) {
            Notification::make()
                ->title('Jadwal Sudah Ada!')
                ->body('Kelas sudah memiliki jadwal pada waktu tersebut.')
                ->danger()
                ->send();

            $this->halt(); // hentikan submit form
        }

        // 2️⃣ Cek double booking guru (teacher schedule)
        $teacherConflict = TeachingSchedule::where([
            'academic_year_id' => $data['academic_year_id'],
            'teacher_id' => $data['teacher_id'],
            'day_id' => $data['day_id'],
            'start_period_id' => $data['start_period_id'],
            'end_period_id' => $data['end_period_id'],
        ])
            ->when($this->record, fn($q) => $q->where('id', '!=', $this->record->id))
            ->exists();

        if ($teacherConflict) {
            Notification::make()
                ->title('Jadwal Sudah Ada!')
                ->body('Guru sudah memiliki jadwal pada waktu tersebut.')
                ->danger()
                ->send();

            $this->halt(); // hentikan submit form
        }
    }
}
