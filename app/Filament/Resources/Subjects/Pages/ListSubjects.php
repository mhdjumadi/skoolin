<?php

namespace App\Filament\Resources\Subjects\Pages;

use App\Filament\Exports\SubjectExporter;
use App\Filament\Resources\Subjects\SubjectResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Mata pelajaran baru'),
            ExportAction::make()
                ->label('Export mata pelajaran')
                ->color('warning')
                ->exporter(SubjectExporter::class),
        ];
    }
}
