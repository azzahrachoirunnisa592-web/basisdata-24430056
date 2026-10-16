<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KOMPA Koperasi Mahasiswa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        kopma: { 500:'#22c55e', 600:'#16a34a', 700:'#15803d', 800:'#166534', 900:'#14532d', 950:'#052e16' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family:'Plus Jakarta Sans', sans-serif; }
        @keyframes floatUp { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-14px); } }
        .float-anim { animation: floatUp 4s ease-in-out infinite; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(10px);} to{opacity:1;transform:none;} }
        .fade-in { animation: fadeIn .5s ease both; }
        .left-pattern {
            background-image: radial-gradient(circle at 20% 20%, rgba(34,197,94,.18) 0%, transparent 55%),
                              radial-gradient(circle at 80% 80%, rgba(16,163,74,.15) 0%, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen flex items-stretch bg-slate-900">

<div class="flex w-full min-h-screen">

    {{-- ============================
         SISI KIRI — Informasi KOMPA (Hijau)
    ============================= --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden left-pattern"
         style="background: linear-gradient(135deg, #052e16 0%, #0f4a26 40%, #0d3b1e 100%);">

        {{-- Dekorasi lingkaran --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-emerald-500/5 border border-emerald-500/10"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 rounded-full bg-teal-500/5 border border-teal-500/10 translate-x-20 translate-y-20"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[480px] h-[480px] rounded-full border border-emerald-500/5"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-white font-black text-xl shadow-xl shadow-emerald-500/30">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <div class="text-xl font-extrabold tracking-widest text-white">KOMPA</div>
                    <div class="text-[10px] text-emerald-400 font-medium tracking-wide">Koperasi Mahasiswa</div>
                </div>
            </div>

            {{-- Hero Text --}}
            <div class="float-anim">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-400/20 text-emerald-300 text-xs font-semibold mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Aktif & Real-time
                </div>
                <h1 class="text-4xl font-extrabold text-white leading-tight mb-4">
                    Selamat Datang di<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">
                        Sistem KOMPA
                    </span>
                </h1>
                <p class="text-emerald-100/70 text-sm leading-relaxed max-w-sm">
                    Platform manajemen koperasi mahasiswa terpadu — kasir POS, inventaris barang, data anggota, simpanan & pinjaman, SHU, dan analisis cerdas.
                </p>
            </div>

            {{-- Fitur Unggulan --}}
            <div class="space-y-3">
                @php
                    $features = [
                        ['icon'=>'fa-cash-register',  'text'=>'Kasir POS real-time dengan struk digital'],
                        ['icon'=>'fa-brain',           'text'=>'Analisis cerdas & ramalan omzet otomatis'],
                        ['icon'=>'fa-piggy-bank',      'text'=>'Kelola simpanan, pinjaman & SHU anggota'],
                        ['icon'=>'fa-credit-card',     'text'=>'Integrasi Payment Gateway Midtrans'],
                    ];
                @endphp
                @foreach($features as $f)
                <div class="flex items-center gap-3 text-sm text-emerald-100/80">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 flex-shrink-0 text-xs">
                        <i class="fa-solid {{ $f['icon'] }}"></i>
                    </div>
                    <span>{{ $f['text'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Footer Info --}}
            <div class="text-[11px] text-emerald-500/60 font-mono">
                Annisa Choirunnisa &bull; NIM: 24430056 &bull; kopma_24430056
            </div>
        </div>
    </div>

    {{-- ============================
         SISI KANAN — Form Login
    ============================= --}}
    <div class="flex-1 flex items-center justify-center bg-white p-8 fade-in">
        <div class="w-full max-w-md">

            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-white font-black text-lg">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div class="text-xl font-extrabold tracking-widest text-slate-800">KOMPA</div>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-800 mb-1">Masuk ke Sistem</h2>
            <p class="text-slate-400 text-sm mb-8">Gunakan akun KOMPA atau tombol demo di bawah.</p>

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Form Login --}}
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-envelope text-sm"></i></span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="email@kopma.ac.id"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock text-sm"></i></span>
                        <input type="password" id="password" name="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-12 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all">
                        <button type="button" onclick="togglePwd()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-500 focus:ring-emerald-400">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" id="btn-login"
                        class="w-full py-3 px-6 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    Masuk ke KOMPA
                </button>
            </form>

            {{-- Divider --}}
            <div class="my-6 flex items-center gap-3">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-xs text-slate-400 font-medium">atau akun demo</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            {{-- Quick Login Buttons --}}
            <div class="grid grid-cols-2 gap-3">
                @php
                    $demoAccounts = [
                        ['role'=>'admin',    'label'=>'Admin',    'icon'=>'fa-shield-halved',  'color'=>'violet', 'sub'=>'Annisa (24430056)'],
                        ['role'=>'pengurus', 'label'=>'Pengurus', 'icon'=>'fa-user-tie',        'color'=>'blue',   'sub'=>'Kelola KOPMA'],
                        ['role'=>'kasir',    'label'=>'Kasir',    'icon'=>'fa-cash-register',   'color'=>'emerald','sub'=>'Input Transaksi'],
                        ['role'=>'anggota',  'label'=>'Anggota',  'icon'=>'fa-id-card',         'color'=>'amber',  'sub'=>'Lihat Simpanan'],
                    ];
                    $colorMap = [
                        'violet'  => 'border-violet-200  bg-violet-50  hover:bg-violet-100  text-violet-700',
                        'blue'    => 'border-blue-200    bg-blue-50    hover:bg-blue-100    text-blue-700',
                        'emerald' => 'border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700',
                        'amber'   => 'border-amber-200   bg-amber-50   hover:bg-amber-100   text-amber-700',
                    ];
                @endphp

                @foreach($demoAccounts as $acc)
                <form action="{{ route('login.quick') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" value="{{ $acc['role'] }}">
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-3 rounded-xl border {{ $colorMap[$acc['color']] }} transition-all duration-150 text-left">
                        <div class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="fa-solid {{ $acc['icon'] }} text-sm"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-xs font-bold leading-tight">{{ $acc['label'] }}</div>
                            <div class="text-[10px] opacity-70 truncate">{{ $acc['sub'] }}</div>
                        </div>
                    </button>
                </form>
                @endforeach
            </div>

            {{-- Info --}}
            <p class="mt-6 text-center text-[11px] text-slate-400">
                Sistem Koperasi Mahasiswa KOMPA &bull; Database: <span class="font-mono text-emerald-600">kopma_24430056</span>
            </p>
        </div>
    </div>
</div>

<script>
    function togglePwd() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>
