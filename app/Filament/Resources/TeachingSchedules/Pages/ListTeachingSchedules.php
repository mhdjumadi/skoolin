<?php

namespace App\Filament\Resources\TeachingSchedules\Pages;

use App\Filament\Exports\TeachingScheduleExporter;
use App\Filament\Resources\TeachingSchedules\TeachingScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListTeachingSchedules extends ListRecords
{
    protected static string $resource = TeachingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Jadwal baru'),
            ExportAction::make()
                ->label('Export jadwal')
                ->color('warning')
                ->exporter(TeachingScheduleExporter::class),
        ];
    }
}
