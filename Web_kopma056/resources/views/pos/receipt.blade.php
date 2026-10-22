@extends('layouts.app')

@section('title', 'Struk Pembayaran POS — KOMPA')
@section('header_title', 'Struk Pembayaran Transaksi POS')

@push('styles')
<style>
    /* Styling Cetak Printer Thermal & A4 */
    @media print {
        aside, header, .no-print, nav {
            display: none !important;
        }
        body, html, main {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            color: black !important;
        }
        .receipt-container {
            width: 100% !important;
            max-width: 78mm !important; /* Standar Thermal 80mm / 58mm */
            margin: 0 auto !important;
            padding: 4mm !important;
            box-shadow: none !important;
            border: none !important;
        }
        .a4-mode .receipt-container {
            max-width: 160mm !important;
            padding: 12mm !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-xl mx-auto space-y-5" id="receiptWrapper">
    
    {{-- ===== BAR AKSI CETAK & NAVIGASI (DISEMBUNYIKAN SAAT CETAK) ===== --}}
    <div class="bg-white p-4 rounded-3xl border border-slate-200/90 shadow-sm flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('pos.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-arrow-left"></i> Transaksi Kasir Baru
        </a>

        <div class="flex items-center gap-2">
            <button type="button" onclick="printThermal()" 
                    class="px-4 py-2.5 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/25 flex items-center gap-2 transition-all">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Thermal (58/80mm)</span>
            </button>
            <button type="button" onclick="printA4()" 
                    class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Kertas A4</span>
            </button>
        </div>
    </div>

    {{-- Banner Notifikasi Status Auto-Print --}}
    @if(isset($autoPrint) && $autoPrint)
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 rounded-2xl flex items-center justify-between text-xs no-print animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>Transaksi berhasil disimpan! Kotak dialog printer struk dibuka otomatis.</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-xs font-bold">
            Tutup
        </button>
    </div>
    @endif

    {{-- ========================================================
         CONTAINER STRUK THERMAL STANDAR KOPERASI (80mm / 58mm)
    ========================================================= --}}
    <div class="receipt-container bg-white p-6 sm:p-8 rounded-3xl shadow-lg border border-slate-200/90 font-mono text-xs text-slate-900 space-y-4">
        
        {{-- Header Identitas Toko & Koperasi --}}
        <div class="text-center pb-3 border-b-2 border-dashed border-slate-300 space-y-1">
            <div class="text-base sm:text-lg font-black tracking-wider text-slate-900">
                KOMPA KOPERASI MAHASISWA
            </div>
            <div class="text-[11px] font-bold text-emerald-800">
                Sistem Informasi Koperasi Terpadu &bull; kopma_24430056
            </div>
            <div class="text-[10px] text-slate-500">
                Kampus Terpadu - Gedung Student Center Lt. 1 &bull; NIM: 24430056
            </div>
            <div class="text-[10px] text-slate-400">
                Telp / WhatsApp: 0812-3456-7056
            </div>
        </div>

        {{-- Info Faktur & Transaksi --}}
        <div class="space-y-1.5 text-[11px] pb-3 border-b-2 border-dashed border-slate-300">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Faktur:</span>
                <span class="font-extrabold text-slate-900">{{ $transaksi->no_faktur }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal:</span>
                <span>{{ $transaksi->tgl_transaksi->format('d/m/Y H:i:s') }} WIB</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Kasir:</span>
                <span class="font-bold">{{ $transaksi->kasir ? $transaksi->kasir->name : 'Kasir Utama (24430056)' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Pembeli:</span>
                <span class="font-extrabold text-emerald-900">
                    {{ $transaksi->anggota ? $transaksi->anggota->nama . ' [' . ($transaksi->anggota->nim ?: $transaksi->anggota->id_anggota) . ']' : 'Pelanggan Umum' }}
                </span>
            </div>
            <div class="flex justify-between pt-1 border-t border-slate-100">
                <span class="text-slate-500">Metode Bayar:</span>
                <span class="font-black text-slate-900 uppercase">
                    @if(isset($metodePembayaran) && in_array($metodePembayaran, ['qris', 'midtrans']))
                        QRIS / Dompet Digital (DANA/OVO/GoPay)
                    @else
                        Tunai (Cash)
                    @endif
                </span>
            </div>
        </div>

        {{-- Daftar Item Belanja --}}
        <div class="space-y-2 py-1">
            <div class="flex justify-between text-[10px] font-extrabold text-slate-400 uppercase tracking-wider pb-1 border-b border-slate-200">
                <span>Item &amp; Kuantiti</span>
                <span class="text-right">Subtotal</span>
            </div>

            @foreach($transaksi->details as $d)
            <div class="space-y-0.5">
                <div class="font-bold text-slate-900 text-[11px]">
                    {{ $d->barang ? $d->barang->nama_barang : $d->kode_barang }}
                </div>
                <div class="flex justify-between text-slate-600 text-[11px]">
                    <span class="text-slate-500 font-mono">
                        {{ $d->jumlah }} pcs &times; Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                    </span>
                    <span class="font-extrabold text-slate-900">
                        Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan Total Pembayaran --}}
        <div class="pt-3 border-t-2 border-dashed border-slate-300 space-y-1.5 text-[11px]">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Belanja:</span>
                <span class="font-bold">Rp {{ number_format($transaksi->total_belanja, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->diskon > 0)
            <div class="flex justify-between text-rose-600 font-bold">
                <span>Diskon Promo:</span>
                <span>-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-sm font-black text-slate-900 pt-1.5 border-t border-slate-200">
                <span>TOTAL AKHIR:</span>
                <span class="text-emerald-700 text-base">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-700">
                <span>Pembayaran ({{ isset($metodePembayaran) && in_array($metodePembayaran, ['qris', 'midtrans']) ? 'QRIS Digital' : 'Tunai Diterima' }}):</span>
                <span class="font-bold">Rp {{ number_format($transaksi->nominal_uang, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-black text-emerald-800 text-xs">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Barcode Struk Visual --}}
        <div class="pt-3 border-t-2 border-dashed border-slate-300 text-center space-y-1">
            <div class="flex justify-center items-center py-1">
                {{-- Barcode Line Visual SVG --}}
                <svg class="h-10 w-48 text-slate-800" viewBox="0 0 100 24" preserveAspectRatio="none">
                    <rect x="2" y="0" width="2" height="24" fill="currentColor"/>
                    <rect x="6" y="0" width="3" height="24" fill="currentColor"/>
                    <rect x="11" y="0" width="1" height="24" fill="currentColor"/>
                    <rect x="14" y="0" width="4" height="24" fill="currentColor"/>
                    <rect x="20" y="0" width="2" height="24" fill="currentColor"/>
                    <rect x="24" y="0" width="1" height="24" fill="currentColor"/>
                    <rect x="27" y="0" width="3" height="24" fill="currentColor"/>
                    <rect x="32" y="0" width="2" height="24" fill="currentColor"/>
                    <rect x="36" y="0" width="4" height="24" fill="currentColor"/>
                    <rect x="42" y="0" width="1" height="24" fill="currentColor"/>
                    <rect x="45" y="0" width="3" height="24" fill="currentColor"/>
                    <rect x="50" y="0" width="2" height="24" fill="currentColor"/>
                    <rect x="54" y="0" width="4" height="24" fill="currentColor"/>
                    <rect x="60" y="0" width="1" height="24" fill="currentColor"/>
                    <rect x="63" y="0" width="3" height="24" fill="currentColor"/>
                    <rect x="68" y="0" width="2" height="24" fill="currentColor"/>
                    <rect x="72" y="0" width="3" height="24" fill="currentColor"/>
                    <rect x="77" y="0" width="2" height="24" fill="currentColor"/>
                    <rect x="81" y="0" width="4" height="24" fill="currentColor"/>
                    <rect x="87" y="0" width="1" height="24" fill="currentColor"/>
                    <rect x="90" y="0" width="3" height="24" fill="currentColor"/>
                    <rect x="95" y="0" width="2" height="24" fill="currentColor"/>
                </svg>
            </div>
            <div class="text-[10px] font-mono font-bold text-slate-500 tracking-widest">
                * {{ $transaksi->no_faktur }} *
            </div>
        </div>

        {{-- Footer Struk --}}
        <div class="text-center pt-2 text-[10px] text-slate-500 space-y-1">
            <div class="font-extrabold text-emerald-800 bg-emerald-50 py-1 rounded-lg border border-emerald-200/80">
                <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> STATUS: LUNAS &bull; STOK DIPOTONG
            </div>
            <div class="pt-1.5 font-bold text-slate-700">*** TERIMA KASIH ATAS KUNJUNGAN ANDA ***</div>
            <div>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</div>
            <div class="text-slate-400 font-mono">kopma_24430056 &bull; Annisa Choirunnisa</div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function printThermal() {
        document.getElementById('receiptWrapper').classList.remove('a4-mode');
        window.print();
    }

    function printA4() {
        document.getElementById('receiptWrapper').classList.add('a4-mode');
        window.print();
    }

    // Auto-print receipt saat transaksi kasir baru saja diselesaikan
    @if(isset($autoPrint) && $autoPrint)
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });
    @endif
</script>
@endpush
