<?php

namespace App\Filament\Pages;

use App\Models\TeachingJournal;
use App\Models\Classes;
use App\Models\Teacher;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\DateFilter;
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

    protected string $view = 'filament.pages.journal-attendance-report';
    protected static ?string $navigationLabel = 'Presensi Jurnal';
    protected static ?string $modelLabel = 'Presensi Jurnal';
    protected static ?string $pluralModelLabel = 'Presensi Jurnal';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCheck;

    public ?string $date = null;
    public ?int $classId = null;
    public ?int $teacherId = null;

    protected function getTableQuery()
    {
        $query = TeachingJournal::query()
            ->with([
                'teachingSchedule.lessonPeriod',
                'teachingSchedule.class',
                'teachingSchedule.subject',
                'teachingSchedule.teacher.user',
                'attendances.student',
            ]);

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        if ($this->classId) {
            $query->whereHas('teachingSchedule', fn($q) => $q->where('class_id', $this->classId));
        }

        if ($this->teacherId) {
            $query->whereHas('teachingSchedule', fn($q) => $q->where('teacher_id', $this->teacherId));
        }

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

                TextColumn::make('teachingSchedule.lessonPeriod.number')
                    ->label('Jam Ke')
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
                    ->label('Jumlah Siswa')
                    ->counts('attendances')
                    ->color('success'),
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

                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->relationship('teachingSchedule.class', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('teacher')
                    ->label('Guru')
                    ->relationship('teachingSchedule.teacher.user', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
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