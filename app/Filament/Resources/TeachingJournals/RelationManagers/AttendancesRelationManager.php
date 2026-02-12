<?php

namespace App\Filament\Resources\TeachingJournals\RelationManagers;

use App\Models\Classes;
use App\Models\JournalAttendance;
use App\Models\StudentClass;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    // --------------------------
    // Form
    // --------------------------
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'nama')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'tanpa_keterangan' => 'Tanpa Keterangan',
                    ])
                    ->default('hadir')
                    ->required(),

                TextInput::make('notes')
                    ->label('Keterangan')
                    ->placeholder('-'),
            ]);
    }

    // --------------------------
    // InfoList
    // --------------------------
    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')->label('ID'),
                TextEntry::make('student.nama')->label('Nama Siswa'),
                TextEntry::make('status')->label('Status'),
                TextEntry::make('notes')->label('Keterangan')->placeholder('-'),
                TextEntry::make('created_at')->dateTime()->label('Dibuat')->placeholder('-'),
                TextEntry::make('updated_at')->dateTime()->label('Diupdate')->placeholder('-'),
            ]);
    }

    // --------------------------
    // Table
    // --------------------------
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student_id')
            ->columns([
                TextColumn::make('student.name')->label('Nama Siswa')->searchable(),
                TextColumn::make('status')->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'tanpa_keterangan' => 'Tanpa keterangan',
                    ])
                    ->inline()
                    ->searchable()
                    ->sortable(),
                TextInputColumn::make('notes')
                    ->label('Keterangan')
                    ->searchable()
                    ->inline()
                    ->placeholder('-'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Default CreateAction jika ingin menambahkan manual
                // CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
