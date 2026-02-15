<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Models\AcademicYear;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;


use Illuminate\Support\Facades\DB;



class ClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'classes';
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
                    ->label('Kelas')
                    ->searchable(),

                TextColumn::make('pivot.academicYear.name')
                    ->label('Tahun akademik')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Tambah kelas')
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
                            ->reactive(),
                    ])
            ])

            ->recordActions([
                EditAction::make()
                    ->form(fn(EditAction $action): array => [
                        Toggle::make('is_active')
                            ->label('Aktifkan kelas')
                    ]),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
