@extends('layouts.app')

@section('title', 'Struk Pembayaran')
@section('header_title', 'Struk Pembayaran Transaksi POS')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    
    <!-- Action buttons (Hidden during print) -->
    <div class="flex items-center justify-between gap-3 print:hidden">
        <a href="{{ route('pos.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Transaksi Kasir Baru
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            <button onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
        </div>
    </div>

    <!-- Thermal Struk Container -->
    <div class="bg-white p-8 rounded-2xl shadow-md border border-slate-200 font-mono text-xs text-slate-800 space-y-4">
        <!-- Logo & Header Toko -->
        <div class="text-center pb-4 border-b border-dashed border-slate-300">
            <div class="text-lg font-black tracking-wider text-slate-900">KOMPA KOPERASI MAHASISWA</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Sistem Kasir Ritel & Inventaris Digital</div>
            <div class="text-[11px] text-slate-400">Kampus Terpadu - Gedung Student Center Lt. 1</div>
        </div>

        <!-- Info Faktur -->
        <div class="space-y-1 text-[11px] pb-3 border-b border-dashed border-slate-300">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Faktur:</span>
                <span class="font-bold text-slate-900">{{ $transaksi->no_faktur }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal:</span>
                <span>{{ $transaksi->tgl_transaksi->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Kasir:</span>
                <span>{{ $transaksi->kasir ? $transaksi->kasir->name : 'Kasir Utama' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Pelanggan:</span>
                <span class="font-bold text-emerald-800">
                    {{ $transaksi->anggota ? $transaksi->anggota->nama . ' (NIM: ' . $transaksi->anggota->nim . ')' : 'Pelanggan Umum' }}
                </span>
            </div>
        </div>

        <!-- Item Belanja -->
        <div class="space-y-2 py-2">
            @foreach($transaksi->details as $d)
            <div>
                <div class="font-bold text-slate-800">{{ $d->barang ? $d->barang->nama_barang : $d->kode_barang }}</div>
                <div class="flex justify-between text-slate-600 text-[11px]">
                    <span>{{ $d->jumlah }} x Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</span>
                    <span class="font-bold text-slate-900">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total Pembayaran -->
        <div class="pt-3 border-t border-dashed border-slate-300 space-y-1.5 text-[11px]">
            <div class="flex justify-between">
                <span class="text-slate-500">Subtotal Belanja:</span>
                <span>Rp {{ number_format($transaksi->total_belanja, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->diskon > 0)
            <div class="flex justify-between text-rose-600">
                <span>Diskon:</span>
                <span>-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-sm font-extrabold text-slate-900 pt-1.5 border-t border-slate-200">
                <span>TOTAL AKHIR:</span>
                <span class="text-emerald-700">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tunai Diterima:</span>
                <span>Rp {{ number_format($transaksi->nominal_uang, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span class="text-slate-700">Kembalian:</span>
                <span class="text-emerald-700">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer Struk -->
        <div class="text-center pt-4 border-t border-dashed border-slate-300 text-[11px] text-slate-400 space-y-1">
            <div class="text-emerald-700 font-bold bg-emerald-50 py-1 rounded border border-emerald-200/60">
                <i class="fa-solid fa-check-circle mr-1"></i> Transaksi Berhasil & Stok Terpotong
            </div>
            <div class="pt-2">Terima kasih telah berbelanja di Koperasi Mahasiswa!</div>
            <div class="text-[10px]">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</div>
        </div>
    </div>
</div>
@endsection
