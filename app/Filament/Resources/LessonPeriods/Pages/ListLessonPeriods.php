<?php

namespace App\Filament\Resources\LessonPeriods\Pages;

use App\Filament\Resources\LessonPeriods\LessonPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLessonPeriods extends ListRecords
{
    protected static string $resource = LessonPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Jam mengajar baru'),
        ];
    }
}
