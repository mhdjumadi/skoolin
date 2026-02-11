<?php

namespace App\Filament\Resources\TeachingSchedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeachingScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('academic_year_id'),
                TextEntry::make('class_id'),
                TextEntry::make('teacher_id'),
                TextEntry::make('subject_id'),
                TextEntry::make('lesson_period_id'),
                TextEntry::make('day_id'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
