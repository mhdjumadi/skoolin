<?php

namespace App\Filament\Resources\HomeroomTeachers\Pages;

use App\Filament\Resources\HomeroomTeachers\HomeroomTeacherResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeroomTeachers extends ListRecords
{
    protected static string $resource = HomeroomTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->label('Wali kelas baru'),
        ];
    }
}
