<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - KOMPA Koperasi Mahasiswa (24430056)</title>
    
    <!-- Google Fonts & Tailwind CSS CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        kopma: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR NAVIGASI VERTIKAL BERWARNA HIJAU GELAP KHAS KOPERASI -->
        <aside class="w-72 bg-gradient-to-b from-kopma-950 via-kopma-900 to-emerald-950 text-white flex flex-col shadow-2xl z-30 flex-shrink-0 transition-all duration-300">
            <!-- Brand & Logo -->
            <div class="p-6 border-b border-emerald-800/60 flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-300 flex items-center justify-center text-kopma-950 font-black text-2xl shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <div class="text-xl font-extrabold tracking-wider text-white flex items-center gap-2">
                        KOMPA
                        <span class="text-[10px] uppercase font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 px-2 py-0.5 rounded-full">v1.0</span>
                    </div>
                    <div class="text-xs text-emerald-300/80 font-medium">Koperasi Mahasiswa</div>
                </div>
            </div>

            <!-- Navigasi Menu -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar text-sm">
                <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-emerald-400/60 uppercase tracking-wider">
                    Menu Utama
                </div>

                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-emerald-100/80 hover:bg-emerald-800/50 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pos.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('pos.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-emerald-100/80 hover:bg-emerald-800/50 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-cash-register w-5 text-center {{ request()->routeIs('pos.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                        <span>Transaksi Kasir (POS)</span>
                    </div>
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                    </span>
                </a>

                <a href="{{ route('transaksi.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('transaksi.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-emerald-100/80 hover:bg-emerald-800/50 hover:text-white' }}">
                    <i class="fa-solid fa-receipt w-5 text-center {{ request()->routeIs('transaksi.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <div class="px-3 pt-5 pb-1 text-[11px] font-bold text-emerald-400/60 uppercase tracking-wider">
                    Master Data
                </div>

                <a href="{{ route('barang.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('barang.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-emerald-100/80 hover:bg-emerald-800/50 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center {{ request()->routeIs('barang.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Manajemen Barang</span>
                </a>

                <a href="{{ route('anggota.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('anggota.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-emerald-100/80 hover:bg-emerald-800/50 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center {{ request()->routeIs('anggota.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Data Anggota</span>
                </a>
            </nav>

            <!-- Profil Pengguna / Identitas Mahasiswa di Bagian Bawah -->
            <div class="p-4 border-t border-emerald-800/60 bg-emerald-950/60">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-700/60 border border-emerald-500/30 flex items-center justify-center font-bold text-emerald-200">
                        AN
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-sm text-white truncate">Annisa Choirunnisa</div>
                        <div class="text-[11px] text-emerald-300 font-mono flex items-center gap-1.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            NIM: 24430056
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA KANAN -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <!-- HEADER ATAS -->
            <header class="bg-white border-b border-slate-200/80 px-8 py-3.5 flex items-center justify-between shadow-sm z-20 flex-shrink-0">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        @yield('header_title', 'Ringkasan Sistem KOMPA')
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">Sistem Informasi Koperasi Mahasiswa Terpadu & Kasir POS</p>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Status Jam & Tanggal -->
                    <div class="hidden md:flex items-center gap-2 text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                        <i class="fa-regular fa-clock text-emerald-600"></i>
                        <span id="liveClock">{{ now()->translatedFormat('l, d F Y - H:i') }} WIB</span>
                    </div>

                    <!-- BANNER / KOTAK PEMBERITAHUAN STATUS TERHUBUNG KE DATABASE MYSQL -->
                    <div class="flex items-center gap-2 text-xs font-semibold text-emerald-800 bg-emerald-50/90 px-3.5 py-1.5 rounded-full border border-emerald-300 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span>Terhubung ke Database MySQL <span class="font-mono text-emerald-900 font-bold bg-emerald-200/50 px-1.5 py-0.5 rounded">kopma_24430056</span></span>
                    </div>
                </div>
            </header>

            <!-- ISI HALAMAN / KONTEN UTAMA SCROLLABLE -->
            <main class="flex-1 overflow-y-auto custom-scrollbar p-6 lg:p-8 bg-slate-50/80">
                
                <!-- Notifikasi Flash Message -->
                @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 flex items-start gap-3 shadow-sm animate-fade-in">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 flex items-start gap-3 shadow-sm animate-fade-in">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                @if(isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 shadow-sm">
                    <div class="font-bold text-sm mb-1">Terdapat kesalahan validasi:</div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
