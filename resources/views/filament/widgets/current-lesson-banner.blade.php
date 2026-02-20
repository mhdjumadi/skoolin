<x-filament-widgets::widget>
    {{-- <x-filament::section> --}}
        <div class="w-full rounded-xl shadow-lg p-4 text-center font-semibold overflow-hidden bg-[#134686] text-white" x-data="{ 
                time: '{{ $currentTime }}', 
                text: '{{ $currentLessonText }}', 
                status: '{{ $currentLessonStatus }}', 
                date: '{{ now()->format('l, d F Y') }}' 
            }" x-init="setInterval(() => { @this.call('updateTime'); }, 1000)">
            <!-- Tanggal & Hari -->

            <div class="text-lg mb-2" x-text="date"></div>

            <!-- Jam sekarang -->
            <div class="text-3xl font-bold mb-1" x-text="time"></div>

            <!-- Info pelajaran dengan marquee jika panjang -->
            <div class="overflow-hidden whitespace-nowrap mt-4">
                <div class="inline-block animate-marquee" x-text="text"></div>
            </div>
        </div>
    {{-- </x-filament::section> --}}

    <!-- Tailwind marquee animation -->
    <style>
        .animate-marquee {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 60s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</x-filament-widgets::widget>