<?php

namespace App\Filament\Resources\TeachingJournals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Schema;

class TeachingJournalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
         ->components([
            Section::make('Informasi Pertemuan')
            ->schema([
                Grid::make(2)->schema([

                    TextEntry::make('teachingSchedule.day.name')
                        ->label('Hari'),

                    TextEntry::make('date')
                        ->label('Tanggal')
                        ->date(),

                    TextEntry::make('teachingSchedule.lessonPeriod.number')
                        ->label('Jam Ke'),

                    TextEntry::make('teachingSchedule.subject.name')
                        ->label('Mata Pelajaran'),

                    TextEntry::make('teachingSchedule.teacher.user.name')
                        ->label('Guru'),

                    TextEntry::make('teachingSchedule.class.name')
                        ->label('Kelas'),

                ]),
            ]),

        Section::make('Materi & Catatan')
            ->schema([
                TextEntry::make('material')
                    ->label('Materi')
                    ->columnSpanFull(),

                TextEntry::make('notes')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]),

        Section::make('Informasi Sistem')
            ->collapsed()
            ->schema([
                Grid::make(2)->schema([
                    TextEntry::make('created_at')
                        ->label('Dibuat Pada')
                        ->dateTime(),

                    TextEntry::make('updated_at')
                        ->label('Terakhir Diupdate')
                        ->dateTime(),
                ]),
            ])
         ]);
    }
}
