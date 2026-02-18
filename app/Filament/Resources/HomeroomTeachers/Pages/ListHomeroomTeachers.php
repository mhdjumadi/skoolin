<?php

namespace App\Filament\Resources\HomeroomTeachers\Pages;

use App\Filament\Exports\HomeroomTeacherExporter;
use App\Filament\Resources\HomeroomTeachers\HomeroomTeacherResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeroomTeachers extends ListRecords
{
    protected static string $resource = HomeroomTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Wali kelas baru'),
            ExportAction::make()
                ->label('Export wali kelas')
                ->color('warning')
                ->exporter(HomeroomTeacherExporter::class),
        ];
    }
}
