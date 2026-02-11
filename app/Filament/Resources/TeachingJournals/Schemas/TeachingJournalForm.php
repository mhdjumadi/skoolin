<?php

namespace App\Filament\Resources\TeachingJournals\Schemas;

use App\Models\TeachingSchedule;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TeachingJournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->required(),
                TextInput::make('material')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
