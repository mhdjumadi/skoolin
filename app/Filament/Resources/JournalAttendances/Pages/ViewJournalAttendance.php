<?php

namespace App\Filament\Resources\JournalAttendances\Pages;

use App\Filament\Resources\JournalAttendances\JournalAttendanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJournalAttendance extends ViewRecord
{
    protected static string $resource = JournalAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
