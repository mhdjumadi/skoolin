<?php

namespace App\Filament\Pages;

use App\Models\TeachingJournal;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use UnitEnum;
use BackedEnum;

class JournalAttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;
    use HasPageShield;


    protected string $view = 'filament.pages.journal-attendance-report';
    protected static ?string $navigationLabel = 'Presensi Jurnal';
    protected static ?string $title = 'Presensi Jurnal';
    protected static ?string $modelLabel = 'Presensi Jurnal';
    protected static ?string $pluralModelLabel = 'Presensi Jurnal';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCheck;

    protected function getTableQuery()
    {
        $query = TeachingJournal::query()
            ->with([
                'teachingSchedule.lessonPeriod',
                'teachingSchedule.class',
                'teachingSchedule.subject',
                'teachingSchedule.teacher.user',
                'teachingSchedule.academicYear',
                'attendances.student',
            ])
            ->withCount([
                'attendances',
                'attendances as hadir_count' => fn($q) => $q->where('status', 'hadir'),
                'attendances as sakit_count' => fn($q) => $q->where('status', 'sakit'),
                'attendances as izin_count' => fn($q) => $q->where('status', 'izin'),
                'attendances as alpha_count' => fn($q) => $q->where('status', 'tanpa_keterangan'),
            ]);

        return $query;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('teachingSchedule.startPeriod.number')
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
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teachingSchedule.class.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('teachingSchedule.subject.name')
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('teachingSchedule.teacher.user.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('attendances_count')
                    ->label('Absensi')
                    ->formatStateUsing(function ($state, $record) {

                        return "Total: {$state} | "
                            . "H: {$record->hadir_count} | "
                            . "S: {$record->sakit_count} | "
                            . "I: {$record->izin_count} | "
                            . "A: {$record->alpha_count}";
                    })
                    ->color(
                        fn($record) =>
                        $record->alpha_count > 0 ? 'danger' : 'success'
                    ),
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
                            ->when(isset($data['from']), fn($q) => $q->whereDate('teaching_journals.date', '>=', $data['from']))
                            ->when(isset($data['until']), fn($q) => $q->whereDate('teaching_journals.date', '<=', $data['until']));
                    }),

                // Filter tahun akademik
                SelectFilter::make('academic_year')
                    ->label('Tahun Akademik')
                    ->relationship('teachingSchedule.academicYear', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->relationship('teachingSchedule.class', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('teacher')
                    ->label('Guru')
                    ->relationship('teachingSchedule.teacher', 'id') // pakai id dulu
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name)
                    ->visible(fn() => auth()->user()->hasRole('admin'))
                    ->searchable()
                    ->multiple()
                    ->preload()
            ])
            ->defaultSort('date', 'desc');
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