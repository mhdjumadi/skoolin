<?php

namespace App\Filament\Resources\RfidMasters\Tables;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Student;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RfidMastersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rfid_uid')
                    ->label('ID Kartu')
                    ->searchable(),
                TextColumn::make('device')
                    ->label("Perangkat")
                    ->searchable(),
                TextColumn::make('student.name')
                    ->label('Siswa')
                    ->searchable()
                    ->getStateUsing(fn ($record) => 
                        $record->student?->name ?? 'Belum dipasangkan'
                    )
                    ->color(fn ($record) => 
                        $record->student_id ? 'success' : 'warning'
                    )
                    ->url(fn ($record) =>
                        $record->student_id
                            ? StudentResource::getUrl('view', ['record' => $record->student_id])
                            : null
                    )
                    ->openUrlInNewTab(false),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Nonaktif')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('add_student')
                    ->label('Pasangkan Siswa')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn($record) => is_null($record->student_id))
                    ->form([
                        Select::make('student_id')
                            ->label('Pilih Siswa')
                            ->options(function () {
                                return Student::where(function ($q) {
                                    $q->whereDoesntHave('rfidMasters')
                                        ->orWhereDoesntHave('rfidMasters', function ($sub) {
                                            $sub->where('is_active', true);
                                        });
                                })->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),

                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'student_id' => $data['student_id'],
                            'is_active' => true,
                            'notes' => 'Assign student',
                        ]);

                        Notification::make()
                            ->title('Siswa berhasil dipasangkan')
                            ->success()
                            ->send();
                    }),

                Action::make('remove_student')
                    ->label('Lepaskan Siswa')
                    ->icon('heroicon-o-user-minus')
                    ->color('warning')
                    ->visible(fn($record) => !is_null($record->student_id))
                    ->action(function (array $data, $record, $livewire) {
                        // Update record
                        $record->update([
                            'student_id' => null,
                            'is_active' => false,
                            'notes' => ''
                        ]);

                        Notification::make()
                            ->title('Siswa berhasil dilepaskan')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
