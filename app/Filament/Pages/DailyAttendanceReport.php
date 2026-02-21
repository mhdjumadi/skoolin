<?php

namespace App\Filament\Pages;

use App\Models\Student;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use BackedEnum;

class DailyAttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.daily-attendance-report';

    protected static ?string $title = 'Presensi Harian';
    protected static ?string $navigationLabel = 'Presensi Harian';
    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected function getTableQuery()
    {
        $user = Auth::user();

        $query = Student::query()
            ->select([
                'students.id',
                'students.name',

                DB::raw("SUM(CASE WHEN student_attendances.status = 'hadir' THEN 1 ELSE 0 END) AS hadir"),
                DB::raw("SUM(CASE WHEN student_attendances.status = 'izin' THEN 1 ELSE 0 END) AS izin"),
                DB::raw("SUM(CASE WHEN student_attendances.status = 'sakit' THEN 1 ELSE 0 END) AS sakit"),
                DB::raw("SUM(CASE WHEN student_attendances.status = 'dispensasi' THEN 1 ELSE 0 END) AS dispensasi"),
            ])
            ->leftJoin('student_attendances', 'student_attendances.student_id', '=', 'students.id')
            ->where('students.is_active', true)
            ->groupBy('students.id', 'students.name');

        // ===== ROLE FILTER =====

        if ($user->hasRole('teacher') && $user->teacher) {

            $query->whereExists(function ($sub) use ($user) {

                $sub->select(DB::raw(1))
                    ->from('student_classes')
                    ->join('attendances', function ($join) {
                        $join->on('attendances.student_id', '=', 'student_classes.student_id')
                            ->on('attendances.academic_year_id', '=', 'student_classes.academic_year_id');
                    })
                    ->whereColumn('student_classes.student_id', 'students.id')
                    ->whereIn(
                        'student_classes.class_id',
                        $user->teacher
                            ->classes()
                            ->pluck('classes.id')
                    );
            });
        }

        if ($user->hasRole('guardian')) {
            $query->whereExists(function ($sub) use ($user) {
                $sub->select(DB::raw(1))
                    ->from('guardian_students')
                    ->whereColumn('guardian_students.student_id', 'students.id')
                    ->where('guardian_students.guardian_id', $user->guardian?->id);
            });
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('hadir')
                    ->label('Hadir')
                    ->badge()->color('success'),
                TextColumn::make('izin')
                    ->label('Izin')->badge()
                    ->color('warning'),
                TextColumn::make('sakit')
                    ->label('Sakit')
                    ->badge()
                    ->color('info'),
                TextColumn::make('dispensasi')
                    ->label('Dispensasi')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
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
                            ->when(isset($data['from']), fn($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when(isset($data['until']), fn($q) => $q->whereDate('date', '<=', $data['until']));
                    }),

                SelectFilter::make('class')
                    ->label('Kelas')
                    ->query(function ($query, $classId) {
                        $query->whereExists(function ($sub) use ($classId) {
                            $sub->select(DB::raw(1))
                                ->from('student_classes')
                                ->join('student_attendances', function ($join) {
                                    $join->on('student_attendances.student_id', '=', 'student_classes.student_id')
                                        ->on('student_attendances.academic_year_id', '=', 'student_classes.academic_year_id');
                                })
                                ->whereColumn('student_classes.student_id', 'students.id')
                                ->where('student_classes.class_id', $classId);
                        });
                    })
            ])
            ->defaultSort('name');
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export laporan')
                ->icon('heroicon-o-arrow-down-tray')
                ->formats([ExportFormat::Xlsx]),
        ];
    }
}