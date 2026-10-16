@extends('layouts.app')

@section('title', 'Payment Gateway — KOMPA')
@section('header_title', '<i class="fa-solid fa-credit-card text-emerald-500"></i> Pengaturan Payment Gateway')

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- ===== HEADER BANNER ===== --}}
    <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg"
         style="background: linear-gradient(135deg, #0b1120, #1e3a5f);">
        <div class="absolute right-4 bottom-0 opacity-10 text-[160px]"><i class="fa-brands fa-cc-mastercard"></i></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold mb-3">
                    <i class="fa-solid fa-shield-halved"></i> Midtrans Payment Gateway
                </div>
                <h2 class="text-2xl font-extrabold">Pengaturan Pembayaran Online</h2>
                <p class="text-slate-300 text-sm mt-1">Konfigurasi integrasi Midtrans untuk pembayaran digital anggota KOPMA (Sandbox / Produksi).</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white/10 border border-white/10 rounded-xl px-4 py-3 text-center">
                    <div class="text-[10px] text-slate-400 uppercase tracking-wider">Mode Aktif</div>
                    <div class="text-sm font-bold mt-0.5 {{ $config['environment'] === 'sandbox' ? 'text-amber-300' : 'text-emerald-300' }}">
                        {{ strtoupper($config['environment']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FORM KONFIGURASI ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-gear text-slate-400"></i>
            <h3 class="font-bold text-slate-800 text-sm">Konfigurasi API Midtrans</h3>
        </div>
        <form action="{{ route('payment.save') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Environment Toggle --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">Mode Lingkungan</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="environment" value="sandbox"
                               {{ $config['environment'] === 'sandbox' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-amber-400 peer-checked:bg-amber-50 border-slate-200 hover:border-slate-300">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <i class="fa-solid fa-flask text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-800">Sandbox</div>
                                    <div class="text-xs text-slate-500">Mode pengujian (tidak ada transaksi nyata)</div>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="environment" value="production"
                               {{ $config['environment'] === 'production' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="border-2 rounded-xl p-4 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 border-slate-200 hover:border-slate-300">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <i class="fa-solid fa-rocket text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-800">Produksi</div>
                                    <div class="text-xs text-slate-500">Mode live — transaksi uang nyata</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- API Keys --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="server_key" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-server text-slate-400 mr-1"></i> Server Key
                    </label>
                    <div class="relative">
                        <input type="password" id="server_key" name="server_key"
                               value="{{ $config['server_key'] }}"
                               placeholder="SB-Mid-server-xxxxxxxxxxxx"
                               class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent">
                        <button type="button" onclick="toggleKey('server_key')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Digunakan di backend — JANGAN tampilkan ke publik.</p>
                </div>
                <div>
                    <label for="client_key" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-key text-slate-400 mr-1"></i> Client Key
                    </label>
                    <div class="relative">
                        <input type="text" id="client_key" name="client_key"
                               value="{{ $config['client_key'] }}"
                               placeholder="SB-Mid-client-xxxxxxxxxxxx"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Digunakan di frontend (Snap.js).</p>
                </div>
            </div>

            {{-- Webhook URLs --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-webhook text-slate-400 mr-1"></i> URL Konfigurasi
                </label>
                <div class="space-y-2">
                    @php
                        $urls = [
                            ['label'=>'Notification / Webhook URL (Daftarkan ke Dashboard Midtrans)', 'val'=>$config['webhook_url'],  'color'=>'emerald'],
                            ['label'=>'Finish URL (Redirect setelah bayar)',   'val'=>$config['finish_url'],   'color'=>'blue'],
                            ['label'=>'Error URL (Redirect jika transaksi gagal)', 'val'=>$config['error_url'],    'color'=>'rose'],
                            ['label'=>'Unfinish URL (Redirect jika dibatalkan)',   'val'=>$config['unfinish_url'], 'color'=>'amber'],
                        ];
                        $urlColors = ['emerald'=>'bg-emerald-50 border-emerald-100 text-emerald-700', 'blue'=>'bg-blue-50 border-blue-100 text-blue-700','rose'=>'bg-rose-50 border-rose-100 text-rose-700','amber'=>'bg-amber-50 border-amber-100 text-amber-700'];
                    @endphp
                    @foreach($urls as $u)
                    <div class="{{ $urlColors[$u['color']] }} border rounded-xl px-4 py-2.5 flex items-center justify-between gap-3">
                        <div class="text-[11px] text-slate-500 min-w-[200px]">{{ $u['label'] }}</div>
                        <code class="text-xs font-mono font-bold flex-1 text-right">{{ $u['val'] }}</code>
                        <button type="button" onclick="copyUrl('{{ $u['val'] }}')"
                                class="text-slate-400 hover:text-slate-700 flex-shrink-0 p-1">
                            <i class="fa-regular fa-copy text-sm"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-2 border-t border-slate-100 flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi
                </button>
                <p class="text-[11px] text-slate-400">
                    <i class="fa-solid fa-info-circle text-emerald-400"></i>
                    Konfigurasi akan disimpan ke file <code class="font-mono bg-slate-100 px-1 rounded">.env</code> server.
                </p>
            </div>
        </form>
    </div>

    {{-- ===== INFO INTEGRASI ===== --}}
    <div class="bg-slate-800 rounded-2xl p-6 text-white">
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-code text-emerald-400"></i>
            <h3 class="font-bold text-sm">Cara Integrasi Snap.js (Frontend)</h3>
        </div>
        <pre class="text-xs text-emerald-300 bg-black/30 rounded-xl p-4 overflow-x-auto leading-relaxed font-mono">&lt;script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $config['client_key'] ?: 'YOUR_CLIENT_KEY' }}"&gt;&lt;/script&gt;

&lt;script&gt;
  snap.pay('SNAP_TOKEN_FROM_SERVER', {
    onSuccess: (result) => { console.log('success', result); },
    onPending: (result) => { console.log('pending', result); },
    onError:   (result) => { console.log('error', result);   },
  });
&lt;/script&gt;</pre>
        <p class="text-xs text-slate-400 mt-3">
            <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
            Token SNAP dibuat dari backend menggunakan <code class="text-emerald-300 font-mono">Server Key</code> via Midtrans API.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleKey(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
function copyUrl(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('URL disalin: ' + text);
    });
}
</script>
@endpush
