@extends('layouts.app')

@section('title', 'Analisis Cerdas — KOMPA')
@section('header_title', '<i class="fa-solid fa-brain text-emerald-500"></i> Analisis Cerdas (Smart Analysis)')

@push('styles')
<style>
    .pulse-warning { animation: pulseWarn 2s infinite; }
    @keyframes pulseWarn { 0%,100%{ box-shadow:0 0 0 0 rgba(239,68,68,.3); } 50%{ box-shadow:0 0 0 8px transparent; } }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ===== JUDUL SECTION ===== --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-sm mt-0.5">Temuan otomatis & rekomendasi tindakan berdasarkan data real-time sistem KOMPA</p>
        </div>
        <div class="text-xs text-slate-400 bg-white border border-slate-200 px-3 py-1.5 rounded-lg">
            <i class="fa-solid fa-clock text-emerald-500 mr-1"></i>
            Diperbarui: {{ now()->translatedFormat('d M Y, H:i') }} WIB
        </div>
    </div>

    {{-- ===== PERINGATAN & TEMUAN (3 Kartu) ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Kredit Macet --}}
        <div class="relative bg-white rounded-2xl border-2 {{ $kreditMacet->count() > 0 ? 'border-rose-300 pulse-warning' : 'border-emerald-200' }} shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl {{ $kreditMacet->count() > 0 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kredit Macet</div>
                        <div class="text-xl font-extrabold {{ $kreditMacet->count() > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $kreditMacet->count() }} kasus
                        </div>
                    </div>
                </div>
                @if($kreditMacet->count() > 0)
                <div class="space-y-1.5">
                    @foreach($kreditMacet->take(3) as $km)
                    <div class="flex items-center justify-between text-xs bg-rose-50 rounded-lg px-3 py-2">
                        <span class="font-semibold text-rose-700">{{ $km->anggota->nama }}</span>
                        <span class="text-rose-500 font-mono">Rp {{ number_format($km->sisa_pinjaman, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 p-2.5 bg-rose-50 rounded-lg border border-rose-100">
                    <div class="text-[10px] text-rose-600 font-bold flex items-center gap-1"><i class="fa-solid fa-lightbulb"></i> REKOMENDASI</div>
                    <div class="text-[11px] text-rose-500 mt-0.5">Kirim surat teguran & jadwalkan mediasi pembayaran cicilan.</div>
                </div>
                @else
                <div class="text-xs text-emerald-600 bg-emerald-50 rounded-lg p-2.5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Tidak ada kredit macet. Keuangan sehat!
                </div>
                @endif
            </div>
        </div>

        {{-- Stok Kritis --}}
        <div class="relative bg-white rounded-2xl border-2 {{ $stokKritis->count() > 0 ? 'border-amber-300' : 'border-emerald-200' }} shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl {{ $stokKritis->count() > 0 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center text-lg">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stok Kritis</div>
                        <div class="text-xl font-extrabold {{ $stokKritis->count() > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $stokKritis->count() }} barang
                        </div>
                    </div>
                </div>
                @if($stokKritis->count() > 0)
                <div class="space-y-1.5">
                    @foreach($stokKritis->take(3) as $b)
                    <div class="flex items-center justify-between text-xs bg-amber-50 rounded-lg px-3 py-2">
                        <span class="font-semibold text-amber-800 truncate max-w-[120px]">{{ $b->nama_barang }}</span>
                        <span class="text-amber-600 font-bold">{{ $b->stok }} {{ $b->satuan }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 p-2.5 bg-amber-50 rounded-lg border border-amber-100">
                    <div class="text-[10px] text-amber-600 font-bold flex items-center gap-1"><i class="fa-solid fa-lightbulb"></i> REKOMENDASI</div>
                    <div class="text-[11px] text-amber-500 mt-0.5">Segera hubungi pemasok untuk restock barang kritis di atas.</div>
                </div>
                @else
                <div class="text-xs text-emerald-600 bg-emerald-50 rounded-lg p-2.5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Semua stok barang dalam kondisi aman.
                </div>
                @endif
            </div>
        </div>

        {{-- SHU Belum Dibagi --}}
        <div class="relative bg-white rounded-2xl border-2 {{ $shuBelumDibagi->count() > 0 ? 'border-violet-300' : 'border-emerald-200' }} shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl {{ $shuBelumDibagi->count() > 0 ? 'bg-violet-100 text-violet-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center text-lg">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SHU Belum Dibagi</div>
                        <div class="text-xl font-extrabold {{ $shuBelumDibagi->count() > 0 ? 'text-violet-600' : 'text-emerald-600' }}">
                            {{ $shuBelumDibagi->sum('jumlah_anggota') }} anggota
                        </div>
                    </div>
                </div>
                @if($shuBelumDibagi->count() > 0)
                <div class="space-y-1.5">
                    @foreach($shuBelumDibagi as $sh)
                    <div class="flex items-center justify-between text-xs bg-violet-50 rounded-lg px-3 py-2">
                        <span class="font-semibold text-violet-700">Tahun {{ $sh->tahun }}</span>
                        <span class="text-violet-500 font-mono">Rp {{ number_format($sh->total, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 p-2.5 bg-violet-50 rounded-lg border border-violet-100">
                    <div class="text-[10px] text-violet-600 font-bold flex items-center gap-1"><i class="fa-solid fa-lightbulb"></i> REKOMENDASI</div>
                    <div class="text-[11px] text-violet-500 mt-0.5">Persiapkan Rapat Anggota Tahunan (RAT) untuk pembagian SHU.</div>
                </div>
                @else
                <div class="text-xs text-emerald-600 bg-emerald-50 rounded-lg p-2.5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Semua SHU sudah dibagikan kepada anggota.
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== GRAFIK PENJUALAN 7 HARI ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-emerald-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Grafik Omzet 7 Hari Terakhir</h3>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Real-time
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="h-60">
                <canvas id="omzetChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ===== GRAFIK SKENARIO OMZET BULANAN ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-violet-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Ramalan & Skenario Omzet Bulanan</h3>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-emerald-600"><span class="w-3 h-0.5 bg-emerald-500 inline-block rounded"></span>Optimis +15%</span>
                <span class="flex items-center gap-1 text-blue-600"><span class="w-3 h-0.5 bg-blue-500 inline-block rounded"></span>Moderat +5%</span>
                <span class="flex items-center gap-1 text-rose-500"><span class="w-3 h-0.5 bg-rose-400 inline-block rounded"></span>Pesimis -10%</span>
            </div>
        </div>
        <div class="p-6">
            <div class="h-64">
                <canvas id="skenarioChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ===== STATISTIK RINGKAS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Omzet Bulan Ini</div>
            <div class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ now()->translatedFormat('F Y') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Rata-rata Harian</div>
            <div class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($rataHarian, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400 mt-1">Per hari ({{ now()->day }} hari berjalan)</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">Proyeksi Akhir Bulan</div>
            <div class="text-2xl font-extrabold text-emerald-600">Rp {{ number_format($proyeksiBulanIni, 0, ',', '.') }}</div>
            <div class="text-xs text-slate-400 mt-1">Berdasarkan rata-rata harian</div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Data dari PHP
const omzetHarian = @json($omzetHarian);
const omzetBulanan = @json($omzetBulanan);

// Helper format Rupiah
function fmtRp(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); }

// ---- CHART 1: Omzet 7 Hari ----
const ctx1 = document.getElementById('omzetChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: omzetHarian.map(d => d.tanggal),
        datasets: [{
            label: 'Omzet (Rp)',
            data: omzetHarian.map(d => d.omzet),
            backgroundColor: omzetHarian.map(d =>
                d.omzet === Math.max(...omzetHarian.map(x=>x.omzet))
                    ? 'rgba(34,197,94,0.9)' : 'rgba(34,197,94,0.4)'
            ),
            borderColor: 'rgba(34,197,94,1)',
            borderWidth: 1.5,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => fmtRp(ctx.parsed.y) } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp ' + (v/1000) + 'K', font: { size: 10 } },
                grid: { color: 'rgba(0,0,0,.05)' }
            },
            x: { ticks: { font: { size: 10 } }, grid: { display: false } }
        }
    }
});

// ---- CHART 2: Skenario Bulanan ----
const ctx2 = document.getElementById('skenarioChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: omzetBulanan.map(d => d.label),
        datasets: [
            {
                label: 'Optimis (+15%)',
                data: omzetBulanan.map(d => d.optimis),
                borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.08)',
                borderWidth: 2, pointRadius: 3, tension: 0.4, fill: false,
            },
            {
                label: 'Moderat (+5%)',
                data: omzetBulanan.map(d => d.moderat),
                borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.05)',
                borderWidth: 2, pointRadius: 3, tension: 0.4, fill: false, borderDash: [5,3],
            },
            {
                label: 'Pesimis (-10%)',
                data: omzetBulanan.map(d => d.pesimis),
                borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,.05)',
                borderWidth: 2, pointRadius: 3, tension: 0.4, fill: false, borderDash: [2,4],
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp ' + (v/1000) + 'K', font: { size: 10 } },
                grid: { color: 'rgba(0,0,0,.05)' }
            },
            x: { ticks: { font: { size: 9 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
