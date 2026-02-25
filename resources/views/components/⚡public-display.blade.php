<?php

use Livewire\Component;
use Carbon\Carbon;
use App\Models\AcademicYear;
use App\Models\TeachingSchedule;
use App\Models\Student;
use App\Models\StudentAttendance;

new class extends Component {
    public $now;
    public $jadwalHariIni = [];
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
        logger('Polling jalan');
        $this->now = now();
        $todayDate = $this->now->toDateString();
        $todayNumber = $this->now->dayOfWeekIso;

        // 1. Cari Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return;
        }

        // 2. Query Dasar (Base Query) untuk efisiensi
        $baseQuery = TeachingSchedule::query()
            ->where('academic_year_id', $activeYear->id)
            ->whereHas('day', fn($q) => $q->where('order', $todayNumber))
            ->with(['startPeriod', 'endPeriod', 'class', 'subject', 'teacher.user']);

        // 4. AMBIL JADWAL YANG SEDANG BERLANGSUNG (Jadwal Sekarang)
        $currentLesson = (clone $baseQuery)
            ->whereHas('startPeriod', fn($q) => $q->whereTime('start_time', '<=', $this->now))
            ->whereHas('endPeriod', fn($q) => $q->whereTime('end_time', '>=', $this->now))
            ->get();

        $this->jadwalSekarang = $currentLesson->map(function ($item) {
            $now = now();
            $startTime = Carbon::parse($item->startPeriod?->start_time);
            $endTime = Carbon::parse($item->endPeriod?->end_time);

            // Gunakan kolom 'date' sesuai fillable model Jurnal Anda
            $hasJournal = $item->journals()
                ->where('date', $now->toDateString())
                ->exists();

            $progres = 0;
            $isLate = false;

            // Hitung total durasi & menit yang sudah berjalan
            $totalDuration = $startTime->diffInMinutes($endTime);
            $elapsed = $startTime->diffInMinutes($now, false);

            // if ($hasJournal) {
            //     if ($elapsed > 0) {
            $progres = min(100, round(($elapsed / max($totalDuration, 1)) * 100));
            //     }
            // } else {
            //     // Jika sudah lewat 15 menit dari jam mulai tapi belum ada jurnal
            //     if ($elapsed > 15) {
            //         $isLate = true;
            //     }
            // }

            return [
                'kelas' => $item->class?->name ?? '-',
                'mapel' => $item->subject?->name ?? '-',
                'guru' => $item->teacher?->user?->name ?? '-',
                'jam' => ($item->startPeriod?->start_time ?? '') . ' - ' . ($item->endPeriod?->end_time ?? ''),
                'has_journal' => $hasJournal,
                'is_late' => $isLate,
                'progres' => $progres,
            ];
        })->toArray();

        $this->jadwalHariIni = (clone $baseQuery)->get()->map(function ($item) {
            return [
                'kelas' => $item->class?->name ?? '-', // Sesuaikan 'name' dengan kolom di tabel classes
                'mapel' => $item->subject?->name ?? '-', // Sesuaikan 'name' dengan kolom di tabel subjects
                'guru' => $item->teacher?->user?->name ?? '-', // Mengambil nama dari relasi user
                'jam' => ($item->startPeriod?->start_time ?? '') . ' - ' . ($item->endPeriod?->end_time ?? ''),
            ];
        })->toArray();

        $this->guruBelumMasuk = (clone $baseQuery)
            // 1. Filter Jadwal yang sedang berlangsung (sama seperti jadwalSekarang)
            ->whereHas('startPeriod', fn($q) => $q->whereTime('start_time', '<=', $this->now))
            ->whereHas('endPeriod', fn($q) => $q->whereTime('end_time', '>=', $this->now))

            // 2. Filter: Ambil yang TIDAK PUNYA jurnal untuk hari ini
            ->whereDoesntHave('journals', function ($query) use ($todayDate) {
                $query->whereDate('date', $todayDate);
                // pastikan nama kolom 'date' sesuai dengan di tabel teaching_journals Anda
            })

            ->with(['class', 'subject', 'teacher.user'])
            ->get()
            // 3. Mapping agar formatnya rapi untuk dashboard
            ->map(function ($item) {
                return [
                    'kelas' => $item->class->name ?? '-',
                    'mapel' => $item->subject->name ?? '-',
                    'nama' => $item->teacher->user->name ?? '-',
                    'foto' => $item->teacher->user->profile_photo_url ?? null, // Opsional untuk UI
                ];
            })->toArray();

        // 1. Ambil ID Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->first();

        if ($activeYear) {
            $this->siswaBelumPresensi = Student::query()
                // 2. Filter siswa yang terdaftar di kelas pada tahun ajaran aktif (melalui pivot student_classes)
                ->whereHas('studentClasses', function ($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id);
                })

                // 3. Filter: Siswa yang TIDAK PUNYA data absensi untuk HARI INI
                ->whereDoesntHave('attendances', function ($q) use ($todayDate) {
                    $q->whereDate('date', $todayDate); // Sesuaikan 'attendance_date' dengan kolom tabel Anda
                })

                // 4. Eager Loading untuk mengambil nama kelas dari pivot/relasi
                ->with([
                    'studentClasses' => function ($q) use ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id)->with('class');
                    }
                ])
                ->get()

                // 5. Mapping ke format array yang Anda inginkan
                ->map(function ($student) use ($activeYear) {
                    // Mengambil nama kelas dari relasi studentClasses yang aktif
                    $currentClass = $student->studentClasses->first()?->class?->name ?? '-';

                    return [
                        'nama' => $student->name, // Sesuaikan kolom nama di tabel siswa
                        'kelas' => $currentClass,
                    ];
                })
                ->toArray();
        }

        if ($activeYear) {
            // 1. Hitung total siswa yang seharusnya hadir di tahun ajaran ini
            $totalSiswa = Student::whereHas('studentClasses', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })->count();

            // 2. Ambil data absensi hari ini
            $attendanceStats = StudentAttendance::whereDate('attendance_date', $todayDate)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            // Ambil angka mentah per status (default 0 jika tidak ada)
            $hadir = $attendanceStats->get('hadir', 0);
            $terlambat = $attendanceStats->get('terlambat', 0);
            $izin = $attendanceStats->get('izin', 0);
            $sakit = $attendanceStats->get('sakit', 0);

            // Hitung SIAPA PUN yang sudah melakukan presensi (untuk mengurangi angka Alpha)
            $sudahAbsen = $hadir + $terlambat + $izin + $sakit;

            // Alpha = Total Siswa - Semua yang sudah absen
            $hitungAlpha = max(0, $totalSiswa - $sudahAbsen);

            $this->absensiHariIni = [
                'total' => $totalSiswa,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $hitungAlpha,
            ];
        }
    }
};
?>

