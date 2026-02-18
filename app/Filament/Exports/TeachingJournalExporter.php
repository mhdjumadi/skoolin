<?php

namespace App\Filament\Exports;

use App\Models\TeachingJournal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TeachingJournalExporter extends Exporter
{
    protected static ?string $model = TeachingJournal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('teaching_schedule_id'),
            ExportColumn::make('date'),
            ExportColumn::make('start_time'),
            ExportColumn::make('end_time'),
            ExportColumn::make('material'),
            ExportColumn::make('activities'),
            ExportColumn::make('assessment'),
            ExportColumn::make('notes'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your teaching journal export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
