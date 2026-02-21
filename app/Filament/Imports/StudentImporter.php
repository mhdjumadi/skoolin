<?php

namespace App\Filament\Imports;

use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Hash;


class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nisn')
                ->example(['12345678', '12312311']),
            ImportColumn::make('name')
                ->example(['Putra contoh satu', 'Putri contoh dua'])
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('gender')
                ->example(['l', 'p'])
                ->rules(['required']),
            ImportColumn::make('birth_place')
                ->example(['Jakarta', 'Bandung'])
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('birth_date')
                ->example(['2004-02-21', '2002-02-21'])
                ->rules(['date']),
            ImportColumn::make('phone')
                ->example(['8531432176532', '81362536511'])
                ->rules(['required']),
            ImportColumn::make('address')
                ->example(['Jl. Pangrango utara NO.5', 'Jl. Diponegoro'])
                ->requiredMapping()
                ->rules(['required'])
        ];
    }

    public function resolveRecord(): Student
    {
        $student = Student::firstOrNew([
            'nisn' => $this->data['nisn'],
        ]);

        $student->password = Hash::make($this->data['nisn']);

        return $student;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
