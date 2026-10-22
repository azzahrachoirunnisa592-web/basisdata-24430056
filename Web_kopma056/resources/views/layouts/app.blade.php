<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - KOMPA Koperasi Mahasiswa (24430056)</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        kopma: {
                            50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0',
                            300: '#86efac', 400: '#4ade80', 500: '#22c55e',
                            600: '#16a34a', 700: '#15803d', 800: '#166534',
                            900: '#14532d', 950: '#052e16',
                        },
                        dark: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                            700: '#1e293b', 800: '#0f172a', 850: '#0b1120',
                            900: '#070e1a', 950: '#030712',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #22c55e; }
        /* Sidebar active glow */
        .nav-active { box-shadow: 0 0 0 1px rgba(34,197,94,.3), inset 3px 0 0 #22c55e; }
        /* Fade-in animation */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .animate-fadeInUp { animation: fadeInUp .35s ease both; }
        /* Sidebar item hover */
        .nav-item { transition: background .15s, color .15s, box-shadow .15s; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased overflow-x-hidden">

<div class="flex h-screen overflow-hidden">

    {{-- ========================================================
         SIDEBAR: Dark Background + Emerald/Green Accents
    ========================================================= --}}
    <aside id="sidebar" class="w-64 bg-dark-800 text-white flex flex-col shadow-2xl z-30 flex-shrink-0 transition-all duration-300"
           style="background: linear-gradient(180deg, #0b1120 0%, #0f172a 60%, #071012 100%);">

        {{-- Brand --}}
        <div class="px-5 py-5 border-b border-white/5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-dark-900 font-black text-lg shadow-lg shadow-emerald-500/30 flex-shrink-0">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="overflow-hidden">
                <div class="text-[17px] font-extrabold tracking-widest text-white leading-tight">KOMPA</div>
                <div class="text-[10px] text-emerald-400 font-medium tracking-wide">Koperasi Mahasiswa</div>
            </div>
            <span class="ml-auto text-[9px] bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-1.5 py-0.5 rounded-full font-bold tracking-wider">v2.0</span>
        </div>

        @php
            $currentUser = auth()->user();
            $currentRole = $currentUser ? $currentUser->role : 'admin';
        @endphp

        {{-- Navigasi Menu Berdasarkan Role --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto custom-scrollbar text-[13px]">

            @if($currentRole === 'admin')
                {{-- ========================================================
                     1. ROLE ADMIN (ANNISA - 24430056): AKSES PENUH
                ========================================================= --}}
                <div class="px-2 pt-1 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Menu Utama</div>

                <a href="{{ route('dashboard') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('dashboard') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Dashboard Utama</span>
                </a>

                <a href="{{ route('analisis.index') }}"
                   class="nav-item flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('analisis.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-brain w-4 text-center {{ request()->routeIs('analisis.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                        <span>Analisis Cerdas</span>
                    </div>
                    <span class="text-[9px] bg-amber-500/20 text-amber-400 border border-amber-500/30 px-1.5 py-0.5 rounded-full font-bold">AI</span>
                </a>

                <div class="px-2 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kasir & Penjualan</div>

                <a href="{{ route('pos.index') }}"
                   class="nav-item flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('pos.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-cash-register w-4 text-center {{ request()->routeIs('pos.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                        <span>Transaksi Kasir (POS)</span>
                    </div>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </a>

                <a href="{{ route('transaksi.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('transaksi.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('transaksi.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <div class="px-2 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Master Data</div>

                <a href="{{ route('barang.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('barang.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-4 text-center {{ request()->routeIs('barang.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Manajemen Barang</span>
                </a>

                <a href="{{ route('anggota.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('anggota.index') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-users w-4 text-center {{ request()->routeIs('anggota.index') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Data Anggota</span>
                </a>

                <div class="px-2 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pengaturan Sistem</div>

                <a href="{{ route('payment.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('payment.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-credit-card w-4 text-center {{ request()->routeIs('payment.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Payment Gateway</span>
                    <span class="ml-auto text-[9px] bg-blue-500/20 text-blue-400 border border-blue-500/30 px-1.5 py-0.5 rounded-full font-bold">Midtrans</span>
                </a>

            @elseif($currentRole === 'pengurus')
                {{-- ========================================================
                     2. ROLE PENGURUS (KELOLA KOPMA):
                     Hanya menu pengelolaan koperasi:
                     Dashboard Utama, Analisis Cerdas, Data Anggota, 
                     Manajemen Barang, dan Riwayat Transaksi.
                ========================================================= --}}
                <div class="px-2 pt-1 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pengelolaan KOPMA</div>

                <a href="{{ route('dashboard') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('dashboard') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Dashboard Utama</span>
                </a>

                <a href="{{ route('analisis.index') }}"
                   class="nav-item flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('analisis.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-brain w-4 text-center {{ request()->routeIs('analisis.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                        <span>Analisis Cerdas</span>
                    </div>
                    <span class="text-[9px] bg-amber-500/20 text-amber-400 border border-amber-500/30 px-1.5 py-0.5 rounded-full font-bold">AI</span>
                </a>

                <div class="px-2 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Master Koperasi</div>

                <a href="{{ route('anggota.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('anggota.index') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-users w-4 text-center {{ request()->routeIs('anggota.index') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Data Anggota</span>
                </a>

                <a href="{{ route('barang.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('barang.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-4 text-center {{ request()->routeIs('barang.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Manajemen Barang</span>
                </a>

                <div class="px-2 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Laporan & Audit</div>

                <a href="{{ route('transaksi.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('transaksi.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('transaksi.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>

            @elseif($currentRole === 'kasir')
                {{-- ========================================================
                     3. ROLE KASIR (INPUT TRANSAKSI):
                     Hanya modul operasional kasir:
                     Transaksi Kasir (POS) dan Riwayat Transaksi.
                ========================================================= --}}
                <div class="px-2 pt-1 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Operasional Kasir</div>

                <a href="{{ route('pos.index') }}"
                   class="nav-item flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('pos.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-cash-register w-4 text-center {{ request()->routeIs('pos.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                        <span>Transaksi Kasir (POS)</span>
                    </div>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </a>

                <a href="{{ route('transaksi.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('transaksi.*') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('transaksi.*') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>

            @elseif($currentRole === 'anggota')
                {{-- ========================================================
                     4. ROLE ANGGOTA (LIHAT SIMPANAN):
                     Hanya modul portal anggota:
                     Dashboard Anggota (simpanan pokok, wajib, sukarela, sisa pinjaman, SHU).
                ========================================================= --}}
                <div class="px-2 pt-1 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Portal Anggota</div>

                <a href="{{ route('anggota.dashboard') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold {{ request()->routeIs('anggota.dashboard') ? 'bg-emerald-600/20 text-emerald-400 nav-active' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-id-card w-4 text-center {{ request()->routeIs('anggota.dashboard') ? 'text-emerald-400' : 'text-slate-500' }}"></i>
                    <span>Dashboard Anggota</span>
                </a>

            @endif

        </nav>

        {{-- Profil Pengguna Berdasarkan Auth Session --}}
        <div class="p-4 border-t border-white/5 bg-black/20">
            <div class="flex items-center gap-3">
                @php
                    $userName = $currentUser->name ?? 'Annisa Choirunnisa';
                    $userInitial = strtoupper(substr($userName, 0, 2));
                    $roleLabel = match($currentRole) {
                        'admin'    => 'Administrator • 24430056',
                        'pengurus' => 'Pengurus KOPMA',
                        'kasir'    => 'Petugas Kasir',
                        'anggota'  => 'Anggota Koperasi',
                        default    => 'Pengguna'
                    };
                    $roleColor = match($currentRole) {
                        'admin'    => 'text-emerald-400',
                        'pengurus' => 'text-blue-400',
                        'kasir'    => 'text-amber-400',
                        'anggota'  => 'text-teal-400',
                        default    => 'text-slate-400'
                    };
                    $dotColor = match($currentRole) {
                        'admin'    => 'bg-emerald-400',
                        'pengurus' => 'bg-blue-400',
                        'kasir'    => 'bg-amber-400',
                        'anggota'  => 'bg-teal-400',
                        default    => 'bg-slate-400'
                    };
                @endphp
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-500 flex items-center justify-center font-bold text-white text-xs flex-shrink-0 shadow-lg shadow-emerald-500/20">
                    {{ $userInitial }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-[12px] text-white truncate">{{ $userName }}</div>
                    <div class="text-[10px] {{ $roleColor }} font-mono flex items-center gap-1.5 mt-0.5 truncate">
                        <span class="inline-block w-1.5 h-1.5 rounded-full {{ $dotColor }} animate-pulse flex-shrink-0"></span>
                        <span class="truncate">{{ $roleLabel }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Keluar / Ganti Akun"
                            class="w-8 h-8 rounded-lg bg-white/5 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 flex items-center justify-center transition-all duration-150">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ========================================================
         KONTEN UTAMA (Kanan)
    ========================================================= --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- HEADER --}}
        <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between shadow-sm z-20 flex-shrink-0">
            <div>
                <h1 class="text-[17px] font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                    @yield('header_title', 'Dashboard KOMPA')
                </h1>
                <p class="text-[11px] text-slate-400 mt-0.5">Sistem Informasi Koperasi Mahasiswa Terpadu &bull; kopma_24430056</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Jam --}}
                <div class="hidden md:flex items-center gap-2 text-[11px] text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                    <i class="fa-regular fa-clock text-emerald-500"></i>
                    <span id="liveClock">{{ now()->translatedFormat('l, d F Y - H:i') }} WIB</span>
                </div>

                {{-- DB Status --}}
                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>DB: <span class="font-mono text-emerald-800 font-bold">kopma_24430056</span></span>
                </div>
            </div>
        </header>

        {{-- KONTEN HALAMAN --}}
        <main class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-slate-100 animate-fadeInUp">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            @endif

            @if(isset($errors) && $errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                <div class="font-bold text-sm mb-1 flex items-center gap-2"><i class="fa-solid fa-xmark-circle"></i> Terdapat kesalahan validasi:</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

<script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        const options = { weekday:'long', day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:false };
        const formatted = now.toLocaleDateString('id-ID', options).replace(',', ',') + ' WIB';
        const el = document.getElementById('liveClock');
        if (el) el.textContent = formatted;
    }
    updateClock();
    setInterval(updateClock, 30000);
</script>
@stack('scripts')
</body>
</html>
