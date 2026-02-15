<?php

use App\Models\Classes;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('filament.partials.qr-scanner');
});

use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('/classes/{class}/qr-download', function (Classes $class) {

    $url = url('/admin/journals?action=onboarding&token=' . $class->id);

    $qr = QrCode::format('png')
        ->size(300)
        ->generate($url);

    return response($qr)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="qr-kelas-' . $class->id . '.png"');

})->name('classes.qr.download');

