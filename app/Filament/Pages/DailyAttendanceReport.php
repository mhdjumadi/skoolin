<?php

namespace App\Filament\Pages;

use App\Filament\Exports\StudentAttendanceReportExporter;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentAttendance;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use DB;
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


    protected function getTableQuery()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $user = auth()->user();

        $sub = DB::table('student_attendances')
            ->select(
                'student_id',
                'class_id',
                'academic_year_id',
                DB::raw("SUM(CASE WHEN status IN ('hadir','terlambat') THEN 1 ELSE 0 END) as hadir_count"),
                DB::raw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin_count"),
                DB::raw("SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit_count"),
                DB::raw("SUM(CASE WHEN status = 'dispensasi' THEN 1 ELSE 0 END) as dispensasi_count")
            )
            ->where('academic_year_id', '=', $activeYear->id)
            ->when($this->filterDate ?? null, function ($q, $filterDate) {
                if (!empty($filterDate['from'])) {
                    $q->whereDate('date', '>=', $filterDate['from']);
                }
                if (!empty($filterDate['until'])) {
                    $q->whereDate('date', '<=', $filterDate['until']);
                }
            })
            ->groupBy('student_id', 'class_id', 'academic_year_id');

        $query = Student::query()
            ->select(
                DB::raw("
                    students.id || '-' || 
                    COALESCE(attendance_summary.class_id, 0) || '-' || 
                    COALESCE(attendance_summary.academic_year_id, 0) as id
                "),
                'students.id as student_id',
                'students.name as student_name',
                'classes.name as class_name',
                'academic_years.name as academic_year',
                DB::raw("COALESCE(attendance_summary.hadir_count, 0) as hadir_count"),
                DB::raw("COALESCE(attendance_summary.izin_count, 0) as izin_count"),
                DB::raw("COALESCE(attendance_summary.sakit_count, 0) as sakit_count"),
                DB::raw("COALESCE(attendance_summary.dispensasi_count, 0) as dispensasi_count")
            )
            ->leftJoinSub($sub, 'attendance_summary', function ($join) {
                $join->on('students.id', '=', 'attendance_summary.student_id');
            })
            ->leftJoin('classes', 'classes.id', '=', 'attendance_summary.class_id')
            ->leftJoin('academic_years', 'academic_years.id', '=', 'attendance_summary.academic_year_id')
            ->orderBy('student_name');

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
                TextColumn::make('student_name')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('class_name')
                    ->label('Kelas'),
                TextColumn::make('academic_year')
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

                // // 🔹 Filter Tahun Akademik
                // SelectFilter::make('academic_year_id')
                //     ->label('Tahun Akademik')
                //     ->relationship('academicYear', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->default(function () {
                //         return AcademicYear::where('is_active', true)
                //             ->value('id'); // cukup satu, tidak perlu array
                //     }),

                // 🔹 Filter Tanggal
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')->label('Dari')->default(now()->startOfMonth()),
                        DatePicker::make('until')->label('Sampai')->default(now()),
                    ])
                    ->query(function ($query, $data) {
                        $this->filterDate = $data;
                        return $query;
                    }),

                // // 🔹 Filter Kelas
                // SelectFilter::make('class_id')
                //     ->label('Kelas')
                //     ->relationship('class', 'name')
                //     ->searchable()
                //     ->preload(),

            ])
            ->defaultSort('students.name');
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Download laporan')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(StudentAttendanceReportExporter::class)
                ->formats([ExportFormat::Xlsx]),
        ];
    }
}