<div wire:poll.5s="loadData" class="h-screen bg-[#0f172a] text-slate-200 p-5 flex flex-col overflow-hidden font-sans">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6 bg-slate-800/40 p-4 rounded-xl border border-white/5 flex-none">
        <div class="flex items-center gap-4">
            {{-- Logo Simple --}}
            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center shadow-inner">
                <span class="text-2xl font-bold text-white">H</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight leading-none">
                    DCC <span class="text-green-500">SMK HARAPAN</span>
                </h1>
                <div class="flex items-center gap-2 mt-1 text-slate-400 text-xs font-medium uppercase tracking-wider">
                    <span>{{ $now->locale('id')->translatedFormat('l, d F Y') }}</span>
                    <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                    <span class="text-green-500/80 animate-pulse">● Live System</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 text-right">
            <div class="flex flex-col">
                <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Waktu Server</span>
                <span class="text-xl font-mono font-bold text-white">{{ now()->format('H:i:s') }}</span>
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="flex flex-1 gap-5 overflow-hidden">

        {{-- LEFT PANEL (Warnings) --}}
        <div class="w-1/4 flex flex-col gap-5 h-full">
            {{-- Siswa Belum Presensi --}}
            <div class="flex-1 flex flex-col bg-slate-800/30 rounded-xl border border-white/5 overflow-hidden">
                <div class="p-3 bg-red-500/10 border-b border-red-500/10 flex justify-between items-center z-10 bg-slate-900">
                    <h2 class="text-xs font-bold text-red-400 uppercase tracking-wider flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        Belum Presensi
                    </h2>
                    <span
                        class="text-xs font-mono text-red-400/70 bg-red-500/5 px-2 py-0.5 rounded">{{ count($siswaBelumPresensi) }}</span>
                </div>
        
                <div class="flex-1 relative overflow-hidden group" x-data="{ 
                    scroll: 0, 
                    height: 0, 
                    contentHeight: 0,
                    init() {
                        $nextTick(() => {
                            this.contentHeight = this.$refs.container.scrollHeight;
                            this.height = this.$el.clientHeight;
                            if(this.contentHeight > this.height) {
                                setInterval(() => {
                                    if (this.scroll >= this.contentHeight / 2) {
                                        this.scroll = 0; // Seamless loop reset
                                    } else {
                                        this.scroll += 0.5; // Kecepatan scroll
                                    }
                                }, 30);
                            }
                        });
                    }
                }">
                
                    <div x-ref="container" class="p-3 space-y-2" :style="`transform: translateY(-${scroll}px)`">
                
                        {{-- Data Utama --}}
                        @forelse($siswaBelumPresensi as $siswa)
                            <div class="p-2.5 bg-slate-800/50 rounded-lg border border-white/5 transition-all hover:border-red-500/30">
                                <p class="text-[13px] font-semibold text-slate-200 truncate uppercase">{{ $siswa['nama'] }}</p>
                                <p class="text-[9px] text-slate-500 font-bold mt-0.5 uppercase tracking-tighter">
                                    Kelas {{ $siswa['kelas'] }}
                                </p>
                            </div>
                        @empty
                            <div class="py-10 flex flex-col items-center justify-center opacity-30">
                                <span class="text-2xl">✓</span>
                                <p class="text-[10px] mt-1">Nihil</p>
                            </div>
                        @endforelse
                
                        {{-- Duplicate data (Sama persis dengan data utama tanpa opacity-50) --}}
                        @if(count($siswaBelumPresensi) > 4)
                            @foreach($siswaBelumPresensi as $siswa)
                                <div class="p-2.5 bg-slate-800/50 rounded-lg border border-white/5 transition-all hover:border-red-500/30">
                                    <p class="text-[13px] font-semibold text-slate-200 truncate uppercase">{{ $siswa['nama'] }}</p>
                                    <p class="text-[9px] text-slate-500 font-bold mt-0.5 uppercase tracking-tighter">
                                        Kelas {{ $siswa['kelas'] }}
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                
                    {{-- Fade Effect --}}
                    <div
                        class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-[#0f172a] to-transparent pointer-events-none">
                    </div>
                </div>
            </div>
        
            {{-- Guru Belum Masuk --}}
            <div class="flex-1 flex flex-col bg-slate-800/30 rounded-xl border border-white/5 overflow-hidden">
                <div
                    class="p-3 bg-amber-500/10 border-b border-amber-500/10 flex justify-between items-center z-10 bg-slate-900">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        Guru Belum Masuk
                    </h2>
                    <span
                        class="text-xs font-mono text-amber-400/70 bg-amber-500/5 px-2 py-0.5 rounded">{{ count($guruBelumMasuk) }}</span>
                </div>
        
                <div class="flex-1 relative overflow-hidden" x-data="{ 
                    scroll: 0, 
                    contentHeight: 0,
                    init() {
                        $nextTick(() => {
                            this.contentHeight = this.$refs.container.scrollHeight;
                            if(this.contentHeight > this.$el.clientHeight) {
                                setInterval(() => {
                                    this.scroll = (this.scroll >= this.contentHeight / 2) ? 0 : this.scroll + 0.5;
                                }, 35);
                            }
                        });
                    }
                }">
                
                    <div x-ref="container" class="p-3 space-y-2" :style="`transform: translateY(-${scroll}px)`">
                        {{-- 1. DATA UTAMA --}}
                        @forelse($guruBelumMasuk as $guru)
                            <div class="p-2.5 bg-slate-800/50 rounded-lg border border-white/5">
                                <div class="flex justify-between items-start gap-2">
                                    <p class="text-[13px] font-semibold text-slate-200 truncate uppercase leading-tight">
                                        {{ $guru['nama'] }}
                                    </p>
                                    <span
                                        class="text-[8px] bg-amber-500/20 text-amber-400 px-1.5 py-0.5 rounded font-bold uppercase border border-amber-500/20">
                                        {{ $guru['kelas'] }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 italic truncate opacity-80">{{ $guru['mapel'] }}</p>
                            </div>
                        @empty
                            <div class="py-10 flex flex-col items-center justify-center opacity-30">
                                <span class="text-2xl">👨‍🏫</span>
                                <p class="text-[10px] mt-1 uppercase">Semua di kelas</p>
                            </div>
                        @endforelse
                
                        {{-- 2. LOOP DUPLICATE (Sama persis dengan di atas agar transisi mulus) --}}
                        @if(count($guruBelumMasuk) > 4)
                            @foreach($guruBelumMasuk as $guru)
                                <div class="p-2.5 bg-slate-800/50 rounded-lg border border-white/5">
                                    <div class="flex justify-between items-start gap-2">
                                        <p class="text-[13px] font-semibold text-slate-200 truncate uppercase leading-tight">
                                            {{ $guru['nama'] }}
                                        </p>
                                        <span
                                            class="text-[8px] bg-amber-500/20 text-amber-400 px-1.5 py-0.5 rounded font-bold uppercase border border-amber-500/20">
                                            {{ $guru['kelas'] }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-1 italic truncate opacity-80">{{ $guru['mapel'] }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                
                    {{-- Overlay Fade agar teks tidak terpotong tajam di bawah --}}
                    <div
                        class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-[#0f172a] to-transparent pointer-events-none">
                    </div>
                </div>
            </div>
        </div>

        {{-- CENTER PANEL (Utama) --}}
        <div class="flex-1 flex flex-col gap-5 overflow-hidden">
            <div class="flex-1 flex flex-col min-h-0 bg-slate-800/20 rounded-2xl border border-white/5 p-6 relative overflow-hidden"
                x-data="{ 
                    activePage: 0, 
                    totalPage: {{ ceil(count($jadwalSekarang) / 4) }}, {{-- Menampilkan 4 data per slide --}}
                    init() {
                        if(this.totalPage > 1) {
                            setInterval(() => {
                                this.activePage = (this.activePage + 1) % this.totalPage;
                            }, 5000); {{-- Ganti slide setiap 5 detik --}}
                        }
                    }
                }">
            
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-white flex items-center gap-3">
                        
                        <span class="relative flex h-2.5 w-2.5">
                        
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        
                        </span>
                        SEDANG BERLANGSUNG
                    </h2>
            
                    {{-- Indikator Halaman (Dots) --}}
                    <div class="flex gap-2" x-show="totalPage > 1">
                        <template x-for="p in totalPage" :key="p">
                            <div class="h-1.5 transition-all duration-500 rounded-full"
                                :class="activePage === (p-1) ? 'w-6 bg-green-500' : 'w-2 bg-slate-700'"></div>
                        </template>
                    </div>
                </div>
            
                <div class="flex-1 relative">
                    @if(count($jadwalSekarang) > 0)
                        @foreach(collect($jadwalSekarang)->chunk(4) as $pageIndex => $chunk)
                            <div x-show="activePage === {{ $pageIndex }}" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-105"
                                class="grid grid-cols-2 grid-rows-2 gap-5 h-full">

                                @foreach($chunk as $jadwal)
                                    <div
                                        class="relative overflow-hidden group bg-slate-800/40 rounded-2xl border border-white/10 shadow-xl flex flex-col p-5 transition-all duration-300 hover:border-green-500/50 hover:bg-slate-800/60">

                                        {{-- Decorative background accent --}}
                                        <div
                                            class="absolute -right-4 -top-4 w-20 h-20 bg-green-500/5 rounded-full blur-2xl group-hover:bg-green-500/10 transition-colors">
                                        </div>

                                        <div class="relative flex-1">
                                            {{-- Top Header: Tag & Jam --}}
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="relative flex h-2 w-2">
                                                        <span
                                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                    </span>
                                                    {{-- <span class="text-[10px] font-bold text-green-400 uppercase tracking-[0.15em]">Sesi Aktif</span> --}}
                                                    @if($jadwal['has_journal'])
                                                            {{-- Status Jika Sudah Absen --}}
                                                            {{-- <span class="relative flex h-1.5 w-1.5">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                                            </span> --}}
                                                        <span class="text-[10px] font-bold text-green-400 uppercase tracking-[0.15em]">Proses Mengajar</span>
                                                    @elseif($jadwal['is_late'])
                                                        {{-- Status Jika Guru Telat Mengisi Jurnal --}}
                                                        <span
                                                            class="text-[9px] font-black text-red-500 bg-red-500/10 px-2 py-0.5 rounded border border-red-500/20 animate-pulse">
                                                            ⚠️ GURU BELUM MENGISI JURNAL
                                                        </span>
                                                    @else
                                                        {{-- Status Menunggu (Baru mulai < 15 menit) --}} <span
                                                            class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter italic">Menunggu Guru...</span>
                                                    @endif
                                                </div>
                                                <div class="bg-slate-900/50 px-3 py-1 rounded-lg border border-white/5">
                                                    <span class="text-sm font-mono font-bold text-yellow-400">{{ $jadwal['jam'] }}</span>
                                                </div>
                                            </div>

                                            {{-- Main Content: Mata Pelajaran --}}
                                            <h3
                                                class="text-xl font-black text-white leading-tight uppercase tracking-tight mb-4 group-hover:text-green-400 transition-colors line-clamp-2">
                                                {{ $jadwal['mapel'] }}
                                            </h3>

                                            {{-- Info Row: Kelas & Guru --}}
                                            <div class="grid grid-cols-2 gap-3 mt-auto">
                                                <div class="flex flex-col p-2 bg-white/5 rounded-xl border border-white/5">
                                                    <span class="text-[9px] text-slate-500 uppercase font-bold tracking-wider mb-1">Ruang /
                                                        Kelas</span>
                                                    <span class="text-sm font-bold text-slate-200 truncate">{{ $jadwal['kelas'] }}</span>
                                                </div>
                                                <div class="flex flex-col p-2 bg-white/5 rounded-xl border border-white/5">
                                                    <span class="text-[9px] text-slate-500 uppercase font-bold tracking-wider mb-1">Tenaga
                                                        Pengajar</span>
                                                    <span class="text-sm font-semibold text-slate-400 truncate">{{ $jadwal['guru'] }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Footer: Progress Bar --}}
                                        <div class="mt-5">
                                            <div class="flex justify-between items-center mb-1.5">
                                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Waktu Berjalan</span>

                                                <div class="flex items-center gap-1.5">
                                                    <span class="relative flex h-1.5 w-1.5">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                                    </span>
                                                    <span class="text-[9px] font-black text-green-500 italic uppercase">On Progress
                                                        ({{ $jadwal['progres'] }}%)</span>
                                                    {{--  --}}
                                                </div>
                                            </div>

                                            {{-- Progress Bar Container --}}
                                            <div class="w-full h-2 bg-slate-900/50 rounded-full overflow-hidden p-[2px] border border-white/5">
                                                @if($jadwal['progres'])
                                                    <div class="h-full bg-gradient-to-r from-green-600 via-green-400 to-emerald-400 rounded-full shadow-[0_0_10px_rgba(52,211,153,0.3)] transition-all duration-1000"
                                                        style="width: {{ $jadwal['progres'] }}%">
                                                    </div>
                                                @else
                                                    {{-- Progress bar statis/bergaris jika belum ada jurnal --}}
                                                    <div class="h-full w-full bg-slate-800 flex items-center justify-center">
                                                        <div class="w-full h-full opacity-20"
                                                            style="background-image: linear-gradient(45deg, #475569 25%, transparent 25%, transparent 50%, #475569 50%, #475569 75%, transparent 75%, transparent); background-size: 10px 10px;">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        {{-- Empty State --}}
                        <div class="h-full flex flex-col items-center justify-center text-slate-500 space-y-4">
                            <div class="w-20 h-20 bg-slate-800/50 rounded-full flex items-center justify-center border border-white/5">
                                <span class="text-4xl">☕</span>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-slate-300 uppercase tracking-widest">Istirahat</p>
                                <p class="text-sm text-slate-500 font-medium">Tidak ada jadwal pelajaran yang aktif</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quote / Info --}}
            <div class="bg-blue-600/10 border border-blue-500/20 p-5 rounded-xl">
                <p class="text-slate-300 italic text-sm leading-relaxed">
                    "Teknologi hanyalah alat. Dalam hal membuat anak-anak bekerja sama dan memotivasi mereka, guru adalah yang paling penting."
                </p>
                <p class="text-[10px] text-blue-400 font-bold uppercase mt-2 tracking-widest">— Bill Gates</p>
            </div>
        </div>

        {{-- RIGHT PANEL (Agenda) --}}
        <div class="w-1/4 h-full">
            <div class="bg-slate-800/30 rounded-xl border border-white/5 h-full flex flex-col overflow-hidden" x-data="{ 
                    scroll: 0,
                    itemHeight: 0,
                    totalItems: {{ count($jadwalHariIni) }},
                    init() {
                        if(this.totalItems > 5) { // Hanya aktif jika item melebihi kapasitas layar
                            setInterval(() => {
                                this.itemHeight = this.$refs.firstItem?.offsetHeight + 12 || 100;
                                // Reset ke atas jika sudah mencapai akhir
                                if (this.scroll >= (this.itemHeight * (this.totalItems - 4))) {
                                    this.scroll = 0;
                                } else {
                                    this.scroll += this.itemHeight;
                                }
                            }, 4000); // Berpindah setiap 4 detik
                        }
                    }
                }">
        
                {{-- Header Tetap Diam --}}
                <div
                    class="p-4 border-b border-white/5 flex justify-between items-center bg-slate-800/50 relative z-10 backdrop-blur-sm">
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                        Agenda Hari Ini
                    </h2>
                    <span
                        class="text-[10px] px-2 py-0.5 bg-blue-500/10 border border-blue-500/20 rounded text-blue-400 font-mono">
                        {{ count($jadwalHariIni) }} JADWAL
                    </span>
                </div>
        
                {{-- Area Konten Bergerak --}}
                <div class="flex-1 relative overflow-hidden p-4">
                    <div class="flex flex-col transition-all duration-700 ease-in-out space-y-3"
                        :style="`transform: translateY(-${scroll}px)`">
        
                        @forelse($jadwalHariIni as $index => $jadwal)
                            <div @if($index === 0) x-ref="firstItem" @endif
                                class="group p-3.5 bg-slate-800/40 rounded-xl border border-white/5 hover:border-blue-500/30 transition-all shadow-sm">

                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter mb-1">Jam
                                            Pelajaran</span>
                                        <span class="text-sm font-mono font-bold text-blue-400">{{ $jadwal['jam'] }}</span>
                                    </div>
                                    <span
                                        class="text-[9px] font-black text-white uppercase bg-slate-700/50 border border-white/10 px-2 py-1 rounded shadow-inner">
                                        {{ $jadwal['kelas'] }}
                                    </span>
                                </div>

                                <div class="h-px w-full bg-gradient-to-r from-white/10 to-transparent mb-3"></div>

                                <p
                                    class="text-[13px] font-bold text-slate-100 uppercase leading-tight group-hover:text-blue-300 transition-colors line-clamp-2">
                                    {{ $jadwal['mapel'] }}
                                </p>

                                <div class="mt-2 flex items-center gap-2 text-slate-400 italic">
                                    <span class="text-[10px] opacity-70">👤 {{ $jadwal['guru'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center py-20 opacity-20">
                                <span class="text-4xl">🗓️</span>
                                <p class="text-xs mt-2 uppercase font-bold tracking-widest">Belum Ada Agenda</p>
                            </div>
                        @endforelse
                    </div>
        
                    {{-- Overlay Gradasi agar tidak terpotong kasar --}}
                    <div
                        class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-slate-900 to-transparent pointer-events-none">
                    </div>
                </div>
        
                {{-- Footer Kecil untuk indikator status --}}
                <div class="p-2 bg-slate-900/50 flex justify-center border-t border-white/5">
                    <div class="flex gap-1.5" x-show="totalItems > 4">
                        <template x-for="i in Math.min(5, totalItems)" :key="i">
                            <div class="w-1 h-1 rounded-full bg-slate-700"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER STATS --}}
    <div class="mt-5 grid grid-cols-5 gap-4 flex-none">
        {{-- TOTAL --}}
        <div class="bg-slate-800/80 p-3 rounded-xl border border-white/5 flex flex-col items-center">
            <span class="text-[10px] text-slate-500 uppercase font-bold mb-1">Total Siswa</span>
            <span class="text-xl font-mono font-bold text-white">{{ $absensiHariIni['total'] }}</span>
        </div>
    
        {{-- HADIR (Hadir murni + Terlambat) --}}
        <div class="bg-green-500/10 p-3 rounded-xl border border-green-500/20 flex flex-col items-center">
            <span class="text-[10px] text-green-500 uppercase font-bold mb-1">Hadir</span>
            <span class="text-xl font-mono font-bold text-green-400">
                {{ $absensiHariIni['hadir'] + $absensiHariIni['terlambat'] }}
            </span>
        </div>
    
        {{-- IZIN/SAKIT --}}
        <div class="bg-amber-500/10 p-3 rounded-xl border border-amber-500/20 flex flex-col items-center">
            <span class="text-[10px] text-amber-500 uppercase font-bold mb-1">Izin/Sakit</span>
            <span class="text-xl font-mono font-bold text-amber-400">
                {{ $absensiHariIni['izin'] + $absensiHariIni['sakit'] }}
            </span>
        </div>
    
        {{-- ALPHA (Hasil pengurangan bersih) --}}
        <div class="bg-red-500/10 p-3 rounded-xl border border-red-500/20 flex flex-col items-center">
            <span class="text-[10px] text-red-500 uppercase font-bold mb-1">Alpha</span>
            <span class="text-xl font-mono font-bold text-red-400">{{ $absensiHariIni['alpha'] }}</span>
        </div>
    
        {{-- PERSENTASE --}}
        <div class="bg-blue-500/10 p-3 rounded-xl border border-blue-500/20 flex flex-col items-center">
            <span class="text-[10px] text-blue-500 uppercase font-bold mb-1">Persentase</span>
            <span class="text-xl font-mono font-bold text-blue-400">
                {{-- Persentase = (Hadir + Terlambat) / Total --}}
                {{ number_format((($absensiHariIni['hadir'] + $absensiHariIni['terlambat']) / max($absensiHariIni['total'], 1)) * 100, 1) }}%
            </span>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    </style>
</div>