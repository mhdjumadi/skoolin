<?php

namespace App\Filament\Exports;

use App\Models\TeachingSchedule;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TeachingScheduleExporter extends Exporter
{
    protected static ?string $model = TeachingSchedule::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('academic_year_id'),
            ExportColumn::make('class_id'),
            ExportColumn::make('teacher_id'),
            ExportColumn::make('subject_id'),
            ExportColumn::make('lesson_period_id'),
            ExportColumn::make('day_id'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your teaching schedule export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
