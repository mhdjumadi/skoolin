<?php

namespace App\Filament\Widgets\Guardian;

use App\Models\TeachingSchedule;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use DB;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class DailyLessonSchedule extends TableWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Jadwal Pelajaran Siswa Hari Ini';
    protected static ?int $sort = 2;


    public function table(Table $table): Table
    {
        $user = auth()->user();
        $todayNumber = now()->dayOfWeekIso;

        // Ambil semua student_id anak guardian
        $studentIds = $user?->guardian?->students()->pluck('id')->toArray();

        // Ambil semua class_id dari pivot student_classes
        $classIds = DB::table('student_classes')
            ->whereIn('student_id', $studentIds)
            ->pluck('class_id')
            ->toArray();

        // Query utama untuk tabel
        $query = TeachingSchedule::query()
            ->with(['startPeriod', 'endPeriod', 'class', 'subject', 'teacher.user', 'day'])
            ->whereIn('class_id', $classIds)
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber));

        return $table
            ->query($query)
            ->columns([
                // Nomor jam: start-end, jika sama tampil 1
                TextColumn::make('startPeriod.number')
                    ->label('Jam Ke')
                    ->formatStateUsing(function ($state, $record) {
                        $start = $record->startPeriod?->number;
                        $end = $record->endPeriod?->number;

                        if (!$start || !$end) {
                            return '-';
                        }

                        return $start === $end ? (string) $start : "{$start}-{$end}";
                    })
                    ->sortable(),

                TextColumn::make('class.name')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),

                TextColumn::make('teacher.user.name')
                    ->label('Guru'),

                // Jam mulai & selesai
                TextColumn::make('startPeriod.start_time')
                    ->label('Mulai')
                    ->time('H:i'),

                TextColumn::make('endPeriod.end_time')
                    ->label('Selesai')
                    ->time('H:i'),
            ]);
    }
}
