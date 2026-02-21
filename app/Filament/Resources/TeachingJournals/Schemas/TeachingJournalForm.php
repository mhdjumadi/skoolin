<?php

namespace App\Filament\Resources\TeachingJournals\Schemas;

use App\Models\TeachingSchedule;
use Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeachingJournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('teaching_schedule_id')
                            ->label('Jadwal Mengajar')
                            ->options(
                                TeachingSchedule::with(['day', 'lessonPeriod', 'subject', 'teacher'])
                                    ->get()
                                    ->pluck('full_label', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->required(),
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->disabled(fn () => Auth::user()->hasRole('teacher')),

                        TimePicker::make('start_time')
                            ->label('Mulai')
                            ->required()
                            ->disabled(fn () => Auth::user()->hasRole('teacher')),

                        TimePicker::make('end_time')
                            ->label('Selesai')
                            ->disabled(fn () => Auth::user()->hasRole('teacher')),
                        Textarea::make('material')
                            ->label('Materi')
                            ->required(),
                        Textarea::make('activities')
                            ->label('Kegiatan'),
                        Textarea::make('assessment')
                            ->label('Penilaian'),
                        Textarea::make('notes')
                            ->label('Catatan')
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
