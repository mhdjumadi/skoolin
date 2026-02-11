<?php

namespace App\Filament\Resources\TeachingJournals\Pages;

use App\Filament\Resources\TeachingJournals\TeachingJournalResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeachingJournals extends ListRecords
{
    protected static string $resource = TeachingJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('scanQr')
                ->label('Scan QR Kelas')
                // ->icon('heroicon-o-qrcode')
                ->button()
                // ->action('openQrScanner')
                ->modalHeading('Scan QR Kelas')
                ->modalContent(fn() => view('filament.partials.qr-scanner')) // 1️⃣ panggil view blade
                ->modalWidth('lg') // optional, biar modal lebih besar
        ];
    }
}
