<?php

namespace App\Filament\Exports;

use App\Models\StudentAttendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class StudentAttendanceExporter extends Exporter
{
    protected static ?string $model = StudentAttendance::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('student_id'),
            ExportColumn::make('academicYear.name'),
            ExportColumn::make('date'),
            ExportColumn::make('check_in'),
            ExportColumn::make('check_out'),
            ExportColumn::make('status'),
            ExportColumn::make('note'),
            ExportColumn::make('device'),
            ExportColumn::make('created_by'),
            ExportColumn::make('updated_by'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student attendance export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
