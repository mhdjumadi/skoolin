<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Exports\StudentExporter;
use App\Filament\Imports\StudentImporter;
use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Siswa baru'),
            ExportAction::make()
                ->label('Export siswa')
                ->color('warning')
                ->exporter(StudentExporter::class),
            ImportAction::make()
                ->label('Import siswa')
                ->color('info')
                ->importer(StudentImporter::class)
        ];
    }
}
