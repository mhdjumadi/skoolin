<?php

namespace App\Filament\Exports;

use App\Models\JournalAttendanceReport;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Number;

class JournalAttendanceReportExporter extends Exporter
{
    protected static ?string $model = JournalAttendanceReport::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('date')
                ->label('Tanggal'),

            ExportColumn::make('teachingSchedule.startPeriod.number')
                ->label('Jam Mengajar')
                ->formatStateUsing(function ($state, $record) {

                    $schedule = $record->teachingSchedule;

                    $startNumber = $schedule?->startPeriod?->number;
                    $endNumber = $schedule?->endPeriod?->number;

                    $startTime = $schedule?->startPeriod?->start_time;
                    $endTime = $schedule?->endPeriod?->end_time;

                    if (!$startNumber || !$endNumber || !$startTime || !$endTime) {
                        return '-';
                    }

                    $rangeNumber = $startNumber == $endNumber
                        ? $startNumber
                        : "{$startNumber}-{$endNumber}";

                    $startFormatted = Carbon::parse($startTime)->format('H.i');
                    $endFormatted = Carbon::parse($endTime)->format('H.i');

                    return "{$rangeNumber} - ({$startFormatted} - {$endFormatted})";
                }),

            ExportColumn::make('teachingSchedule.class.name')
                ->label('Kelas'),

            ExportColumn::make('teachingSchedule.subject.name')
                ->label('Mata Pelajaran'),

            ExportColumn::make('teachingSchedule.teacher.user.name')
                ->label('Guru'),

            ExportColumn::make('attendances_count')
                ->label('Absensi')
                ->formatStateUsing(function ($state, $record) {

                    return "Total: {$state} | "
                        . "H: {$record->hadir_count} | "
                        . "S: {$record->sakit_count} | "
                        . "I: {$record->izin_count} | "
                        . "A: {$record->alpha_count}";
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your journal attendance report export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
