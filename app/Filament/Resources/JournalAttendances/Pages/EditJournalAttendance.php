<?php

namespace App\Filament\Resources\JournalAttendances\Pages;

use App\Filament\Resources\JournalAttendances\JournalAttendanceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJournalAttendance extends EditRecord
{
    protected static string $resource = JournalAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
