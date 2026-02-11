<?php

namespace App\Filament\Resources\JournalAttendances\Pages;

use App\Filament\Resources\JournalAttendances\JournalAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJournalAttendances extends ListRecords
{
    protected static string $resource = JournalAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
