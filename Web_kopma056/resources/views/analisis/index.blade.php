@extends('layouts.app')

@section('title', 'Analisis Cerdas & Rencana Kedepan — KOMPA')
@section('header_title', 'Analisis Cerdas & Rencana Kedepan')

@push('styles')
<style>
    .pulse-danger { animation: pulseDanger 2s infinite; }
    @keyframes pulseDanger { 0%,100%{ box-shadow:0 0 0 0 rgba(239,68,68,.3); } 50%{ box-shadow:0 0 0 8px transparent; } }
    @media print {
        aside, header, .no-print { display: none !important; }
        main, body { background: white !important; padding: 0 !important; }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- ===== HEADER NAVIGASI & TOMBOL CETAK ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                    <i class="fa-solid fa-brain text-emerald-600"></i> AI & Smart Analytics Engine
                </span>
                <span class="text-xs text-slate-400">Pembaruan: {{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
            </div>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                Laporan komprehensif real-time dan rekomendasi rencana strategis yang harus dipersiapkan pengurus koperasi.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-4 py-2.5 rounded-2xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 flex items-center gap-2 shadow-sm transition-all">
                <i class="fa-solid fa-print text-emerald-600"></i>
                <span>Cetak Lembar Rencana Kerja</span>
            </button>
        </div>
    </div>

    {{-- ===== BANNER REKOMENDASI UTAMA UNTUK ADMIN ===== --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-7 text-white shadow-xl"
         style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f766e 100%);">
        <div class="absolute right-4 -bottom-8 opacity-10 text-[220px] pointer-events-none">
            <i class="fa-solid fa-compass-drafting"></i>
        </div>
        <div class="relative z-10 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="bg-white/20 backdrop-blur-md border border-white/20 text-emerald-100 text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    <i class="fa-solid fa-bullhorn mr-1"></i> Roadmap Tindakan Admin Minggu Ini
                </span>
                <div class="text-xs text-emerald-100 font-mono">
                    Target Evaluasi: {{ now()->translatedFormat('F Y') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider">1. Anggaran Restock Disiapkan</div>
                    <div class="text-xl font-black text-white mt-1">
                        Rp {{ number_format($totalDanaRestockDibutuhkan, 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-emerald-100/80 mt-1">
                        Untuk pengadaan <b>{{ $jumlahBarangPerluRestock }} barang</b> agar tidak kehabisan stok.
                    </div>
                </div>

                <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider">2. Rencana Jadwal Kasir</div>
                    <div class="text-xl font-black text-white mt-1 capitalize">
                        Puncak Waktu {{ $waktuPuncak }}
                    </div>
                    <div class="text-[11px] text-emerald-100/80 mt-1">
                        Tugaskan 2 kasir aktif saat jam istirahat kuliah (11:00 - 14:00).
                    </div>
                </div>

                <div class="bg-black/20 backdrop-blur-md border border-white/10 rounded-2xl p-4">
                    <div class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider">3. Cadangan Kas Operasional</div>
                    <div class="text-xl font-black text-white mt-1">
                        Rp {{ number_format($targetCadanganKas, 0, ',', '.') }}
                    </div>
                    <div class="text-[11px] text-emerald-100/80 mt-1">
                        Pertahankan safety reserve 25% dari omzet bulanan koperasi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== KARTU METRIK EKSEKUTIF RINCI ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Omzet --}}
        <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Omzet Bulan Ini</div>
                <div class="text-2xl font-black text-slate-900 mt-1">
                    Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Proyeksi akhir bulan: <b class="text-emerald-700">Rp {{ number_format($proyeksiBulanIni, 0, ',', '.') }}</b>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-chart-line"></i>
            </div>
        </div>

        {{-- Laba Kotor & Margin --}}
        <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Estimasi Laba Kotor</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">
                    Rp {{ number_format($estimasiLabaKotor, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Margin Rata-rata: <span class="font-bold text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $marginPersen }}%</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        {{-- Rata-rata Nilai Belanja (Basket Size) --}}
        <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Rata-rata Belanja</div>
                <div class="text-2xl font-black text-slate-900 mt-1">
                    Rp {{ number_format($avgBasketSize, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Dari <b>{{ $totalTransaksiBulanIni }} transaksi</b> kasir tercatat
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
        </div>

        {{-- Alokasi Dana SHU --}}
        <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Estimasi Alokasi SHU</div>
                <div class="text-2xl font-black text-violet-700 mt-1">
                    Rp {{ number_format($estimasiAlokasiSHU, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Dicadangkan untuk pembagian RAT Anggota
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>
    </div>

    {{-- ========================================================
         BAGIAN 1: RENCANA PENGADAAN & ESTIMASI ANGGARAN RESTOCK
    ========================================================= --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-lg">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">Rencana Pengadaan & Anggaran Restock Prioritas</h3>
                    <p class="text-xs text-slate-500">Daftar barang yang harus segera dibeli oleh pengurus agar persediaan toko tidak kosong</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-wallet text-amber-600 mr-1"></i> Total Kebutuhan Dana: <b>Rp {{ number_format($totalDanaRestockDibutuhkan, 0, ',', '.') }}</b>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 font-extrabold uppercase border-b border-slate-200/80 text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Urgensi</th>
                        <th class="py-3.5 px-4">Nama Barang & Kategori</th>
                        <th class="py-3.5 px-4 text-center">Sisa Stok</th>
                        <th class="py-3.5 px-4 text-center">Ketahanan Stok</th>
                        <th class="py-3.5 px-4 text-center">Rekomendasi Beli</th>
                        <th class="py-3.5 px-4 text-right">Harga Pokok (Beli)</th>
                        <th class="py-3.5 px-6 text-right">Anggaran Restock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rencanaRestock as $r)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3.5 px-6">
                            @if($r['urgensi'] === 'kritis')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 animate-pulse">
                                <i class="fa-solid fa-circle-exclamation"></i> KRITIS (SEGERA)
                            </span>
                            @elseif($r['urgensi'] === 'waspada')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                <i class="fa-solid fa-triangle-exclamation"></i> WASPADA
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                <i class="fa-solid fa-circle-info"></i> REKOMENDASI
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-extrabold text-slate-900">{{ $r['nama_barang'] }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $r['kode_barang'] }} • {{ $r['kategori'] }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="font-black text-xs {{ $r['stok_sekarang'] <= 3 ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $r['stok_sekarang'] }} {{ $r['satuan'] }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold">
                            @if($r['estimasi_hari_habis'] <= 3)
                            <span class="text-rose-600 font-black">&le; {{ $r['estimasi_hari_habis'] }} Hari</span>
                            @else
                            <span class="text-amber-700">&plusmn; {{ $r['estimasi_hari_habis'] }} Hari</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                +{{ $r['qty_rekomendasi'] }} {{ $r['satuan'] }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono text-slate-600">
                            Rp {{ number_format($r['harga_beli'], 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-6 text-right font-black text-slate-900 text-sm">
                            Rp {{ number_format($r['estimasi_biaya'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-2"></i>
                            <div class="font-bold">Seluruh stok barang dalam kondisi aman (&gt; 10 pcs).</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================================================
         BAGIAN 2: HERO PRODUCTS VS SLOW MOVING
    ========================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- TOP 5 HERO PRODUCTS (FAST MOVING) --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">Produk Terlaris (Hero Products)</h4>
                        <p class="text-[11px] text-slate-400">Penyumbang perputaran omzet kasir tertinggi</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold bg-emerald-50 text-emerald-800 px-2.5 py-1 rounded-lg">
                    Fast-Moving
                </span>
            </div>

            <div class="space-y-3">
                @foreach($heroProducts as $index => $hp)
                <div class="p-3 bg-slate-50/70 border border-slate-200/70 rounded-2xl flex items-center justify-between gap-3 text-xs hover:border-emerald-300 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div class="font-black text-slate-900">{{ $hp->barang ? $hp->barang->nama_barang : $hp->kode_barang }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">
                                Terjual: <b class="text-slate-800">{{ $hp->qty_terjual }} pcs</b> • Sisa Stok: {{ $hp->barang ? $hp->barang->stok : '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-black text-emerald-700 text-sm">Rp {{ number_format($hp->omzet_produk, 0, ',', '.') }}</div>
                        <span class="text-[10px] text-slate-400 font-semibold">Total Kontribusi</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-100 text-[11px] text-emerald-900 flex items-start gap-2">
                <i class="fa-solid fa-lightbulb text-emerald-600 mt-0.5"></i>
                <div>
                    <b>Rencana Aksi Admin:</b> Jaga persediaan 5 produk di atas minimal buffer stok 2 minggu. Kehabisan produk ini akan langsung menurunkan omzet kasir harian.
                </div>
            </div>
        </div>

        {{-- SLOW-MOVING ITEMS (MODAL KERJA TERTAHAN) --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">Produk Lambat Terjual (Slow-Moving)</h4>
                        <p class="text-[11px] text-slate-400">Stok banyak namun perputaran barang lambat</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold bg-amber-50 text-amber-800 px-2.5 py-1 rounded-lg">
                    Modal Tertahan
                </span>
            </div>

            <div class="space-y-3">
                @forelse(array_slice($slowMovingProducts, 0, 4) as $sm)
                <div class="p-3 bg-amber-50/40 border border-amber-200/60 rounded-2xl flex items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="font-extrabold text-slate-900">{{ $sm['nama'] }}</div>
                        <div class="text-[11px] text-slate-500">
                            Stok tersisa: <b class="text-amber-800">{{ $sm['stok'] }} pcs</b> (Baru terjual {{ $sm['terjual'] }} pcs)
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-mono font-bold text-amber-900 text-xs">Rp {{ number_format($sm['nilai_tertahan'], 0, ',', '.') }}</div>
                        <span class="text-[10px] text-amber-600 font-semibold">Dana Tertahan</span>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl mb-1"></i>
                    <div>Tidak ada produk yang tertahan lama. Perputaran stok sehat!</div>
                </div>
                @endforelse
            </div>

            <div class="p-3 rounded-2xl bg-amber-50 border border-amber-100 text-[11px] text-amber-900 flex items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
                <div>
                    <b>Rencana Aksi Admin:</b> Lakukan program bundling paket hemat bersama produk terlaris atau berikan diskon 5%-10% bagi anggota untuk melancarkan perputaran kas.
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================================
         BAGIAN 3: PERAMALAN OMZET & RENCANA FINANSIAL BULAN DEPAN
    ========================================================= --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-7 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-emerald-600"></i>
                    Peramalan Omzet & Skenario Finansial Bulan Depan
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Model proyeksi pendapatan untuk perencanaan arus kas dan alokasi modal koperasi</p>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700">
                Baseline: Rp {{ number_format($proyeksiBulanIni, 0, ',', '.') }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Skenario Konservatif --}}
            <div class="border-2 border-slate-200 rounded-3xl p-5 bg-slate-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-slate-700">Konservatif</span>
                    <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">-10%</span>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    Rp {{ number_format($forecastBulanDepan['konservatif']['nominal'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    {{ $forecastBulanDepan['konservatif']['keterangan'] }}
                </p>
                <div class="pt-2 border-t border-slate-200 text-[10px] text-slate-400 font-bold uppercase">
                    Rencana: Pengeluaran operasional dihemat.
                </div>
            </div>

            {{-- Skenario Moderat --}}
            <div class="border-2 border-blue-300 rounded-3xl p-5 bg-blue-50/30 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-blue-900">Moderat (Tren Normal)</span>
                    <span class="text-xs font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">+6%</span>
                </div>
                <div class="text-2xl font-black text-blue-900">
                    Rp {{ number_format($forecastBulanDepan['moderat']['nominal'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed">
                    {{ $forecastBulanDepan['moderat']['keterangan'] }}
                </p>
                <div class="pt-2 border-t border-blue-200 text-[10px] text-blue-700 font-bold uppercase">
                    Rencana: Pembelian stok reguler terjadwal.
                </div>
            </div>

            {{-- Skenario Agresif --}}
            <div class="border-2 border-emerald-400 rounded-3xl p-5 bg-emerald-50/40 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-emerald-900">Agresif (Optimal)</span>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-200 px-2 py-0.5 rounded-full">+18%</span>
                </div>
                <div class="text-2xl font-black text-emerald-800">
                    Rp {{ number_format($forecastBulanDepan['agresif']['nominal'], 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-emerald-800/90 leading-relaxed">
                    {{ $forecastBulanDepan['agresif']['keterangan'] }}
                </p>
                <div class="pt-2 border-t border-emerald-200 text-[10px] text-emerald-800 font-bold uppercase">
                    Rencana: Realisasikan restock & promosi QRIS.
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================
         BAGIAN 4: JAM OPERASIONAL & PENJADWALAN KASIR
    ========================================================= --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-7 space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-900 text-sm">Pola Jam Transaksi Teramai (Peak Hours)</h4>
                    <p class="text-[11px] text-slate-400">Gunakan data ini untuk mengatur shift penjadwalan kasir/staf koperasi</p>
                </div>
            </div>
            <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">
                Shift Kasir
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Pagi (08:00 - 11:00)</div>
                <div class="text-xl font-black text-slate-800 mt-1">{{ $polaWaktu['pagi'] }} Trx</div>
                <div class="text-[10px] text-slate-500 mt-1">1 Kasir Cukup</div>
            </div>

            <div class="p-4 rounded-2xl {{ $waktuPuncak === 'siang' ? 'bg-emerald-50 border-2 border-emerald-400' : 'bg-slate-50 border border-slate-200/80' }}">
                <div class="text-[10px] font-bold {{ $waktuPuncak === 'siang' ? 'text-emerald-800' : 'text-slate-400' }} uppercase">
                    Siang (11:00 - 14:00) @if($waktuPuncak === 'siang') &starf; @endif
                </div>
                <div class="text-xl font-black {{ $waktuPuncak === 'siang' ? 'text-emerald-700' : 'text-slate-800' }} mt-1">{{ $polaWaktu['siang'] }} Trx</div>
                <div class="text-[10px] {{ $waktuPuncak === 'siang' ? 'text-emerald-800 font-bold' : 'text-slate-500' }} mt-1">
                    {{ $waktuPuncak === 'siang' ? 'WAJIB 2 KASIR' : '1-2 Kasir' }}
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Sore (14:00 - 18:00)</div>
                <div class="text-xl font-black text-slate-800 mt-1">{{ $polaWaktu['sore'] }} Trx</div>
                <div class="text-[10px] text-slate-500 mt-1">1-2 Kasir</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="text-[10px] font-bold text-slate-400 uppercase">Malam (18:00 - 21:00)</div>
                <div class="text-xl font-black text-slate-800 mt-1">{{ $polaWaktu['malam'] }} Trx</div>
                <div class="text-[10px] text-slate-500 mt-1">1 Kasir / Tutup Toko</div>
            </div>
        </div>
    </div>

    {{-- ========================================================
         BAGIAN 5: GRAFIK PENJUALAN 7 HARI & TREN BULANAN
    ========================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Grafik 7 Hari --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h4 class="font-black text-slate-900 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-emerald-600"></i>
                    Omzet 7 Hari Terakhir
                </h4>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Real-time</span>
            </div>
            <div class="h-60">
                <canvas id="omzetChart"></canvas>
            </div>
        </div>

        {{-- Grafik Skenario 12 Bulan --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h4 class="font-black text-slate-900 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-blue-600"></i>
                    Tren & Skenario 12 Bulan
                </h4>
                <div class="flex items-center gap-2 text-[10px] font-bold">
                    <span class="text-emerald-600">&bull; Optimis</span>
                    <span class="text-blue-600">&bull; Moderat</span>
                    <span class="text-rose-500">&bull; Pesimis</span>
                </div>
            </div>
            <div class="h-60">
                <canvas id="skenarioChart"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Data dari Controller
const omzetHarian = @json($omzetHarian);
const omzetBulanan = @json($omzetBulanan);

function fmtRp(v) { 
    return 'Rp ' + Number(v).toLocaleString('id-ID'); 
}

// 1. Chart Omzet 7 Hari
const ctx1 = document.getElementById('omzetChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: omzetHarian.map(d => d.tanggal),
        datasets: [{
            label: 'Omzet',
            data: omzetHarian.map(d => d.omzet),
            backgroundColor: 'rgba(16, 185, 129, 0.75)',
            borderColor: 'rgba(5, 150, 105, 1)',
            borderWidth: 1.5,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => fmtRp(ctx.parsed.y) } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp ' + (v/1000) + 'k', font: { size: 10 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { ticks: { font: { size: 10 } }, grid: { display: false } }
        }
    }
});

// 2. Chart Skenario 12 Bulan
const ctx2 = document.getElementById('skenarioChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: omzetBulanan.map(d => d.label),
        datasets: [
            {
                label: 'Optimis (+15%)',
                data: omzetBulanan.map(d => d.optimis),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                borderWidth: 2,
                pointRadius: 2.5,
                tension: 0.35,
                fill: false,
            },
            {
                label: 'Moderat (+5%)',
                data: omzetBulanan.map(d => d.moderat),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                borderWidth: 2,
                pointRadius: 2.5,
                tension: 0.35,
                fill: false,
                borderDash: [4, 3],
            },
            {
                label: 'Pesimis (-10%)',
                data: omzetBulanan.map(d => d.pesimis),
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244, 63, 94, 0.05)',
                borderWidth: 2,
                pointRadius: 2.5,
                tension: 0.35,
                fill: false,
                borderDash: [2, 3],
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp ' + (v/1000) + 'k', font: { size: 10 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { ticks: { font: { size: 9 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
