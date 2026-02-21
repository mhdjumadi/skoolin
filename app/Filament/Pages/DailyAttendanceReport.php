<?php

namespace App\Filament\Pages;

use App\Models\Classes;
use App\Models\Student;
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
use UnitEnum;
use BackedEnum;

class DailyAttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.daily-attendance-report';

    protected static ?string $navigationLabel = 'Presensi Harian';
    protected static ?string $modelLabel = 'Presensi Harian';
    protected static ?string $pluralModelLabel = 'Presensi Harian';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCheck;

    public ?array $classId = null; // property untuk filter kelas


    protected function getTableQuery()
    {
        $query = Student::query()
            ->with('attendances.class')
            ->withCount([
                'attendances as hadir_count' => fn($q) => $q->where('status', 'hadir'),
                'attendances as izin_count' => fn($q) => $q->where('status', 'izin'),
                'attendances as sakit_count' => fn($q) => $q->where('status', 'sakit'),
                'attendances as dispensasi_count' => fn($q) => $q->where('status', 'dispensasi'),
            ])
            ->where('students.is_active', true);

        return $query;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('attendances.class.name')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('attendances.academicYear.name')
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
                // Filter tanggal
                Filter::make('date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('until')
                            ->label('Sampai')
                            ->default(now()->addDay()),
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['from']) && !empty($data['until'])) {
                            $query->whereHas('attendances', function ($q) use ($data) {
                                $q->whereBetween('date', [$data['from'], $data['until']]);
                            });
                        }
                    }),
                // Filter kelas
                SelectFilter::make('academic_year')
                    ->label('Tahun Akademik')
                    ->relationship('attendances.academicYear', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                // Filter kelas
                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->relationship('attendances.class', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('name');
    }

    protected function getTableHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Export Laporan')
                ->formats([ExportFormat::Xlsx]),
        ];
    }
}