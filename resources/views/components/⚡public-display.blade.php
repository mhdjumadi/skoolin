<?php

use Livewire\Component;
use Carbon\Carbon;

new class extends Component {
    public $now;
    public $jadwalSekarang = [];
    public $jadwalBerikutnya = [];
    public $siswaBelumPresensi = [];
    public $guruBelumMasuk = [];
    public $absensiHariIni = [];

    // Fungsi mount hanya jalan sekali saat load pertama
    public function mount()
    {
        $this->loadData();
    }

    // Fungsi ini dipanggil oleh wire:poll setiap 5 detik
    public function loadData()
    {
        $this->now = Carbon::now();

        // Di sini nantinya kamu ganti dengan query database asli
        // Contoh: $this->jadwalSekarang = Jadwal::where('jam_mulai', '<=', now())...

        $this->loadDummy();
    }

    public function loadDummy()
    {
        // ... (isi data dummy kamu yang sudah ada)
        $this->jadwalSekarang = [
            ['kelas' => 'X IPA 1', 'mapel' => 'Matematika', 'guru' => 'Budi Santoso, S.Pd', 'jam' => '07:00 - 08:30'],
            ['kelas' => 'XI IPS 2', 'mapel' => 'Bahasa Indonesia', 'guru' => 'Siti Aminah, S.Pd', 'jam' => '07:00 - 08:30'],
            ['kelas' => 'XII TKJ 1', 'mapel' => 'Pemrograman Web', 'guru' => 'Eko Prasetyo, M.Kom', 'jam' => '07:00 - 08:30'],
            // Tambahkan data lebih banyak untuk testing carousel
        ];

        $this->absensiHariIni = [
            'total' => 320,
            'hadir' => rand(290, 300), // Kita buat sedikit random agar terlihat "real-time" saat testing
            'izin' => 10,
            'sakit' => 7,
            'alpha' => 5
        ];

        $this->siswaBelumPresensi = [
            ['nama' => 'Andi Pratama', 'kelas' => 'XA'],
            ['nama' => 'Budi Sudarsono', 'kelas' => 'XB'],
            ['nama' => 'Citra Lestari', 'kelas' => 'XIA'],
            ['nama' => 'Dedi Cahyadi', 'kelas' => 'XIIA'],
            ['nama' => 'Eka Wijaya', 'kelas' => 'XIB'],
        ];

        $this->guruBelumMasuk = [
            ['nama' => 'Budi Santoso, S.Pd', 'kelas' => 'X IPA 1', 'mapel' => 'TIK'],
            ['nama' => 'Siti Aminah, S.Pd', 'kelas' => 'XI IPS 2', 'mapel' => 'MATEMATIKA'],
        ];
    }
};
?>

