<x-filament-widgets::widget>
    <div class="relative w-full rounded-[2rem] p-7 text-center overflow-hidden transition-all duration-500 shadow-xl
               /* LIGHT MODE: Hijau Kristal Transparan (Sangat Serasi dengan BG Putih/Abu) */
               bg-gradient-to-br from-emerald-50/80 via-white/90 to-green-50/80 backdrop-blur-md border border-emerald-100/50
               /* DARK MODE: Tetap Gelap sesuai yang Anda suka */
               dark:bg-gradient-to-br dark:from-emerald-900/40 dark:via-slate-900 dark:to-emerald-900/40 dark:text-white dark:border-emerald-500/20"
        x-data="{ 
            time: '{{ $currentTime }}', 
            text: '{{ $currentLessonText }}', 
            date: '{{ now()->locale('id')->translatedFormat('l, d F Y') }}' 
        }" x-init="
            setInterval(() => { @this.call('updateTime'); }, 1000);
            $watch('$wire.currentTime', value => { time = value });
        ">

        {{-- Cahaya Latar yang Menyesuaikan Mode --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/10 dark:bg-emerald-400/20 blur-[60px] rounded-full">
        </div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-green-400/10 dark:bg-green-400/20 blur-[60px] rounded-full">
        </div>

        {{-- Tanggal --}}
        <div class="relative z-10 text-[11px] mb-2 uppercase tracking-[0.3em] font-extrabold text-emerald-600 dark:text-emerald-400"
            x-text="date"></div>

        {{-- Jam: Hitam Slate di Light (Bersih), Emerald Glow di Dark --}}
        <div class="relative z-10 text-6xl font-black tracking-tighter tabular-nums 
                    text-slate-800 dark:text-emerald-400 drop-shadow-[0_2px_10px_rgba(16,185,129,0.1)] dark:drop-shadow-[0_2px_15px_rgba(16,185,129,0.4)]"
            x-text="time">
        </div>

        {{-- Marquee Container: Menggunakan Glassmorphism yang Halus --}}
        <div class="mt-7 relative z-10">
            <div
                class="py-3 px-4 bg-emerald-500/[0.04] dark:bg-emerald-500/10 backdrop-blur-sm border border-emerald-500/10 dark:border-emerald-500/20 rounded-2xl overflow-hidden shadow-sm">
                <div class="whitespace-nowrap flex">
                    <div class="animate-marquee-full uppercase text-[12px] font-bold tracking-widest text-emerald-800 dark:text-emerald-300"
                        x-text="text">
                    </div>
                </div>
            </div>
        </div>

        {{-- Indikator 'LIVE' --}}
        <div class="absolute top-6 right-8 flex items-center gap-1.5 opacity-80">
            <span class="relative flex h-2 w-2">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span
                class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 tracking-widest uppercase">Live</span>
        </div>
    </div>

    <style>
        .animate-marquee-full {
            display: inline-block;
            padding-left: 100%;
            animation: marquee-full 35s linear infinite;
        }

        @keyframes marquee-full {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
    </style>
</x-filament-widgets::widget>