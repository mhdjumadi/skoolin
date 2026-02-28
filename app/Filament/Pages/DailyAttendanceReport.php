<?php

namespace App\Filament\Pages;

use App\Filament\Exports\StudentAttendanceReportExporter;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentAttendance;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;

class DailyAttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;
    use HasPageShield;


    protected string $view = 'filament.pages.daily-attendance-report';

    protected static ?string $navigationLabel = 'Presensi Harian';
    protected static ?string $title = 'Presensi Harian';
    protected static ?string $modelLabel = 'Presensi Harian';
    protected static ?string $pluralModelLabel = 'Presensi Harian';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCheck;

    public ?array $classId = null; // property untuk filter kelas



    protected function getTableQuery()
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

        // Jika user guardian, filter hanya anaknya
        if ($user->hasRole('guardian')) {
            $studentIds = $user->guardian->students()->pluck('id')->toArray();
            $query->whereIn('students.id', $studentIds);
        }

        return $query;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('class.name')
                    ->label('Kelas'),
                TextColumn::make('academicYear.name')
                    ->label('Tahun Akademik')
                    ->searchable(),
                TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->badge()->color('success'),
                TextColumn::make('izin_count')
                    ->label('Izin')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('sakit_count')
                    ->label('Sakit')
                    ->badge()
                    ->color('info'),
                TextColumn::make('dispensasi_count')
                    ->label('Dispensasi')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([

                // 🔹 Filter Tahun Akademik
                SelectFilter::make('academic_year_id')
                    ->label('Tahun Akademik')
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        return AcademicYear::where('is_active', true)
                            ->value('id'); // cukup satu, tidak perlu array
                    }),

                // 🔹 Filter Tanggal
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari')
                            ->default(now()->startOfMonth()),

                        DatePicker::make('until')
                            ->label('Sampai')
                            ->default(now()),
                    ])
                    ->query(function ($query, $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q) => $q->whereDate('date', '>=', $data['from'])
                            )
                            ->when(
                                $data['until'],
                                fn($q) => $q->whereDate('date', '<=', $data['until'])
                            );
                    }),

                // 🔹 Filter Kelas
                SelectFilter::make('class_id')
                    ->label('Kelas')
                    ->relationship('class', 'name')
                    ->searchable()
                    ->preload(),

            ])
            ->defaultSort('name');
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export Laporan')
                ->exporter(StudentAttendanceReportExporter::class)
                ->formats([ExportFormat::Xlsx]),
        ];
    }
}