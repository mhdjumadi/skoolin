<?php

namespace App\Filament\Resources\Classes\Pages;

use App\Filament\Resources\Classes\ClassesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClasses extends ListRecords
{
    protected static string $resource = ClassesResource::class;
    protected static ?string $title = 'Kelas';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Kelas baru'),
        ];
    }
}
