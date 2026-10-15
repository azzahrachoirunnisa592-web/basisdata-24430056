@extends('layouts.app')

@section('title', 'Dashboard - Ringkasan Sistem KOMPA')
@section('header_title', 'Ringkasan Sistem KOMPA - Database: kopma_24430056')

@section('content')
<div class="space-y-7">
    
    <!-- BANNER PEMBERITAHUAN UTAMA KONEKSI DATABASE & STATUS SISTEM -->
    <div class="bg-gradient-to-r from-emerald-900 via-kopma-900 to-teal-900 rounded-2xl p-6 text-white shadow-xl shadow-emerald-950/10 border border-emerald-700/50 relative overflow-hidden">
        <!-- Background decorative pattern -->
        <div class="absolute -right-10 -bottom-10 opacity-10 text-white pointer-events-none">
            <i class="fa-solid fa-building-columns text-[220px]"></i>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs font-semibold mb-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Status Server: Operasional & Real-time</span>
                </div>
                <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight">
                    Sistem Koperasi Mahasiswa (KOMPA)
                </h2>
                <p class="text-emerald-100/90 text-sm mt-1 max-w-2xl leading-relaxed">
                    Aplikasi manajemen penjualan ritel koperasi, kasir Point of Sale (POS) otomatis, inventaris barang, dan keanggotaan mahasiswa.
                </p>
            </div>

            <!-- Box Status Database MySQL -->
            <div class="bg-emerald-950/80 backdrop-blur-md rounded-xl p-4 border border-emerald-500/30 flex items-center gap-4 flex-shrink-0">
                <div class="w-12 h-12 rounded-lg bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-300 text-2xl">
                    <i class="fa-solid fa-database"></i>
                </div>
                <div>
                    <div class="text-[11px] uppercase tracking-wider text-emerald-300 font-bold">Koneksi Database XAMPP</div>
                    <div class="text-base font-extrabold text-white flex items-center gap-2">
                        kopma_24430056
                        <span class="text-[10px] bg-emerald-500 text-kopma-950 px-2 py-0.5 rounded-full font-bold">AKTIF</span>
                    </div>
                    <div class="text-[11px] text-emerald-300/80 font-mono mt-0.5">
                        Host: 127.0.0.1:3306 | Driver: MySQL
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 KARTU STATISTIK UTAMA -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- KARTU 1: TOTAL PENDAPATAN -->
        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200/80 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">TOTAL PENDAPATAN</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-rupiah-sign"></i>
                </div>
            </div>

            <div class="relative z-10 my-4">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
            </div>

            <div class="relative z-10 flex items-center justify-between text-xs pt-3 border-t border-slate-100">
                <span class="text-emerald-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    Berdasarkan transaksi kasir
                </span>
                <span class="text-slate-400 font-medium">Real-time</span>
            </div>
        </div>

        <!-- KARTU 2: TOTAL PRODUK BARANG -->
        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200/80 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-sky-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

            <div class="relative z-10 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">TOTAL PRODUK BARANG</span>
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
            </div>

            <div class="relative z-10 my-4">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-baseline gap-2">
                    {{ $totalBarang }}
                    <span class="text-base font-semibold text-slate-500">Item</span>
                </div>
            </div>

            <div class="relative z-10 flex items-center justify-between text-xs pt-3 border-t border-slate-100">
                <span class="text-sky-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-warehouse"></i>
                    Stok aktif di gudang
                </span>
                <span class="text-slate-500 font-medium">{{ number_format($totalStok, 0, ',', '.') }} unit total</span>
            </div>
        </div>

        <!-- KARTU 3: ANGGOTA AKTIF -->
        <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-200/80 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

            <div class="relative z-10 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">ANGGOTA AKTIF</span>
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
            </div>

            <div class="relative z-10 my-4">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-baseline gap-2">
                    {{ $totalAnggota }}
                    <span class="text-base font-semibold text-slate-500">Mahasiswa</span>
                </div>
            </div>

            <div class="relative z-10 flex items-center justify-between text-xs pt-3 border-t border-slate-100">
                <span class="text-purple-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check"></i>
                    Terdaftar di koperasi
                </span>
                <span class="text-slate-400 font-medium">Dari {{ $totalSemuaAnggota }} total</span>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS & POS CALLOUT -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('pos.index') }}" 
           class="p-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white flex items-center justify-between shadow-md hover:shadow-lg hover:from-emerald-700 hover:to-teal-700 transition-all group">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <div>
                    <div class="font-extrabold text-sm">Buka Mesin Kasir POS</div>
                    <div class="text-xs text-emerald-100">Input penjualan & potong stok otomatis</div>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right text-emerald-200 group-hover:translate-x-1 transition-transform"></i>
        </a>

        <a href="{{ route('barang.index') }}" 
           class="p-4 rounded-xl bg-white border border-slate-200 text-slate-800 flex items-center justify-between shadow-sm hover:shadow-md hover:border-emerald-300 transition-all group">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <div>
                    <div class="font-extrabold text-sm text-slate-900">Kelola Katalog Barang</div>
                    <div class="text-xs text-slate-500">Tambah produk, update harga & stok</div>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
        </a>

        <a href="{{ route('anggota.index') }}" 
           class="p-4 rounded-xl bg-white border border-slate-200 text-slate-800 flex items-center justify-between shadow-sm hover:shadow-md hover:border-emerald-300 transition-all group">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <div class="font-extrabold text-sm text-slate-900">Pendaftaran Anggota</div>
                    <div class="text-xs text-slate-500">Kelola data keanggotaan mahasiswa</div>
                </div>
            </div>
            <i class="fa-solid fa-arrow-right text-slate-400 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <!-- TABEL TRANSAKSI PENJUALAN TERKINI -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i>
                    Transaksi Penjualan Terkini
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi kasir yang tercatat secara real-time pada sistem koperasi</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-3.5 py-2 rounded-lg transition-colors flex items-center gap-1.5 self-start sm:self-auto">
                <span>Lihat Semua Riwayat</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-xs uppercase font-bold tracking-wider">
                        <th class="py-3.5 px-6">No. Faktur</th>
                        <th class="py-3.5 px-6">Tanggal & Waktu</th>
                        <th class="py-3.5 px-6">Pembeli / Anggota</th>
                        <th class="py-3.5 px-6">Item Dibeli</th>
                        <th class="py-3.5 px-6">Total Bayar</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerkini as $trx)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-emerald-800">
                            {{ $trx->no_faktur }}
                        </td>
                        <td class="py-4 px-6 text-slate-600 text-xs">
                            <div class="font-medium text-slate-800">{{ $trx->tgl_transaksi->format('d M Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $trx->tgl_transaksi->format('H:i') }} WIB</div>
                        </td>
                        <td class="py-4 px-6">
                            @if($trx->anggota)
                                <div class="font-semibold text-slate-800">{{ $trx->anggota->nama }}</div>
                                <div class="text-[11px] font-mono text-emerald-700 bg-emerald-50 inline-block px-1.5 py-0.5 rounded border border-emerald-200/60 mt-0.5">
                                    NIM: {{ $trx->anggota->nim ?: $trx->anggota->id_anggota }}
                                </div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600">
                                    Pelanggan Umum
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600">
                            <span class="font-semibold text-slate-800">{{ $trx->details->sum('jumlah') }} unit</span>
                            <span class="text-slate-400">({{ $trx->details->count() }} jenis produk)</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-extrabold text-slate-900 text-base">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </div>
                            <div class="text-[11px] text-emerald-600 font-medium">Lunas (Tunai)</div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('pos.receipt', $trx->id_transaksi) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 rounded-lg border border-slate-200 transition-colors">
                                <i class="fa-solid fa-receipt text-xs text-emerald-600"></i>
                                Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-inbox text-4xl mb-3 text-slate-300 block"></i>
                            Belum ada riwayat transaksi tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
