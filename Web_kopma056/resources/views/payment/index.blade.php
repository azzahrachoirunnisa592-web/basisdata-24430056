@extends('layouts.app')

@section('title', 'Pengaturan Payment Gateway — KOMPA')
@section('header_title', 'Pengaturan Payment Gateway')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- ===== ALERT NOTIFIKASI ===== --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
            <div>
                <div class="font-bold text-sm">Berhasil Disimpan</div>
                <div class="text-xs text-emerald-600">{{ session('success') }}</div>
            </div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-700 text-sm">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    {{-- ===== HEADER BANNER UTAMA ===== --}}
    <div class="relative overflow-hidden rounded-3xl p-7 text-white shadow-xl"
         style="background: linear-gradient(135deg, #091e14 0%, #064e3b 50%, #047857 100%);">
        <div class="absolute right-4 -bottom-6 opacity-10 text-[200px] pointer-events-none">
            <i class="fa-brands fa-cc-mastercard"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-emerald-200 text-xs font-bold tracking-wide">
                    <i class="fa-solid fa-shield-halved"></i> Midtrans Payment Gateway Integration
                </div>
                <h2 class="text-2xl md:text-3xl font-black tracking-tight">Pengaturan Pembayaran Digital Kasir</h2>
                <p class="text-emerald-100 text-xs md:text-sm max-w-2xl leading-relaxed">
                    Kelola integrasi gerbang pembayaran Midtrans untuk menerima transaksi non-tunai (QRIS, GoPay, ShopeePay, Transfer Bank VA) secara otomatis, aman, dan instan di kasir KOPMA.
                </p>
            </div>
            
            <div class="flex flex-row md:flex-col gap-3 flex-shrink-0">
                <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center min-w-[140px]">
                    <div class="text-[10px] font-bold text-emerald-200 uppercase tracking-widest">Status Gateway</div>
                    <div class="flex items-center justify-center gap-2 mt-1">
                        <span class="w-2.5 h-2.5 rounded-full {{ !empty($config['server_key']) ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
                        <span class="text-sm font-extrabold text-white">
                            {{ !empty($config['server_key']) ? 'Terhubung' : 'Perlu Setup' }}
                        </span>
                    </div>
                </div>
                <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-4 text-center min-w-[140px]">
                    <div class="text-[10px] font-bold text-emerald-200 uppercase tracking-widest">Lingkungan</div>
                    <div class="text-sm font-extrabold mt-1 {{ $config['environment'] === 'production' ? 'text-emerald-300' : 'text-amber-300' }}">
                        {{ strtoupper($config['environment']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== GRID UTAMA: FORM KONFIGURASI & PETUNJUK ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- KOLOM KIRI: FORM PENGATURAN (8 KOLOM) --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                <div class="px-7 py-5 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm">Formulir Kredensial Midtrans</h3>
                            <p class="text-[11px] text-slate-400">Pengaturan ini akan diterapkan ke sistem kasir POS & pembayaran online</p>
                        </div>
                    </div>
                    <button type="button" onclick="testMidtransConnection()" id="btnTestConn"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white text-emerald-700 border border-emerald-300 hover:bg-emerald-50 transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-plug-circle-bolt"></i>
                        <span>Uji Koneksi API</span>
                    </button>
                </div>

                {{-- Status Hasil Uji Koneksi Live --}}
                <div id="testResultBox" class="hidden mx-7 mt-5 p-4 rounded-2xl border text-xs leading-relaxed transition-all"></div>

                <form action="{{ route('payment.save') }}" method="POST" class="p-7 space-y-6">
                    @csrf

                    {{-- 1. Pilihan Mode Lingkungan --}}
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">
                            1. Mode Lingkungan (Environment)
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            {{-- Sandbox Card --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="environment" value="sandbox" id="env_sandbox"
                                       {{ $config['environment'] === 'sandbox' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="border-2 rounded-2xl p-4 transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50/60 border-slate-200 hover:border-slate-300">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-base flex-shrink-0">
                                            <i class="fa-solid fa-flask"></i>
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-sm text-slate-800 flex items-center gap-1.5">
                                                Sandbox (Uji Coba)
                                                <span class="text-[10px] bg-amber-200/80 text-amber-900 font-bold px-1.5 py-0.5 rounded">Rekomendasi</span>
                                            </div>
                                            <div class="text-[11px] text-slate-500 mt-0.5">Uji coba simulasi transaksi tanpa menggunakan uang sungguhan.</div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            {{-- Production Card --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="environment" value="production" id="env_production"
                                       {{ $config['environment'] === 'production' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="border-2 rounded-2xl p-4 transition-all peer-checked:border-emerald-600 peer-checked:bg-emerald-50/60 border-slate-200 hover:border-slate-300">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base flex-shrink-0">
                                            <i class="fa-solid fa-rocket"></i>
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-sm text-slate-800">
                                                Production (Live)
                                            </div>
                                            <div class="text-[11px] text-slate-500 mt-0.5">Menerima pembayaran nyata dari pelanggan dan anggota KOPMA.</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. Kunci Akses (Access Keys) --}}
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                            2. Kunci Akses API (Access Keys)
                        </label>

                        {{-- Server Key --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="server_key" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-server text-emerald-600"></i> Server Key (Wajib)
                                </label>
                                <span class="text-[10px] font-semibold text-rose-500 bg-rose-50 px-2 py-0.5 rounded">Rahasia Backend</span>
                            </div>
                            <div class="relative">
                                <input type="password" id="server_key" name="server_key" required
                                       value="{{ $config['server_key'] }}"
                                       placeholder="SB-Mid-server-xxxxxxxxxxxx / Mid-server-xxxxxxxxxxxx"
                                       class="w-full pl-4 pr-24 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-xs font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                                <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                                    <button type="button" onclick="toggleKeyVisibility('server_key')" title="Lihat/Sembunyikan"
                                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-eye" id="icon_server_key"></i>
                                    </button>
                                    <button type="button" onclick="copyToClipboard(document.getElementById('server_key').value)" title="Salin"
                                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center text-xs">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                Digunakan oleh backend KOMPA untuk membuat transaksi & verifikasi notifikasi pembayaran.
                            </p>
                        </div>

                        {{-- Client Key --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="client_key" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-key text-blue-600"></i> Client Key (Wajib)
                                </label>
                                <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Frontend / Snap</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="client_key" name="client_key" required
                                       value="{{ $config['client_key'] }}"
                                       placeholder="SB-Mid-client-xxxxxxxxxxxx / Mid-client-xxxxxxxxxxxx"
                                       class="w-full pl-4 pr-12 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-slate-900 text-xs font-mono font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                                <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                    <button type="button" onclick="copyToClipboard(document.getElementById('client_key').value)" title="Salin"
                                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 flex items-center justify-center text-xs">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">
                                Digunakan pada antarmuka kasir & Snap.js untuk memunculkan QRIS / pop-up pembayaran Midtrans.
                            </p>
                        </div>

                        {{-- Merchant ID (Opsional) --}}
                        <div>
                            <label for="merchant_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                                <i class="fa-solid fa-id-badge text-slate-400 mr-1"></i> Merchant ID (Opsional)
                            </label>
                            <input type="text" id="merchant_id" name="merchant_id"
                                   value="{{ $config['merchant_id'] }}"
                                   placeholder="Contoh: G123456789"
                                   class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-slate-50 text-slate-800 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                            <p class="text-[11px] text-slate-400 mt-1">ID Merchant Anda yang tertera di pojok kiri atas Dashboard Midtrans.</p>
                        </div>
                    </div>

                    {{-- 3. URL Webhook & Redirect --}}
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                3. Konfigurasi URL Notifikasi Webhook
                            </label>
                            <span class="text-[11px] text-slate-400">Salin ke Dashboard Midtrans</span>
                        </div>

                        <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl flex items-center justify-between gap-3 text-xs">
                            <div class="overflow-hidden">
                                <div class="font-bold text-emerald-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-bell text-emerald-600"></i> Notification / Webhook URL
                                </div>
                                <div class="font-mono text-[11px] text-emerald-700 truncate mt-0.5" id="webhookUrlText">{{ $config['webhook_url'] }}</div>
                            </div>
                            <button type="button" onclick="copyToClipboard('{{ $config['webhook_url'] }}')"
                                    class="px-3 py-1.5 rounded-xl bg-white text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-300 font-bold text-xs transition-all flex items-center gap-1 flex-shrink-0 shadow-sm">
                                <i class="fa-regular fa-copy"></i>
                                <span>Salin</span>
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Aksi Simpan --}}
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-emerald-500"></i>
                            Kredensial disimpan langsung ke file <code class="font-mono bg-slate-100 px-1 py-0.5 rounded font-bold">.env</code> server secara aman.
                        </div>
                        <button type="submit"
                                class="w-full sm:w-auto px-7 py-3 rounded-2xl font-extrabold text-white text-xs bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 transition-all">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Pengaturan Gateway</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- KOLOM KANAN: PANDUAN CEPAT (4 KOLOM) --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Card Langkah Registrasi Midtrans --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-5">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-800 text-xs">Panduan Mudah 4 Langkah</h4>
                        <p class="text-[10px] text-slate-400">Cara dapatkan Server Key & Client Key</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-[11px] flex-shrink-0 mt-0.5">1</div>
                        <div>
                            <div class="font-bold text-slate-800">Login ke Dashboard Midtrans</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Buka <a href="https://dashboard.midtrans.com" target="_blank" class="text-emerald-600 font-bold underline hover:text-emerald-700">dashboard.midtrans.com</a> dan buat akun baru gratis.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-[11px] flex-shrink-0 mt-0.5">2</div>
                        <div>
                            <div class="font-bold text-slate-800">Buka Menu "Access Keys"</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Pada sidebar kiri Midtrans, klik <span class="font-mono bg-slate-100 px-1 py-0.5 rounded font-bold">Settings &gt; Access Keys</span>.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-[11px] flex-shrink-0 mt-0.5">3</div>
                        <div>
                            <div class="font-bold text-slate-800">Salin Server & Client Key</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Tempelkan Server Key dan Client Key ke formulir di sebelah kiri.
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-black text-[11px] flex-shrink-0 mt-0.5">4</div>
                        <div>
                            <div class="font-bold text-slate-800">Pasang URL Webhook</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Di Midtrans buka <span class="font-mono bg-slate-100 px-1 py-0.5 rounded font-bold">Settings &gt; Configuration</span>, lalu paste URL Notifikasi di atas ke kolom <span class="font-semibold text-slate-700">Payment Notification URL</span>.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <a href="https://dashboard.midtrans.com" target="_blank"
                       class="w-full py-2.5 rounded-xl font-bold text-xs bg-slate-900 hover:bg-black text-white flex items-center justify-center gap-2 transition-colors">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Buka Dashboard Midtrans</span>
                    </a>
                </div>
            </div>

            {{-- Card Metode Pembayaran yang Didukung --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 text-white space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-emerald-400"></i>
                    <h4 class="font-bold text-xs">Metode Pembayaran Kasir</h4>
                </div>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 flex items-center gap-2">
                        <i class="fa-solid fa-qrcode text-emerald-400 text-base"></i>
                        <span class="font-semibold">QRIS Dinamis</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 flex items-center gap-2">
                        <i class="fa-solid fa-mobile-screen text-blue-400 text-base"></i>
                        <span class="font-semibold">GoPay & DANA</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 flex items-center gap-2">
                        <i class="fa-solid fa-bag-shopping text-amber-400 text-base"></i>
                        <span class="font-semibold">ShopeePay</span>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-2.5 flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-violet-400 text-base"></i>
                        <span class="font-semibold">Virtual Account</span>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 leading-relaxed">
                    Pelanggan kasir cukup memindai kode QRIS pada layar kasir KOMPA dengan aplikasi e-wallet apa pun tanpa uang tunai.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function toggleKeyVisibility(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('icon_' + id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}

function copyToClipboard(text) {
    if (!text) {
        alert('Teks masih kosong untuk disalin!');
        return;
    }
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-5 right-5 bg-slate-900 text-white text-xs px-4 py-2.5 rounded-xl shadow-xl z-50 flex items-center gap-2 animate-bounce';
        toast.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-400"></i> Berhasil disalin ke papan klip!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    });
}

function testMidtransConnection() {
    const btn = document.getElementById('btnTestConn');
    const box = document.getElementById('testResultBox');
    const serverKey = document.getElementById('server_key').value;
    const environment = document.querySelector('input[name="environment"]:checked')?.value || 'sandbox';

    if (!serverKey) {
        alert('Silakan masukkan Server Key terlebih dahulu untuk menguji koneksi!');
        document.getElementById('server_key').focus();
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Menguji...</span>';

    fetch('{{ route("payment.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            server_key: serverKey,
            environment: environment
        })
    })
    .then(res => res.json())
    .then(data => {
        box.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-800', 'bg-rose-50', 'border-rose-200', 'text-rose-800', 'bg-amber-50', 'border-amber-200', 'text-amber-800');
        
        if (data.status === 'success') {
            box.classList.add('bg-emerald-50', 'border-emerald-300', 'text-emerald-900');
            box.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                    <div>
                        <div class="font-extrabold text-xs text-emerald-800">KONEKSI BERHASIL!</div>
                        <div class="text-[11px] text-emerald-700 mt-0.5">${data.message}</div>
                    </div>
                </div>
            `;
        } else if (data.status === 'invalid') {
            box.classList.add('bg-rose-50', 'border-rose-300', 'text-rose-900');
            box.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base mt-0.5"></i>
                    <div>
                        <div class="font-extrabold text-xs text-rose-800">KONEKSI DITOLAK (UNAUTHORIZED)</div>
                        <div class="text-[11px] text-rose-700 mt-0.5">${data.message}</div>
                    </div>
                </div>
            `;
        } else {
            box.classList.add('bg-amber-50', 'border-amber-300', 'text-amber-900');
            box.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info text-amber-600 text-base mt-0.5"></i>
                    <div>
                        <div class="font-extrabold text-xs text-amber-800">PERINGATAN</div>
                        <div class="text-[11px] text-amber-700 mt-0.5">${data.message}</div>
                    </div>
                </div>
            `;
        }
    })
    .catch(err => {
        box.classList.remove('hidden');
        box.classList.add('bg-rose-50', 'border-rose-300', 'text-rose-900');
        box.innerHTML = `
            <div class="flex items-start gap-2.5">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-base mt-0.5"></i>
                <div>
                    <div class="font-extrabold text-xs text-rose-800">GAGAL MENGHUBUNGI SERVER</div>
                    <div class="text-[11px] text-rose-700 mt-0.5">${err.message}</div>
                </div>
            </div>
        `;
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-plug-circle-bolt"></i> <span>Uji Koneksi API</span>';
    });
}
</script>
@endpush