<div wire:poll.5s class="h-screen bg-slate-900 text-white p-6 flex flex-col overflow-hidden">

    {{-- HEADER --}}
    <div
        class="flex justify-between items-center mb-8 bg-slate-800/40 backdrop-blur-md p-2 rounded-2xl border border-slate-700/50 shadow-2xl flex-none relative overflow-hidden group">
    
        {{-- Decorative Glow behind Header --}}
        <div class="absolute -left-10 top-0 w-40 h-full bg-green-500/10 blur-3xl rounded-full"></div>
    
        <div class="flex items-center gap-5 relative z-10">
            {{-- Logo Placeholder / Icon Sekolah --}}
            <div
                class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center shadow-lg shadow-green-900/20 group-hover:rotate-3 transition-transform duration-500">
                <span class="text-3xl font-black text-white">H</span>
            </div>
    
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter uppercase leading-none">
                    DCC <span class="text-green-400">SMK HARAPAN</span>
                </h1>
                <div class="flex items-center gap-3 mt-1 text-slate-400">
                
                    <p class="text-sm font-bold tracking-widest uppercase opacity-80">
                        {{ $now->locale('id')->translatedFormat('l') }}
                    </p>
                
                    <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                
                    <p class="text-sm font-medium">
                        {{ $now->locale('id')->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    
        <div class="flex items-center gap-6 relative z-10">
            {{-- Status Indicator --}}
            <div class="hidden md:flex flex-col items-end border-r border-slate-700 pr-6">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">System Status</span>
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                    <span class="text-xs font-bold text-green-400/80 uppercase">Live Monitoring</span>
                </div>
            </div>

            {{-- Status Indicator di dalam Header --}}
            <div class="hidden md:flex flex-col items-end border-r border-slate-700 pr-6">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Last Update</span>
                <div class="flex items-center gap-2">
                    {{-- Dot hijau yang berkedip --}}
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-mono font-bold text-green-400/80">
                        {{ now()->format('H:i:s') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="flex flex-1 gap-6 overflow-hidden">

        {{-- LEFT PANEL --}}
        <div class="w-1/5 flex flex-col gap-4 h-full">
        
            {{-- CARD ATAS: Siswa Belum Presensi --}}
            <div class="bg-slate-800/60 backdrop-blur-md rounded-2xl p-4 shadow-2xl flex-1 flex flex-col overflow-hidden border border-slate-700/50"
                wire:ignore>
                <div class="flex items-center justify-between mb-3 border-b border-red-500/30 pb-2 flex-none">
                    <h2 class="text-[11px] font-black text-red-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        Siswa Belum Presensi
                    </h2>
                    <span class="text-[10px] font-mono text-slate-500" x-text="items.length"
                        x-data="{ items: {{ json_encode($siswaBelumPresensi) }} }"></span>
                </div>
        
                <div x-data="{
                    index: 0,
                    items: {{ json_encode($siswaBelumPresensi) }},
                    // Fungsi untuk menghitung tinggi satu card secara otomatis
                    get itemHeight() {
                        return this.$refs.firstCard ? this.$refs.firstCard.offsetHeight + 12 : 120;
                    },
                    init() {
                        // Animasi hanya jalan jika item melebihi kapasitas layar (asumsi > 5)
                        if (this.items.length > 3) {
                            setInterval(() => {
                                // Reset ke 0 jika sudah mencapai item terakhir agar berputar terus
                                this.index = (this.index >= this.items.length - 3) ? 0 : this.index + 1;
                            }, 3000);
                        }
                    }
                }" class="relative flex-1 overflow-hidden">
                
                    <div class="flex flex-col transition-transform duration-1000 ease-in-out"
                        :style="`transform: translateY(-${index * itemHeight}px);`">
        
                        <template x-for="(siswa, i) in items" :key="'siswa-'+i">
                            <div
                                class="bg-red-500/10 border border-red-500/20 p-3 rounded-xl mb-2 flex flex-col justify-center min-h-[60px] transition-all duration-500 hover:bg-red-500/20">
                                <p class="font-bold text-[12px] text-red-200 uppercase truncate" x-text="siswa.nama"></p>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-[9px] text-red-400/80 font-black uppercase tracking-tighter"
                                        x-text="'Kelas ' + siswa.kelas"></p>
                                    <span
                                        class="text-[8px] bg-red-900/50 px-1 rounded text-red-300 border border-red-500/20">ABSEN</span>
                                </div>
                            </div>
                        </template>
        
                        <template x-if="items.length === 0">
                            <div class="h-full flex flex-col items-center justify-center py-10 opacity-40">
                                <span class="text-2xl">✅</span>
                                <p class="text-[10px] mt-2 text-white italic">Semua Hadir</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        
            {{-- CARD BAWAH: Guru Belum Masuk --}}
            <div class="bg-slate-800/60 backdrop-blur-md rounded-2xl p-4 shadow-2xl flex-1 flex flex-col overflow-hidden border border-slate-700/50"
                wire:ignore>
                <div class="flex items-center justify-between mb-3 border-b border-amber-500/30 pb-2 flex-none">
                    <h2 class="text-[11px] font-black text-amber-400 uppercase tracking-[0.2em] flex items-center gap-2">
                        <span
                            class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)] animate-pulse"></span>
                        Guru Belum Masuk
                    </h2>
                    <span class="text-[10px] font-mono text-slate-500" x-text="items.length"
                        x-data="{ items: {{ json_encode($guruBelumMasuk ?? []) }} }"></span>
                </div>
        
                <div x-data="{
                    index: 0,
                    items: {{ json_encode($guruBelumMasuk) }},
                    displayCount: 2,
                    // Fungsi untuk menghitung tinggi satu card secara otomatis
                    get itemHeight() {
                        return this.$refs.firstCard ? this.$refs.firstCard.offsetHeight + 12 : 120;
                    },
                    init() {
                        // Animasi hanya jalan jika item melebihi kapasitas layar (asumsi > 5)
                        if (this.items.length > 2) {
                            setInterval(() => {
                                // Reset ke 0 jika sudah mencapai item terakhir agar berputar terus
                                this.index = (this.index >= this.items.length - 3) ? 0 : this.index + 1;
                            }, 3000);
                        }
                    }
                }" class="relative flex-1 overflow-hidden">
                
                    <div class="flex flex-col transition-transform duration-1000 ease-in-out"
                        :style="`transform: translateY(-${index * itemHeight}px);`">
        
                        <template x-for="(guru, i) in items" :key="'guru-'+i">
                            <div
                                class="bg-amber-500/10 border border-amber-500/20 p-3 rounded-xl mb-2 flex flex-col justify-center min-h-[70px] transition-all duration-500 hover:bg-amber-500/20">
                                <div class="flex justify-between items-start">
                                    <p class="font-bold text-[12px] text-amber-200 uppercase truncate flex-1"
                                        x-text="guru.nama"></p>
                                    <span
                                        class="text-[9px] font-mono bg-amber-950/60 px-2 py-0.5 rounded text-amber-400 border border-amber-500/30 ml-2"
                                        x-text="guru.kelas"></span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1.5">
                                    <div class="w-1 h-1 bg-amber-500 rounded-full shadow-sm"></div>
                                    <p class="text-[10px] text-amber-400/80 font-medium italic truncate" x-text="guru.mapel">
                                    </p>
                                </div>
                            </div>
                        </template>
        
                        <template x-if="items.length === 0">
                            <div class="h-full flex flex-col items-center justify-center py-10 opacity-40">
                                <span class="text-2xl">👨‍🏫</span>
                                <p class="text-[10px] mt-2 text-white italic">Guru di Kelas</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- CENTER PANEL (Utama) --}}
        <div class="flex-1 flex flex-col gap-6 overflow-hidden">
        
            {{-- Sedang Berlangsung --}}
            {{-- CENTER PANEL (Utama) --}}
            <div class="flex-1 flex flex-col min-h-0">
                <div class="flex justify-between items-end mb-3 flex-none">
                    <h2 class="text-lg font-bold text-green-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        Sedang Berlangsung
                    </h2>
                    {{-- Indikator Slide --}}
                    <div x-data="{ current: 0, total: {{ ceil(count($jadwalSekarang) / 2) }} }" class="flex gap-1 mb-1">
                        <template x-for="i in total">
                            <div class="h-1 w-4 rounded-full transition-all duration-500"
                                :class="$parent.active === (i-1) ? 'bg-green-400 w-8' : 'bg-slate-700'"></div>
                        </template>
                    </div>
                </div>
            
                <div x-data="{
                        active: 0,
                        total: {{ ceil(count($jadwalSekarang) / 2) }},
                        progress: 0,
                        start() {
                            if(this.total > 1) {
                                setInterval(() => {
                                    this.progress += 1;
                                    if(this.progress >= 100) {
                                        this.active = (this.active + 1) % this.total;
                                        this.progress = 0;
                                    }
                                }, 50);
                            }
                        }
                    }" x-init="start"
                    class="relative flex-1 bg-slate-900/60 rounded-2xl border border-slate-700/50 p-4 shadow-2xl overflow-hidden">
            
                    {{-- Background Glow Decor (Dikecilkan) --}}
                    <div class="absolute -top-12 -right-12 w-40 h-40 bg-green-500/5 rounded-full blur-3xl"></div>
            
                    <div class="relative h-full w-full">
                        @foreach(collect($jadwalSekarang)->chunk(2) as $index => $group)
                            <div x-show="active === {{ $index }}" {{-- TRANSISI MASUK: Lebih lambat, datang dari bawah dengan blur pudar --}}
                                x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-1000 delay-300"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-8 blur-sm"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0 blur-0" {{-- TRANSISI KELUAR: Lebih cepat sedikit,
                                menghilang ke atas sambil memudar --}} x-transition:leave="transition cubic-bezier(0.4, 0, 0.2, 1) duration-700"
                                x-transition:leave-start="opacity-100 scale-100 blur-0"
                                x-transition:leave-end="opacity-0 scale-105 -translate-y-8 blur-sm"
                                class="absolute inset-0 grid grid-cols-2 gap-4 items-center" style="display: none;" {{-- Mencegah konten numpuk
                                saat load pertama --}}>
                                @foreach($group as $jadwal)
                                    <div
                                        class="group bg-slate-800/40 backdrop-blur-sm rounded-xl p-5 border border-slate-700/50 shadow-lg h-full flex flex-col justify-between hover:border-green-500/30 transition-all duration-500">
                                        {{-- Konten tetap sama --}}
                                        <div>
                                            <div class="flex justify-between items-start mb-3">
                                                <span
                                                    class="px-2 py-0.5 bg-green-500/10 text-green-400 text-[10px] font-bold rounded border border-green-500/20 uppercase tracking-tighter">
                                                    Live Class
                                                </span>
                                                <span class="text-slate-600 font-mono text-[10px]">#{{ rand(100, 999) }}</span>
                                            </div>

                                            <h3
                                                class="text-xl font-black text-white uppercase leading-tight mb-3 group-hover:text-green-400 transition-colors line-clamp-2">
                                                {{ $jadwal['mapel'] }}
                                            </h3>

                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded bg-slate-700/50 flex items-center justify-center text-[10px]">🏫
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-200">{{ $jadwal['kelas'] }}</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded bg-slate-700/50 flex items-center justify-center text-[10px]">👤
                                                    </div>
                                                    <p class="text-xs text-slate-400 truncate">{{ $jadwal['guru'] }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-slate-700/30 flex justify-between items-center">
                                            <span class="text-lg font-mono font-bold text-yellow-400 tracking-tighter">{{ $jadwal['jam'] }}</span>
                                            <div class="flex -space-x-1.5">
                                                <div
                                                    class="w-6 h-6 rounded-full border border-slate-800 bg-green-500 flex items-center justify-center text-[8px] font-bold text-white shadow-sm">
                                                    IN</div>
                                                <div
                                                    class="w-6 h-6 rounded-full border border-slate-800 bg-slate-600 flex items-center justify-center text-[8px] text-white">
                                                    ...</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
            
                    {{-- Progress Bar Bawah --}}
                    <div class="absolute bottom-0 left-0 h-0.5 bg-green-500/40 transition-all duration-50"
                        :style="`width: ${progress}%` shadow-sm"></div>
                </div>
            </div>
        
            {{-- Informasi --}}
            <div class="flex-none">
                <h2 class="text-xl font-bold text-yellow-400 mb-3 uppercase tracking-widest">Informasi</h2>
                <div
                    class="bg-linier-to-r from-slate-800 to-slate-800/50 p-6 rounded-2xl border border-yellow-500/20 shadow-xl flex items-center justify-between group">
                    <span
                        class="absolute top-2 left-4 text-6xl text-blue-500/10 font-serif group-hover:text-blue-500/20 transition-colors">“</span>
                    <p class="text-xl text-slate-200 font-medium relative z-10 px-8">
                        "Teknologi hanyalah alat. Dalam hal membuat anak-anak bekerja sama dan memotivasi mereka, guru adalah yang
                        paling penting."
                    </p>
                    <p class="text-xs text-blue-400 font-bold uppercase mt-3 tracking-widest">— Bill Gates</p>
            
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL (Pelajaran Hari Ini) --}}
        <div class="w-1/5 flex flex-col gap-4 h-full">
            <div class="bg-slate-800/60 backdrop-blur-md rounded-2xl p-4 shadow-xl flex-1 flex flex-col overflow-hidden border border-slate-700/50"
                wire:ignore>
        
                {{-- Header dengan Glow Indikator --}}
                <div class="flex items-center justify-between mb-4 border-b border-slate-700/50 pb-3 flex-none">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                        <h2 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Agenda Hari Ini</h2>
                    </div>
                    <span class="text-[10px] font-mono text-slate-500 uppercase">{{ count($jadwalSekarang) }} Jadwal</span>
                </div>
        
                {{-- Area Slider --}}
                <div x-data="{
                        index: 0,
                        items: {{ json_encode($jadwalSekarang) }},
                        // Fungsi untuk menghitung tinggi satu card secara otomatis
                        get itemHeight() {
                            return this.$refs.firstCard ? this.$refs.firstCard.offsetHeight + 12 : 120;
                        },
                        init() {
                            // Animasi hanya jalan jika item melebihi kapasitas layar (asumsi > 5)
                            if (this.items.length > 5) {
                                setInterval(() => {
                                    // Reset ke 0 jika sudah mencapai item terakhir agar berputar terus
                                    this.index = (this.index >= this.items.length - 4) ? 0 : this.index + 1;
                                }, 3000);
                            }
                        }
                    }" class="relative flex-1 overflow-hidden">
        
                    <div class="flex flex-col transition-transform duration-1000 ease-in-out"
                        :style="`transform: translateY(-${index * itemHeight}px);`">
        
                        <template x-for="(jadwal, i) in items" :key="i">
                            <div
                                class="card-item bg-slate-700/40 rounded-xl p-3 mb-2 border border-slate-600/30 flex flex-col justify-center min-h-[85px] transition-all duration-500 hover:bg-blue-600/20 hover:border-blue-500/30">
        
                                <div class="flex justify-between items-start">
                                    <p class="font-bold text-[13px] text-blue-300 uppercase leading-tight line-clamp-1"
                                        x-text="jadwal.mapel"></p>
                                    <span class="text-[9px] font-mono text-slate-500" x-text="jadwal.jam"></span>
                                </div>
        
                                <div class="flex items-center gap-2 mt-2">
                                    <span
                                        class="text-[9px] font-black bg-slate-800 text-blue-400 px-1.5 py-0.5 rounded border border-slate-700 uppercase"
                                        x-text="jadwal.kelas"></span>
                                    <div class="h-1 w-1 bg-slate-600 rounded-full"></div>
                                    <p class="text-[10px] text-slate-400 truncate font-medium italic" x-text="jadwal.guru"></p>
                                </div>
        
                                {{-- Status Dot --}}
                                <div class="flex justify-end mt-1">
                                    <div class="flex gap-0.5">
                                        <div class="w-1 h-1 rounded-full" :class="i < 3 ? 'bg-blue-500' : 'bg-slate-700'"></div>
                                        <div class="w-1 h-1 rounded-full bg-slate-700"></div>
                                        <div class="w-1 h-1 rounded-full bg-slate-700"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
        
                        <template x-if="items.length === 0">
                            <div class="h-full flex items-center justify-center opacity-30 italic text-xs py-10">
                                Belum ada jadwal
                            </div>
                        </template>
                    </div>
                </div>
        
                {{-- Footer Indikator --}}
                <div class="mt-4 pt-3 border-t border-slate-700/50 flex justify-center flex-none">
                    <div class="flex gap-1">
                        <template x-for="n in Math.min(items.length, 5)">
                            <div class="h-1 w-1 rounded-full bg-slate-700"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- FOOTER STATS --}}
    <div
        class="mt-6 bg-slate-800/80 p-3 rounded-xl border border-slate-700 shadow-2xl flex justify-around items-center text-sm font-bold flex-none">
        <div class="flex items-center gap-2 text-slate-300">👥 TOTAL: <span
                class="text-white text-lg font-mono">{{ $absensiHariIni['total'] }}</span></div>
        <div class="h-4 w-px bg-slate-700"></div>
        <div class="text-green-400 uppercase">Hadir: <span
                class="text-white text-lg font-mono">{{ $absensiHariIni['hadir'] }}</span></div>
        <div class="text-yellow-400 uppercase">Izin: <span
                class="text-white text-lg font-mono">{{ $absensiHariIni['izin'] }}</span></div>
        <div class="text-orange-400 uppercase">Sakit: <span
                class="text-white text-lg font-mono">{{ $absensiHariIni['sakit'] }}</span></div>
        <div class="text-red-500 uppercase">Alpha: <span
                class="text-white text-lg font-mono">{{ $absensiHariIni['alpha'] }}</span></div>
    </div>
</div>