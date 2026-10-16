@extends('layouts.app')

@section('title', 'Dashboard — KOMPA')
@section('header_title', '<i class="fa-solid fa-chart-pie text-emerald-500"></i> Dashboard Utama KOMPA')

@section('content')
<div class="space-y-6">

    {{-- ===== BANNER STATUS SISTEM ===== --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg text-white"
         style="background: linear-gradient(135deg, #0b1120 0%, #0f4a26 55%, #052e16 100%);">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <i class="fa-solid fa-building-columns text-[200px]"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 p-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-bold mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Status: Operasional & Real-time
                </div>
                <h2 class="text-2xl font-extrabold tracking-tight">Sistem Koperasi Mahasiswa (KOMPA)</h2>
                <p class="text-emerald-100/80 text-sm mt-1 max-w-xl">
                    Manajemen penjualan kasir POS, inventaris barang, simpanan &amp; pinjaman anggota, SHU, dan analisis cerdas.
                </p>
            </div>
            <div class="bg-emerald-950/70 backdrop-blur rounded-xl p-4 border border-emerald-500/20 flex items-center gap-4 flex-shrink-0">
                <div class="w-11 h-11 rounded-lg bg-emerald-500/20 border border-emerald-400/20 flex items-center justify-center text-emerald-300 text-xl">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <div class="text-[10px] uppercase tracking-wider text-emerald-300 font-bold">Database XAMPP</div>
                    <div class="text-base font-extrabold text-white flex items-center gap-2">
                        kopma_24430056
                        <span class="text-[9px] bg-emerald-500 text-emerald-950 px-2 py-0.5 rounded-full font-extrabold">AKTIF</span>
                    </div>
                    <div class="text-[10px] text-emerald-300/70 font-mono">127.0.0.1:3306 | MySQL</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 4 KARTU STATISTIK ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label'=>'Total Pendapatan',  'val'=>'Rp '.number_format($totalPendapatan,0,',','.'), 'icon'=>'fa-rupiah-sign', 'color'=>'emerald', 'sub'=>'Dari seluruh transaksi kasir'],
                ['label'=>'Produk Barang',     'val'=>$totalBarang.' item', 'icon'=>'fa-boxes-stacked','color'=>'blue',    'sub'=>'Total stok: '.number_format($totalStok,0,',','.').' unit'],
                ['label'=>'Anggota Aktif',     'val'=>$totalAnggota.' orang','icon'=>'fa-users',        'color'=>'violet',  'sub'=>'Total terdaftar: '.$totalSemuaAnggota],
                ['label'=>'Omzet Hari Ini',    'val'=>'Rp '.number_format($omzetHariIni,0,',','.'),   'icon'=>'fa-arrow-trend-up','color'=>'amber','sub'=>$transaksiHariIni.' transaksi hari ini'],
            ];
            $colorMap = [
                'emerald' => ['bg'=>'bg-emerald-50','icon'=>'bg-emerald-100 text-emerald-700','val'=>'text-emerald-700','border'=>'border-emerald-100'],
                'blue'    => ['bg'=>'bg-blue-50',   'icon'=>'bg-blue-100 text-blue-700',      'val'=>'text-blue-700',   'border'=>'border-blue-100'],
                'violet'  => ['bg'=>'bg-violet-50', 'icon'=>'bg-violet-100 text-violet-700',  'val'=>'text-violet-700', 'border'=>'border-violet-100'],
                'amber'   => ['bg'=>'bg-amber-50',  'icon'=>'bg-amber-100 text-amber-700',    'val'=>'text-amber-700',  'border'=>'border-amber-100'],
            ];
        @endphp
        @foreach($stats as $s)
        @php $c = $colorMap[$s['color']]; @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 {{ $c['bg'] }} rounded-bl-full -mr-4 -mt-4 group-hover:scale-110 transition-transform duration-300"></div>
            <div class="relative z-10 flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $s['label'] }}</span>
                <div class="w-9 h-9 rounded-xl {{ $c['icon'] }} flex items-center justify-center text-sm">
                    <i class="fa-solid {{ $s['icon'] }}"></i>
                </div>
            </div>
            <div class="relative z-10 text-xl font-extrabold {{ $c['val'] }} mb-1">{{ $s['val'] }}</div>
            <div class="relative z-10 text-[11px] text-slate-400">{{ $s['sub'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ===== KASIR PANEL: Chart 7 Hari + Stok Menipis ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Grafik Penjualan 7 Hari --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-emerald-500"></i>
                    <h3 class="font-bold text-slate-800 text-sm">Penjualan 7 Hari Terakhir</h3>
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold">Dashboard Kasir</span>
                </div>
                <a href="{{ route('analisis.index') }}" class="text-xs text-emerald-600 hover:underline font-semibold">
                    Analisis Lengkap <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Omzet Bulan Ini</div>
                        <div class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="h-48">
                    <canvas id="kasirChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Panel Stok Menipis --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Stok Menipis</h3>
                @if($stokMenipis->count() > 0)
                <span class="ml-auto text-[9px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold border border-amber-200">
                    {{ $stokMenipis->count() }} item
                </span>
                @endif
            </div>
            <div class="p-3 space-y-2 overflow-y-auto max-h-64 custom-scrollbar">
                @forelse($stokMenipis as $b)
                @php
                    $urgency = $b->stok <= 5 ? ['bg'=>'bg-rose-50 border-rose-100','txt'=>'text-rose-600','badge'=>'bg-rose-100 text-rose-700'] : ['bg'=>'bg-amber-50 border-amber-100','txt'=>'text-amber-700','badge'=>'bg-amber-100 text-amber-700'];
                @endphp
                <div class="{{ $urgency['bg'] }} border rounded-xl px-3 py-2.5 flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-700 truncate">{{ $b->nama_barang }}</div>
                        <div class="text-[10px] text-slate-400">{{ $b->kategori->nama_kategori ?? '—' }}</div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-sm font-extrabold {{ $urgency['txt'] }}">{{ $b->stok }}</span>
                        <div class="text-[10px] text-slate-400">{{ $b->satuan }}</div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-2">
                        <i class="fa-solid fa-check text-emerald-500"></i>
                    </div>
                    <div class="text-xs font-bold text-slate-600">Semua stok aman</div>
                    <div class="text-[10px] text-slate-400 mt-0.5">Tidak ada barang kritis</div>
                </div>
                @endforelse
            </div>
            @if($stokMenipis->count() > 0)
            <div class="px-4 pb-4">
                <a href="{{ route('barang.index') }}"
                   class="block w-full text-center py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-colors">
                    <i class="fa-solid fa-boxes-stacked mr-1"></i> Kelola Stok Barang
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- ===== TABEL TRANSAKSI TERKINI ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-receipt text-emerald-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Transaksi Terkini</h3>
            </div>
            <a href="{{ route('transaksi.index') }}" class="text-xs text-emerald-600 hover:underline font-semibold">
                Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-left">
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">No. Faktur</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Anggota / Pembeli</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Bayar</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kasir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerkini as $trx)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('pos.receipt', $trx->id_transaksi) }}"
                               class="font-mono text-xs text-emerald-600 hover:underline">{{ $trx->no_faktur }}</a>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($trx->anggota)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($trx->anggota->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-800">{{ $trx->anggota->nama }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $trx->anggota->nim }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-xs text-slate-400 italic">Pelanggan Umum</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ $trx->tgl_transaksi->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-5 py-3.5 font-bold text-emerald-700 text-sm">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ $trx->kasir->name ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const penjualan7Hari = @json($penjualan7Hari);
const ctx = document.getElementById('kasirChart')?.getContext('2d');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: penjualan7Hari.map(d => d.tanggal),
            datasets: [{
                label: 'Omzet (Rp)',
                data: penjualan7Hari.map(d => d.omzet),
                backgroundColor: penjualan7Hari.map((d, i) =>
                    i === penjualan7Hari.length - 1 ? 'rgba(34,197,94,0.95)' : 'rgba(34,197,94,0.35)'
                ),
                borderColor: 'rgba(34,197,94,1)',
                borderWidth: 1.5,
                borderRadius: 7,
                hoverBackgroundColor: 'rgba(34,197,94,0.85)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID') + ' (' + penjualan7Hari[ctx.dataIndex].jumlah + ' trx)'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => 'Rp' + (v/1000).toFixed(0)+'K', font: { size: 10 } },
                    grid: { color: 'rgba(0,0,0,.04)' }
                },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        }
    });
}
</script>
@endpush
