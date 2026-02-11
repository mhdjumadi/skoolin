<?php

namespace App\Filament\Resources\Classes\RelationManagers;

use App\Filament\Resources\Students\StudentResource;
use App\Models\AcademicYear;
use Filament\Actions\CreateAction;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';
    protected static ?string $recordTitleAttribute = 'name';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('pivot.academicYear.name')
                    ->label('Tahun akademik')
                    ->searchable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Tambah siswa')
                    ->preloadRecordSelect()
                    ->schema(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->multiple(),
                        Select::make('academic_year_id')
                            ->label('Tahun akademik')
                            ->options(
                                AcademicYear::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->reactive(),
                    ])
            ])
            ->recordActions([
                // EditAction::make()
                //     ->form(fn(EditAction $action): array => [
                //         Toggle::make('is_active')
                //             ->label('Aktifkan siswa')
                //     ]),
                DetachAction::make(),
            ]);
    }
}
