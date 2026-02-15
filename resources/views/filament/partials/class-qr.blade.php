@php
    $url = url('/admin/journals?action=onboarding&token=' . $record->id);
@endphp

<div class="flex flex-col items-center space-y-4">

    <div class="p-5 bg-white rounded-2xl shadow-sm border">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
    ->size(220)
    ->generate($url) !!}
    </div>

    </br>

    <x-filament::button tag="a" :href="route('classes.qr.download', $record)" color="primary"
        class="w-full justify-center">
        ⬇ Download QR Code kelas {{ $record->name }}
    </x-filament::button>

</div>