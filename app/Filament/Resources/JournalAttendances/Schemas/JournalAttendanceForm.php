<?php

namespace App\Filament\Resources\JournalAttendances\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JournalAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('teaching_journal_id')
                    ->required(),
                TextInput::make('student_id')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('hadir'),
                TextInput::make('notes'),
            ]);
    }
}
