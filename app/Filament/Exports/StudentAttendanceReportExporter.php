<?php

namespace App\Filament\Exports;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentAttendance;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class StudentAttendanceReportExporter extends Exporter
{
    public static function modifyQuery(Builder $query): Builder
    {

        $user = auth()->user();
        $query = StudentAttendance::query()
            ->selectRaw('
                student_id,
                class_id,
                academic_year_id,
                student_id || "-" || class_id || "-" || academic_year_id as id,
                COUNT(CASE WHEN status IN ("hadir","terlambat") THEN 1 END) as hadir_count,
                COUNT(CASE WHEN status = "izin" THEN 1 END) as izin_count,
                COUNT(CASE WHEN status = "sakit" THEN 1 END) as sakit_count,
                COUNT(CASE WHEN status = "dispensasi" THEN 1 END) as dispensasi_count
            ')
            ->groupBy('student_id', 'class_id', 'academic_year_id');

        if ($user->hasRole('guardian')) {
            $studentIds = $user->guardian->students()->pluck('id')->toArray();
            $query->whereIn('students.id', $studentIds);
        }

        return $query;
    }


    public static function getColumns(): array
    {

        return [
            ExportColumn::make('student.name')
                ->label('Nama Siswa'),
            ExportColumn::make('class.name')
                ->label('Kelas'),
            ExportColumn::make('academicYear.name')
                ->label('Tahun Akademik'),
            ExportColumn::make('hadir_count')
                ->label('Hadir'),
            ExportColumn::make('izin_count')
                ->label('Izin'),
            ExportColumn::make('sakit_count')
                ->label('Sakit'),
            ExportColumn::make('dispensasi_count')
                ->label('Dispensasi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your student attendance report export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
