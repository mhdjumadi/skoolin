<?php

namespace App\Filament\Resources\StudentAttendances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentAttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('student_id'),
                TextEntry::make('academic_year_id'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('check_in')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('check_out')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('device')
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
