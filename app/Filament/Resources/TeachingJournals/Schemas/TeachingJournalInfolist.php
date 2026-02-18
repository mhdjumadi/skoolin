<?php

namespace App\Filament\Resources\TeachingJournals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Schema;

class TeachingJournalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jurnal Mengajar')
                    ->description('Tentang informasi jurnal mengajar')
                    ->icon('heroicon-o-document')
                    ->schema([
                        Fieldset::make('Informasi Pertemuan')
                            ->schema([
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

                        Fieldset::make('Materi & Catatan')
                            ->schema([
                                TextEntry::make('material')
                                    ->label('Materi')
                                    ->columnSpanFull(),

                                TextEntry::make('notes')
                                    ->label('Catatan')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),

                        Fieldset::make('Informasi Sistem')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diupdate')
                                    ->dateTime(),
                            ])
                    ])
                    ->columns('2')
                    ->columnSpanFull()
            ]);
    }
}
