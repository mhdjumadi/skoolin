<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Filament\Resources\Guardians\GuardianResource;
use App\Models\AcademicYear;
use Filament\Actions\CreateAction;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class GuardiansRelationManager extends RelationManager
{
    protected static string $relationship = 'guardians';
    protected static ?string $recordTitleAttribute = 'name';


    public function isReadOnly(): bool
    {
        return false;
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('relationship')
                    ->label('Hubungan')
                    ->formatStateUsing(fn($state) => [
                        'ayah' => 'Ayah',
                        'ibu' => 'Ibu',
                        'wali' => 'Wali',
                    ][$state] ?? '-')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Tambah Orang Tua / Wali')
                    ->recordTitle(fn($record) => $record->user?->name)
                    ->preloadRecordSelect()
                    ->schema(fn(AttachAction $action): array => [

                        $action->getRecordSelect()
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => $record->user?->name ?? '-'
                            )
                            ->searchable(),

                        Select::make('relationship')
                            ->label('Hubungan')
                            ->options([
                                'ayah' => 'Ayah',
                                'ibu' => 'Ibu',
                                'wali' => 'Wali',
                            ])
                            ->default('ayah')
                            ->required(),
                    ]),
            ])

            ->recordActions([
                EditAction::make()
                    ->form(fn(EditAction $action): array => [
                        Select::make('relationship')
                            ->options([
                                'ayah' => 'Ayah',
                                'ibu' => 'Ibu',
                                'wali' => 'Wali',
                            ])
                            ->default('ayah')
                            ->required(),
                    ]),
                DetachAction::make(),
            ]);
    }
}
