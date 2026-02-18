<?php

namespace App\Filament\Resources\StudentAttendances\Pages;

use App\Filament\Exports\StudentAttendanceExporter;
use App\Filament\Resources\StudentAttendances\StudentAttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentAttendances extends ListRecords
{
    protected static string $resource = StudentAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Presensi baru'),
            ExportAction::make()
                ->label('Export presensi')
                ->color('warning')
                ->exporter(StudentAttendanceExporter::class),
        ];
    }
}
