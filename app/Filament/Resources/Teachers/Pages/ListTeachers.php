<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Exports\TeacherExporter;
use App\Filament\Resources\Teachers\TeacherResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Guru baru'),
            ExportAction::make()
                ->label('Export guru')
                ->color('warning')
                ->exporter(TeacherExporter::class),
        ];
    }
}
