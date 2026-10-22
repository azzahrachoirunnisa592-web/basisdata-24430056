@extends('layouts.app')

@section('title', 'Kasir POS — KOMPA')
@section('header_title', 'Kasir POS')

@push('styles')
<style>
    .pos-screen { background: linear-gradient(135deg, #064e3b 0%, #022c22 100%); }
    .pos-lcd { font-family: 'Courier New', Courier, monospace; letter-spacing: -0.5px; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    
    @keyframes pulseSuccess {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    .pulse-qris { animation: pulseSuccess 2s infinite ease-in-out; }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-slide-up { animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    @keyframes bounceIn {
        0%   { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.15); opacity: 1; }
        100% { transform: scale(1); }
    }
    .bounce-in { animation: bounceIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    .pay-tab { cursor: pointer; transition: all 0.2s ease; }
    .pay-tab.active { box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35); }

    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
    .checkmark-path { stroke-dasharray: 60; stroke-dashoffset: 60; animation: drawCheck 0.6s 0.2s ease forwards; }

    .badge-ewallet {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .badge-ewallet:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="h-full flex flex-col space-y-3">

    {{-- BAR SHORTCUT KASIR --}}
    <div class="bg-white px-4 py-2.5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-wrap items-center justify-between gap-2 text-xs">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                <i class="fa-solid fa-cash-register text-emerald-600"></i> Kasir Koperasi KOPMA
            </span>
            <span class="text-slate-300">|</span>
            <span class="text-slate-500 font-mono text-[11px]">Faktur: <b class="text-emerald-700" id="currentFakturNo">{{ $noFakturBaru }}</b></span>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <button type="button" onclick="focusBarcode()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded-lg font-mono text-[11px] flex items-center gap-1 transition-all">
                <b class="bg-slate-800 text-white px-1.5 py-0.5 rounded text-[10px]">F1</b> Scan / Cari Barang
            </button>
            <button type="button" onclick="openMemberSearch()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded-lg font-mono text-[11px] flex items-center gap-1 transition-all">
                <b class="bg-emerald-700 text-white px-1.5 py-0.5 rounded text-[10px]">F2</b> Cari Member (HP/Nama)
            </button>
            <button type="button" onclick="setCash('exact')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded-lg font-mono text-[11px] flex items-center gap-1 transition-all">
                <b class="bg-blue-700 text-white px-1.5 py-0.5 rounded text-[10px]">F3</b> Uang Pas
            </button>
            <button type="button" onclick="clearCart()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1 rounded-lg font-mono text-[11px] flex items-center gap-1 transition-all">
                <b class="bg-rose-700 text-white px-1.5 py-0.5 rounded text-[10px]">F4</b> Reset
            </button>
        </div>
        <div class="text-[11px] text-slate-500 flex items-center gap-2">
            <i class="fa-solid fa-user-circle text-emerald-600"></i>
            <span>Kasir: <b class="text-slate-800">{{ $kasir ? $kasir->name : 'Kasir Utama (24430056)' }}</b></span>
        </div>
    </div>

    {{-- WORKSPACE KASIR (2 KOLOM) --}}
    <div class="flex-1 flex flex-col lg:flex-row gap-4 min-h-0 overflow-hidden">

        {{-- KOLOM KIRI: KERANJANG (55%) --}}
        <div class="lg:w-7/12 flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden h-full">

            {{-- Pencarian Member --}}
            <div class="p-3.5 bg-slate-50/80 border-b border-slate-200/80 flex-shrink-0">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <label class="text-[11px] font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-id-card text-emerald-600"></i> Data Pembeli / Member Koperasi
                    </label>
                    <button type="button" onclick="setPelangganUmum()" class="text-[11px] font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1 transition-colors">
                        <i class="fa-solid fa-user-group text-slate-400"></i> Pelanggan Umum
                    </button>
                </div>

                {{-- Member Box Terpilih --}}
                <div id="memberSelectedBox" class="hidden p-2.5 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-900 text-xs" id="activeMemberName">-</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-200/80 text-emerald-900" id="activeMemberNim">NIM</span>
                            </div>
                            <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
                                <span class="font-mono text-emerald-700 font-semibold" id="activeMemberHp"><i class="fa-solid fa-phone text-[10px] mr-1"></i>-</span>
                                <span class="text-slate-300">•</span>
                                <span id="activeMemberJurusan" class="text-slate-500 text-[10px]">-</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="clearMember()" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-100/50 px-2.5 py-1.5 rounded-xl transition-all">
                        <i class="fa-solid fa-user-xmark mr-1"></i> Ganti
                    </button>
                </div>

                {{-- Input Cari Member --}}
                <div id="memberSearchBox" class="relative">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" id="memberSearchInput" autocomplete="off"
                               placeholder="Cari Member via Nama atau Nomor HP (contoh: 0812... atau Siti)... [F2]"
                               class="w-full pl-9 pr-24 py-2 text-xs rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                        <div class="absolute right-2 top-1/2 -translate-y-1/2">
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">F2</span>
                        </div>
                    </div>
                    <div id="memberDropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white rounded-2xl shadow-xl border border-slate-200/90 z-30 max-h-56 overflow-y-auto custom-scrollbar"></div>
                </div>
            </div>

            {{-- Monitor Layar LCD Hijau Kasir --}}
            <div class="pos-screen px-6 py-4 text-white flex items-center justify-between border-y border-emerald-900 shadow-inner flex-shrink-0">
                <div>
                    <div class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fa-solid fa-display"></i> TOTAL TAGIHAN BELANJA
                    </div>
                    <div class="pos-lcd text-3xl sm:text-4xl font-black text-emerald-300 mt-0.5 tracking-tight" id="displayTotal">Rp 0</div>
                </div>
                <div class="text-right">
                    <div class="text-[11px] text-emerald-200/70 uppercase tracking-wider font-semibold">Total Barang</div>
                    <div class="text-lg font-black text-white" id="displayQty">0 Pcs</div>
                    <div class="text-[10px] text-emerald-300 font-mono" id="displayItems">0 Baris Item</div>
                </div>
            </div>

            {{-- Keranjang Belanja Item List --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-2 min-h-0 bg-slate-50/40" id="cartItemsList">
                <div id="emptyCartView" class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-400">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-300 text-2xl mb-3 shadow-inner">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div class="font-extrabold text-slate-700 text-sm">Keranjang Belanja Masih Kosong</div>
                    <div class="text-xs text-slate-400 max-w-xs mt-1">Pindai barcode barang atau klik produk pada katalog di sebelah kanan untuk memulai transaksi.</div>
                </div>
            </div>

            {{-- Ringkasan Diskon & Total Bersih --}}
            <div class="p-4 bg-white border-t border-slate-200 space-y-3 flex-shrink-0">
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div class="space-y-1.5 text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal Belanja:</span>
                            <span class="font-bold text-slate-800" id="textSubtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Diskon Potongan:</span>
                            <div class="flex items-center gap-1">
                                <span class="text-[11px] text-slate-400 font-mono">Rp</span>
                                <input type="number" id="inputDiskon" min="0" value="0" oninput="calculatePos()"
                                       class="w-24 text-right py-1 px-2 text-xs font-bold rounded-lg border border-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1.5 text-slate-600 border-l border-slate-100 pl-4">
                        <div class="flex justify-between items-center text-emerald-800">
                            <span class="font-bold">Total Bersih:</span>
                            <span class="text-sm font-extrabold text-emerald-700" id="textTotalClean">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Metode Bayar:</span>
                            <span class="font-bold text-slate-800 uppercase px-2 py-0.5 rounded bg-slate-100" id="badgeMetode">Tunai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: KATALOG & PANEL BAYAR (45%) --}}
        <div class="lg:w-5/12 flex flex-col space-y-3 h-full overflow-hidden">

            {{-- Barcode Scanner & Pencarian Cepat Barang --}}
            <div class="bg-white p-3 rounded-2xl border border-slate-200/90 shadow-sm flex-shrink-0">
                <div class="relative">
                    <i class="fa-solid fa-barcode absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="barcodeInput" autofocus autocomplete="off"
                           placeholder="Scan Barcode / Ketik Kode Produk / Nama Barang (Tekan Enter)... [F1]"
                           class="w-full pl-10 pr-24 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 font-mono font-bold text-slate-800">
                    <div class="absolute right-2 top-1/2 -translate-y-1/2">
                        <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-1.5 py-0.5 rounded">Enter</span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 mt-2.5 text-[11px] custom-scrollbar">
                    <button type="button" onclick="filterCategory('all')"
                            class="cat-pill active px-3 py-1 rounded-xl font-bold bg-emerald-600 text-white transition-all whitespace-nowrap shadow-sm" data-category="all">
                        Semua
                    </button>
                    @foreach($kategori as $kat)
                    <button type="button" onclick="filterCategory('{{ $kat->id_kategori }}')"
                            class="cat-pill px-3 py-1 rounded-xl font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all whitespace-nowrap" data-category="{{ $kat->id_kategori }}">
                        {{ $kat->nama_kategori }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Grid Katalog Produk --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 min-h-[140px]">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5" id="productGrid">
                    @foreach($barang as $item)
                    <div class="product-item bg-white p-3 rounded-2xl border border-slate-200/80 hover:border-emerald-500 hover:shadow-md transition-all flex flex-col justify-between cursor-pointer group {{ $item->stok <= 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                         data-code="{{ $item->kode_barang }}" data-name="{{ $item->nama_barang }}"
                         data-price="{{ $item->harga_jual }}" data-stock="{{ $item->stok }}"
                         data-category="{{ $item->id_kategori }}"
                         onclick="addProductToCart('{{ $item->kode_barang }}', '{{ addslashes($item->nama_barang) }}', {{ $item->harga_jual }}, {{ $item->stok }})">
                        <div>
                            <div class="flex items-center justify-between gap-1 mb-1.5">
                                <span class="font-mono text-[10px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">{{ $item->kode_barang }}</span>
                                <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-full {{ $item->stok > 10 ? 'bg-emerald-50 text-emerald-700' : ($item->stok > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                    Stok: {{ $item->stok }}
                                </span>
                            </div>
                            <div class="font-extrabold text-slate-800 text-xs line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ $item->nama_barang }}</div>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                            <div class="font-black text-slate-900 text-xs">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</div>
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center text-xs transition-colors">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- PANEL PEMBAYARAN INTERAKTIF --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md p-4 space-y-3.5 flex-shrink-0">

                {{-- 1. TAB PILIHAN METODE PEMBAYARAN --}}
                <div class="grid grid-cols-3 gap-1.5">
                    <button type="button" id="tabTunai" onclick="setPaymentMethod('tunai')"
                            class="pay-tab active py-2.5 px-2 rounded-xl font-extrabold text-[11px] sm:text-xs bg-emerald-600 text-white flex items-center justify-center gap-1 shadow-sm transition-all">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <span>Tunai</span>
                    </button>
                    <button type="button" id="tabQris" onclick="setPaymentMethod('qris')"
                            class="pay-tab py-2.5 px-2 rounded-xl font-extrabold text-[11px] sm:text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center gap-1 transition-all">
                        <i class="fa-solid fa-qrcode text-emerald-600"></i>
                        <span>QRIS</span>
                    </button>
                    <button type="button" id="tabVa" onclick="setPaymentMethod('va')"
                            class="pay-tab py-2.5 px-2 rounded-xl font-extrabold text-[11px] sm:text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center gap-1 transition-all">
                        <i class="fa-solid fa-building-columns text-blue-600"></i>
                        <span>Bank VA</span>
                    </button>
                </div>

                {{-- 2. FORM INPUT TUNAI & KALKULASI KEMBALIAN (Default Ditampilkan) --}}
                <div id="sectionTunai" class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-extrabold text-slate-700 uppercase flex items-center gap-1">
                            <i class="fa-solid fa-wallet text-emerald-600"></i> Nominal Uang Diterima (Rp)
                        </label>
                        <span class="text-[10px] font-bold text-slate-400">Pecahan Cepat</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-extrabold text-slate-400 text-xs">Rp</span>
                        <input type="number" id="inputCash" min="0" placeholder="0" oninput="calculatePos()"
                               class="w-full pl-10 pr-4 py-2.5 text-base font-black text-slate-900 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-right">
                    </div>
                    <div class="grid grid-cols-6 gap-1">
                        <button type="button" onclick="setCash('exact')" class="py-1 px-1 text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg hover:bg-emerald-100">Pas (F3)</button>
                        <button type="button" onclick="setCash(10000)"  class="py-1 px-1 text-[10px] font-bold bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100">10k</button>
                        <button type="button" onclick="setCash(20000)"  class="py-1 px-1 text-[10px] font-bold bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100">20k</button>
                        <button type="button" onclick="setCash(50000)"  class="py-1 px-1 text-[10px] font-bold bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100">50k</button>
                        <button type="button" onclick="setCash(100000)" class="py-1 px-1 text-[10px] font-bold bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100">100k</button>
                        <button type="button" onclick="setCash(200000)" class="py-1 px-1 text-[10px] font-bold bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100">200k</button>
                    </div>
                    <div class="p-2.5 rounded-xl border flex items-center justify-between" id="changeBox">
                        <span class="text-xs font-bold text-slate-600">Kembalian:</span>
                        <span class="text-base font-black text-slate-900" id="textChange">Rp 0</span>
                    </div>
                </div>

                {{-- 3. CONTAINER BARCODE QRIS DINAMIS & BADGE E-WALLET (Muncul saat QRIS diklik) --}}
                <div id="sectionQris" class="hidden space-y-3 p-3.5 rounded-2xl bg-slate-900 text-white text-xs border border-slate-800 transition-all">
                    {{-- Header QRIS & Badges E-Wallet --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-2 border-b border-white/10 gap-2">
                        <div class="flex items-center gap-1.5 font-black text-xs text-emerald-400">
                            <i class="fa-solid fa-qrcode text-sm"></i> QRIS KOPMA RESMI
                        </div>
                        <div class="flex flex-wrap items-center gap-1">
                            <span class="badge-ewallet px-2 py-0.5 rounded-md bg-[#118EEA] text-white font-extrabold text-[9px] shadow-sm">DANA</span>
                            <span class="badge-ewallet px-2 py-0.5 rounded-md bg-[#4C3494] text-white font-extrabold text-[9px] shadow-sm">OVO</span>
                            <span class="badge-ewallet px-2 py-0.5 rounded-md bg-[#00AED6] text-white font-extrabold text-[9px] shadow-sm">GoPay</span>
                            <span class="badge-ewallet px-2 py-0.5 rounded-md bg-[#EE4D2D] text-white font-extrabold text-[9px] shadow-sm">ShopeePay</span>
                            <span class="badge-ewallet px-2 py-0.5 rounded-md bg-emerald-700 text-white font-extrabold text-[9px] shadow-sm">M-Banking</span>
                        </div>
                    </div>

                    {{-- QR Code Dinamis --}}
                    <div class="bg-white p-3 rounded-2xl flex flex-col items-center justify-center text-slate-800 shadow-md pulse-qris">
                        <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Pindai Kode QRIS untuk Bayar</div>
                        <div class="relative p-2 bg-slate-50 border-2 border-dashed border-emerald-500 rounded-xl">
                            <img id="qrisDirectImage"
                                 src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=KOPMA-QRIS-{{ $noFakturBaru }}"
                                 alt="Kode QRIS" class="w-36 h-36 rounded-lg shadow-sm bg-white p-1.5">
                            <div class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 bg-emerald-600 text-white font-extrabold text-[9px] px-2.5 py-0.5 rounded-full shadow-md flex items-center gap-1 whitespace-nowrap">
                                <i class="fa-solid fa-shield-halved"></i> QRIS TERVERIFIKASI
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <div class="text-[10px] text-slate-400 uppercase font-semibold">Total Tagihan QRIS</div>
                            <div class="text-xl font-black text-emerald-700" id="qrisTagihanText">Rp 0</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 font-medium">Buka aplikasi e-wallet atau mobile banking lalu scan kode QR di atas</div>
                        </div>
                    </div>

                    {{-- Status Verifikasi QRIS --}}
                    <div id="qrisVerificationStatus" class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-center text-xs">
                        <div class="flex items-center justify-center gap-2 text-amber-300 font-bold text-[11px]">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                            <span id="qrisStatusLabel">Menunggu Pembayaran dari Pelanggan...</span>
                        </div>
                    </div>

                    {{-- Aksi QRIS --}}
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <button type="button" id="btnVerifyQris" onclick="verifyQrisPayment()"
                                class="py-2.5 px-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs flex items-center justify-center gap-1.5 shadow-md transition-all">
                            <i class="fa-solid fa-arrows-rotate" id="qrisSpinIcon"></i>
                            <span>Cek Status / Sukses</span>
                        </button>
                        <button type="button" id="btnEnlargeQris" onclick="openEnlargedQris()"
                                class="py-2.5 px-2 rounded-xl bg-white/10 hover:bg-white/20 text-slate-200 font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                            <i class="fa-solid fa-expand"></i>
                            <span>Perbesar QR</span>
                        </button>
                    </div>
                </div>

                {{-- 3B. CONTAINER BANK VIRTUAL ACCOUNT (VA) (Muncul saat VA diklik) --}}
                <div id="sectionVa" class="hidden space-y-3 p-3.5 rounded-2xl bg-slate-900 text-white text-xs border border-slate-800 transition-all">
                    <div class="flex items-center justify-between pb-2 border-b border-white/10">
                        <div class="flex items-center gap-1.5 font-black text-xs text-blue-400">
                            <i class="fa-solid fa-building-columns text-sm"></i> Virtual Account Bank
                        </div>
                        <div class="flex items-center gap-1 text-[9px]">
                            <span class="px-2 py-0.5 rounded bg-blue-900/60 text-blue-300 font-bold border border-blue-700/50">OTOMATIS LUNAS</span>
                        </div>
                    </div>

                    {{-- Pilihan Bank VA --}}
                    <div class="grid grid-cols-4 gap-1.5 text-center">
                        <button type="button" onclick="selectBankVa('BCA', '88082443005601')" id="btnVaBca" class="va-btn active p-2 rounded-xl bg-blue-600 border border-blue-400 text-white font-black text-[10px] shadow-sm">
                            BCA VA
                        </button>
                        <button type="button" onclick="selectBankVa('Mandiri', '89082443005602')" id="btnVaMandiri" class="va-btn p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10 font-bold text-[10px]">
                            Mandiri
                        </button>
                        <button type="button" onclick="selectBankVa('BRI', '88882443005603')" id="btnVaBri" class="va-btn p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10 font-bold text-[10px]">
                            BRI VA
                        </button>
                        <button type="button" onclick="selectBankVa('BNI', '88092443005604')" id="btnVaBni" class="va-btn p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10 font-bold text-[10px]">
                            BNI VA
                        </button>
                    </div>

                    {{-- Box Nomor VA & Total --}}
                    <div class="bg-white/5 border border-white/10 p-3 rounded-2xl space-y-2.5">
                        <div class="flex justify-between items-center text-[11px]">
                            <span class="text-slate-400 font-medium">Bank Tujuan Transfer:</span>
                            <span class="font-extrabold text-blue-300" id="vaBankName">BCA Virtual Account</span>
                        </div>
                        <div class="bg-black/40 p-2.5 rounded-xl border border-white/10 flex items-center justify-between">
                            <div>
                                <div class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Nomor Virtual Account</div>
                                <div class="font-mono font-black text-sm text-emerald-400 tracking-wider" id="vaNumberDisplay">88082443005601</div>
                            </div>
                            <button type="button" onclick="copyVaNumber()" class="px-2.5 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[10px] font-bold flex items-center gap-1 transition-all">
                                <i class="fa-solid fa-copy"></i> Salin
                            </button>
                        </div>
                        <div class="flex justify-between items-center text-xs pt-0.5">
                            <span class="text-slate-400">Total Tagihan Transfer:</span>
                            <span class="font-black text-emerald-400 text-sm" id="vaTagihanText">Rp 0</span>
                        </div>
                    </div>

                    <div id="vaVerificationStatus" class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-center text-xs">
                        <div class="flex items-center justify-center gap-2 text-blue-300 font-bold text-[11px]">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                            <span id="vaStatusLabel">Menunggu Pembayaran Transfer Pelanggan...</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <button type="button" id="btnVerifyVa" onclick="verifyVaPayment()"
                                class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs flex items-center justify-center gap-1.5 shadow-md transition-all">
                            <i class="fa-solid fa-arrows-rotate" id="vaSpinIcon"></i>
                            <span>Simulasi / Cek Pembayaran VA Lunas</span>
                        </button>
                    </div>
                </div>

                {{-- 4. FORM HIDDEN & TOMBOL PROSES PEMBAYARAN / BAYAR SEKARANG --}}
                <form action="{{ route('pos.store') }}" method="POST" id="mainPosForm">
                    @csrf
                    <input type="hidden" name="no_faktur" value="{{ $noFakturBaru }}">
                    <input type="hidden" name="id_anggota" id="hiddenIdAnggota" value="">
                    <input type="hidden" name="diskon" id="hiddenDiskon" value="0">
                    <input type="hidden" name="nominal_uang" id="hiddenNominalUang" value="0">
                    <input type="hidden" name="metode_pembayaran" id="hiddenMetode" value="tunai">
                    <div id="hiddenCartInputs"></div>

                    {{-- Tombol Utama: Proses Pembayaran / Bayar Sekarang --}}
                    <button type="button" id="btnProsesPembayaran"
                            onclick="handleProsesPembayaran()"
                            class="w-full py-4 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-xl shadow-emerald-600/30 flex items-center justify-center gap-2.5 transition-all transform active:scale-[0.98] cursor-pointer">
                        <i class="fa-solid fa-cash-register text-lg"></i>
                        <span id="btnSubmitText">Proses Pembayaran / Bayar Sekarang [Enter]</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL QRIS LAYAR PENUH (POPUP PERBESAR QR) --}}
<div id="qrisModal" class="hidden fixed inset-0 bg-black/75 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center space-y-4 shadow-2xl modal-slide-up">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="font-black text-sm text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-qrcode text-emerald-600"></i> QRIS KOPMA 24430056
            </div>
            <button type="button" onclick="closeQrisModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="space-y-1">
            <div class="text-xs text-slate-400">Total Belanja Kasir KOPMA</div>
            <div class="text-3xl font-black text-emerald-700" id="qrisModalTotal">Rp 0</div>
            <div class="text-[11px] font-mono text-slate-500">Faktur: {{ $noFakturBaru }}</div>
        </div>
        <div class="p-4 bg-slate-50 border-2 border-dashed border-emerald-400 rounded-2xl flex flex-col items-center justify-center">
            <img id="qrisModalImg"
                 src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=KOPMA-QRIS-{{ $noFakturBaru }}"
                 alt="QRIS Code" class="w-52 h-52 rounded-xl shadow-md bg-white p-2">
            <div class="flex flex-wrap items-center justify-center gap-1.5 mt-3">
                <span class="px-2 py-0.5 rounded bg-[#118EEA] text-white font-extrabold text-[9px]">DANA</span>
                <span class="px-2 py-0.5 rounded bg-[#4C3494] text-white font-extrabold text-[9px]">OVO</span>
                <span class="px-2 py-0.5 rounded bg-[#00AED6] text-white font-extrabold text-[9px]">GoPay</span>
                <span class="px-2 py-0.5 rounded bg-[#EE4D2D] text-white font-extrabold text-[9px]">ShopeePay</span>
            </div>
        </div>
        <div class="space-y-2">
            <button type="button" onclick="verifyQrisPayment(); closeQrisModal();"
                    class="w-full py-3 rounded-xl font-extrabold text-xs text-white bg-emerald-600 hover:bg-emerald-700 shadow-md flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Konfirmasi Pembayaran Berhasil
            </button>
            <button type="button" onclick="closeQrisModal()"
                    class="w-full py-2 rounded-xl font-bold text-xs text-slate-500 hover:bg-slate-100">
                Kembali ke Kasir
            </button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI TRANSAKSI BERHASIL & CETAK STRUK --}}
<div id="successModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl modal-slide-up overflow-hidden">
        {{-- Header Sukses Bergradasi Emerald --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-700 p-6 text-center text-white relative">
            <div class="flex items-center justify-center mb-3">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center bounce-in shadow-inner">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="22" stroke="white" stroke-width="3" fill="none"/>
                        <polyline class="checkmark-path" points="13,25 21,33 36,16" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
            </div>
            <div class="font-black text-xl tracking-tight">TRANSAKSI BERHASIL!</div>
            <div class="text-emerald-100 text-xs mt-1 font-medium">Pembayaran diterima & diproses oleh sistem KOPMA</div>
        </div>

        {{-- Isi Detail Transaksi --}}
        <div class="p-5 space-y-4">
            <div class="bg-slate-50 rounded-2xl p-3 flex items-center justify-between text-xs">
                <div>
                    <div class="text-slate-400 font-medium">No. Faktur</div>
                    <div class="font-black text-slate-800 font-mono" id="modalFakturNo">{{ $noFakturBaru }}</div>
                </div>
                <div class="text-right">
                    <div class="text-slate-400 font-medium">Waktu Transaksi</div>
                    <div class="font-bold text-slate-700" id="modalWaktu">-</div>
                </div>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-slate-500 text-xs">Pembeli / Member</span>
                    <span class="font-extrabold text-slate-800 text-xs" id="modalMemberName">Pelanggan Umum</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-slate-500 text-xs">Total Tagihan Belanja</span>
                    <span class="font-black text-emerald-700 text-base" id="modalTotalBayar">Rp 0</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100">
                    <span class="text-slate-500 text-xs">Metode Pembayaran</span>
                    <span class="font-extrabold px-2.5 py-0.5 rounded-full text-xs" id="modalBadgeMetode">-</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100" id="rowTunaiDiterima">
                    <span class="text-slate-500 text-xs">Nominal Uang Diterima</span>
                    <span class="font-bold text-slate-800 text-xs" id="modalUangDiterima">Rp 0</span>
                </div>
                <div class="flex justify-between items-center py-1.5 border-b border-slate-100" id="rowKembalian">
                    <span class="text-slate-500 text-xs">Kembalian</span>
                    <span class="font-black text-emerald-700 text-base" id="modalKembalian">Rp 0</span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-slate-500 text-xs">Kasir Bertugas</span>
                    <span class="font-bold text-slate-700 text-xs">{{ $kasir ? $kasir->name : 'Kasir Utama (24430056)' }}</span>
                </div>
            </div>

            {{-- Ringkasan Item Barang --}}
            <div class="bg-emerald-50/70 border border-emerald-100 rounded-xl p-3 text-xs">
                <div class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                    <span>Ringkasan Barang Dibeli</span>
                    <span id="modalItemCount" class="font-mono text-emerald-600">0 Item</span>
                </div>
                <div id="modalItemSummary" class="text-slate-700 space-y-1 max-h-28 overflow-y-auto custom-scrollbar pr-1"></div>
            </div>

            {{-- Tombol Aksi: Cetak Struk Otomatis & Konfirmasi Simpan --}}
            <div class="grid grid-cols-2 gap-2.5 pt-1">
                <button type="button" id="btnModalPrint" onclick="printReceipt()"
                        class="py-3 rounded-xl font-extrabold text-xs text-white bg-blue-600 hover:bg-blue-700 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Struk Otomatis</span>
                </button>
                <button type="button" id="btnModalConfirm" onclick="submitFinalTransaction()"
                        class="py-3 rounded-xl font-extrabold text-xs text-white bg-emerald-600 hover:bg-emerald-700 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Simpan & Selesai</span>
                </button>
            </div>
            <div class="text-center">
                <button type="button" onclick="closeSuccessModal()" class="text-xs text-slate-400 hover:text-slate-600 font-semibold">
                    Kembali Periksa Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ============================================================
    // 1. WEB AUDIO API SYNTHESIZER (BEEP & SUCCESS TUNE)
    // ============================================================
    const AudioCtxClass = window.AudioContext || window.webkitAudioContext;
    let audioCtx = null;

    function getAudioCtx() {
        if (!audioCtx) {
            audioCtx = new AudioCtxClass();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    // Suara Beep Scan Barcode
    function playScanBeep() {
        try {
            const ctx = getAudioCtx();
            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(1760, now); // A6
            gain.gain.setValueAtTime(0.12, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.08);

            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.08);
        } catch (e) {
            console.log('Audio note error:', e);
        }
    }

    // Suara Melodi Transaksi Sukses (Chime Nada Tiga Tingkat)
    function playTone(ctx, freq, t, dur, vol) {
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'triangle';
        osc.frequency.setValueAtTime(freq, t);
        gain.gain.setValueAtTime(vol, t);
        gain.gain.exponentialRampToValueAtTime(0.001, t + dur);
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start(t);
        osc.stop(t + dur);
    }

    function playPaymentSuccessSound() {
        try {
            const ctx = getAudioCtx();
            const now = ctx.currentTime;
            // Chime C5 - E5 - G5 - C6
            playTone(ctx, 523.25, now,        0.12, 0.20);
            playTone(ctx, 659.25, now + 0.09, 0.12, 0.22);
            playTone(ctx, 783.99, now + 0.18, 0.15, 0.25);
            playTone(ctx, 1046.50, now + 0.27, 0.40, 0.30);
        } catch (e) {
            console.log('Audio success error:', e);
        }
    }

    // ============================================================
    // 2. STATE APLIKASI KASIR
    // ============================================================
    const allMembers   = @json($anggota);
    const noFaktur     = '{{ $noFakturBaru }}';
    let cart           = {};
    let selectedMember = null;
    let paymentMethod  = 'tunai'; // 'tunai' | 'qris'
    let isQrisVerified = false;

    // ============================================================
    // 3. INISIALISASI EVENT LISTENERS (DOMContentLoaded)
    // ============================================================
    document.addEventListener('DOMContentLoaded', function () {

        // Tombol Tab Pilihan Metode Pembayaran
        const tabTunai = document.getElementById('tabTunai');
        const tabQris  = document.getElementById('tabQris');
        if (tabTunai) tabTunai.addEventListener('click', function () { setPaymentMethod('tunai'); });
        if (tabQris)  tabQris.addEventListener('click',  function () { setPaymentMethod('qris');  });

        // Tombol Aksi QRIS
        const btnVerifyQris = document.getElementById('btnVerifyQris');
        const btnEnlargeQris = document.getElementById('btnEnlargeQris');
        if (btnVerifyQris) btnVerifyQris.addEventListener('click', verifyQrisPayment);
        if (btnEnlargeQris) btnEnlargeQris.addEventListener('click', openEnlargedQris);

        // Tombol Proses Pembayaran / Bayar Sekarang
        const btnProses = document.getElementById('btnProsesPembayaran');
        if (btnProses) btnProses.addEventListener('click', handleProsesPembayaran);

        // Keyboard Shortcuts Kasir
        document.addEventListener('keydown', function (e) {
            if (e.key === 'F1') { e.preventDefault(); focusBarcode(); }
            if (e.key === 'F2') { e.preventDefault(); openMemberSearch(); }
            if (e.key === 'F3') { e.preventDefault(); setCash('exact'); }
            if (e.key === 'F4') { e.preventDefault(); clearCart(); }
            if (e.key === 'Escape') {
                closeQrisModal();
                closeSuccessModal();
            }
            if (e.key === 'Enter') {
                const tag = document.activeElement ? document.activeElement.tagName : '';
                const activeId = document.activeElement ? document.activeElement.id : '';
                if (tag !== 'INPUT' || activeId === 'inputCash') {
                    e.preventDefault();
                    handleProsesPembayaran();
                }
            }
        });

        // Autocomplete Pencarian Member Koperasi
        const memberInput    = document.getElementById('memberSearchInput');
        const memberDropdown = document.getElementById('memberDropdown');

        if (memberInput && memberDropdown) {
            memberInput.addEventListener('input', function (e) {
                const q = e.target.value.toLowerCase().trim();
                if (!q) {
                    memberDropdown.classList.add('hidden');
                    return;
                }

                const hits = allMembers.filter(m =>
                    (m.nama       || '').toLowerCase().includes(q) ||
                    (m.no_hp      || '').toLowerCase().includes(q) ||
                    (m.nim        || '').toLowerCase().includes(q) ||
                    (m.id_anggota || '').toLowerCase().includes(q)
                );

                if (!hits.length) {
                    memberDropdown.innerHTML = `<div class="p-3 text-xs text-slate-400 text-center">Tidak ditemukan member: "<b>${escapeHtml(q)}</b>"</div>`;
                    memberDropdown.classList.remove('hidden');
                    return;
                }

                memberDropdown.innerHTML = hits.slice(0, 8).map(m => `
                    <div class="p-3 hover:bg-emerald-50 border-b border-slate-100 last:border-0 cursor-pointer flex items-center justify-between gap-3 text-xs"
                         onclick="selectMember('${m.id_anggota}')">
                        <div>
                            <div class="font-extrabold text-slate-800 flex items-center gap-1.5">
                                ${escapeHtml(m.nama)}
                                ${m.nim ? `<span class="text-[10px] font-mono bg-slate-100 px-1.5 py-0.5 rounded">${m.nim}</span>` : ''}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                <i class="fa-solid fa-phone text-[10px] mr-1 text-emerald-600"></i>${m.no_hp || '-'} &bull; ${escapeHtml(m.jurusan || 'KOPMA')}
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Pilih</span>
                    </div>`).join('');
                memberDropdown.classList.remove('hidden');
            });

            document.addEventListener('click', function (e) {
                if (!memberInput.contains(e.target) && !memberDropdown.contains(e.target)) {
                    memberDropdown.classList.add('hidden');
                }
            });
        }

        // Barcode Scanner Listener
        const barcodeInput = document.getElementById('barcodeInput');
        if (barcodeInput) {
            barcodeInput.addEventListener('keydown', function (e) {
                if (e.key !== 'Enter') return;
                e.preventDefault();
                const val = this.value.trim().toLowerCase();
                if (!val) return;

                let found = null;
                document.querySelectorAll('.product-item').forEach(card => {
                    if (found) return;
                    const code = (card.getAttribute('data-code') || '').toLowerCase();
                    const name = (card.getAttribute('data-name') || '').toLowerCase();
                    if (code === val || code.includes(val) || name.includes(val)) {
                        found = {
                            code: card.getAttribute('data-code'),
                            name: card.getAttribute('data-name'),
                            price: parseInt(card.getAttribute('data-price')),
                            stock: parseInt(card.getAttribute('data-stock'))
                        };
                    }
                });

                if (found) {
                    addProductToCart(found.code, found.name, found.price, found.stock);
                    this.value = '';
                } else {
                    alert('Produk "' + this.value + '" tidak ditemukan!');
                    this.select();
                }
            });
        }

        // Hitung awal
        calculatePos();
    });

    // ============================================================
    // 4. METODE PEMBAYARAN TOGGLE (TUNAI, QRIS, BANK VA)
    // ============================================================
    let selectedBankVaName = 'BCA';
    let currentVaNumber    = '88082443005601';
    let isVaVerified       = false;

    function setPaymentMethod(method) {
        paymentMethod = method;
        document.getElementById('hiddenMetode').value = method;

        const tabTunai = document.getElementById('tabTunai');
        const tabQris  = document.getElementById('tabQris');
        const tabVa    = document.getElementById('tabVa');
        const secTunai = document.getElementById('sectionTunai');
        const secQris  = document.getElementById('sectionQris');
        const secVa    = document.getElementById('sectionVa');
        const badgeM   = document.getElementById('badgeMetode');

        // Reset semua class tab ke inaktif
        const inActiveCls = 'pay-tab py-2.5 px-2 rounded-xl font-extrabold text-[11px] sm:text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 flex items-center justify-center gap-1 transition-all';
        const activeCls   = 'pay-tab active py-2.5 px-2 rounded-xl font-extrabold text-[11px] sm:text-xs bg-emerald-600 text-white flex items-center justify-center gap-1 shadow-sm transition-all';
        const activeVaCls = 'pay-tab active py-2.5 px-2 rounded-xl font-extrabold text-[11px] sm:text-xs bg-blue-600 text-white flex items-center justify-center gap-1 shadow-sm transition-all';

        tabTunai.className = inActiveCls;
        tabQris.className  = inActiveCls;
        if (tabVa) tabVa.className = inActiveCls;

        secTunai.classList.add('hidden');
        secQris.classList.add('hidden');
        if (secVa) secVa.classList.add('hidden');

        if (method === 'tunai') {
            tabTunai.className = activeCls;
            secTunai.classList.remove('hidden');
            badgeM.innerText = 'Tunai';
            badgeM.className = 'font-bold text-slate-800 uppercase px-2 py-0.5 rounded bg-slate-100';

            setTimeout(() => {
                const ic = document.getElementById('inputCash');
                if (ic) ic.focus();
            }, 100);
        } else if (method === 'qris') {
            tabQris.className = activeCls;
            secQris.classList.remove('hidden');
            badgeM.innerText = 'QRIS / Digital';
            badgeM.className = 'font-bold text-emerald-800 uppercase px-2 py-0.5 rounded bg-emerald-100';
        } else if (method === 'va') {
            if (tabVa) tabVa.className = activeVaCls;
            if (secVa) secVa.classList.remove('hidden');
            badgeM.innerText = 'Bank VA (' + selectedBankVaName + ')';
            badgeM.className = 'font-bold text-blue-800 uppercase px-2 py-0.5 rounded bg-blue-100';
        }

        calculatePos();
    }

    // Pilihan Bank Virtual Account
    function selectBankVa(bank, vaNumber) {
        selectedBankVaName = bank;
        currentVaNumber = vaNumber;
        document.getElementById('vaBankName').innerText = bank + ' Virtual Account';
        document.getElementById('vaNumberDisplay').innerText = vaNumber;

        document.querySelectorAll('.va-btn').forEach(b => {
            b.className = 'va-btn p-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:bg-white/10 font-bold text-[10px]';
        });
        const activeBtn = document.getElementById('btnVa' + bank);
        if (activeBtn) {
            activeBtn.className = 'va-btn active p-2 rounded-xl bg-blue-600 border border-blue-400 text-white font-black text-[10px] shadow-sm';
        }
        document.getElementById('badgeMetode').innerText = 'Bank VA (' + bank + ')';
        playScanBeep();
    }

    function copyVaNumber() {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(currentVaNumber).then(() => {
                alert('Nomor Virtual Account ' + currentVaNumber + ' berhasil disalin!');
            });
        } else {
            alert('Nomor Virtual Account: ' + currentVaNumber);
        }
    }

    function verifyVaPayment() {
        const keys = Object.keys(cart);
        if (!keys.length) {
            alert('Pilih barang terlebih dahulu untuk transaksi!');
            return;
        }

        const btn = document.getElementById('btnVerifyVa');
        const icon = document.getElementById('vaSpinIcon');
        const statusBox = document.getElementById('vaVerificationStatus');
        const totalText = document.getElementById('textTotalClean').innerText;

        if (btn) btn.disabled = true;
        if (icon) icon.classList.add('fa-spin');
        const label = document.getElementById('vaStatusLabel');
        if (label) label.innerText = 'Memeriksa mutasi Virtual Account ' + selectedBankVaName + '...';

        setTimeout(function () {
            isVaVerified = true;
            if (btn) btn.disabled = false;
            if (icon) icon.classList.remove('fa-spin');

            playPaymentSuccessSound();

            if (statusBox) {
                statusBox.className = 'p-2.5 rounded-xl bg-blue-500/20 border border-blue-400 text-center text-xs';
                statusBox.innerHTML = `
                    <div class="flex items-center justify-center gap-2 text-blue-300 font-black text-xs">
                        <i class="fa-solid fa-circle-check text-base text-blue-400"></i>
                        <span>TRANSFER VA BERHASIL & LUNAS (${totalText})</span>
                    </div>
                    <div class="text-[10px] text-blue-200/90 mt-0.5 font-medium">Rekening KOPMA menerima dana via ${selectedBankVaName} VA</div>
                `;
            }
            calculatePos();
        }, 600);
    }

    // Update Kode QRIS Secara Dinamis berdasarkan total belanja
    function updateDynamicQrisCode(total) {
        const payload  = encodeURIComponent('KOPMA_24430056;FAKTUR:' + noFaktur + ';TOTAL:' + total + ';STATUS:VALID');
        const urlSmall = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + payload;
        const urlLarge = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' + payload;

        const directImg = document.getElementById('qrisDirectImage');
        const modalImg  = document.getElementById('qrisModalImg');
        const tagihanTxt = document.getElementById('qrisTagihanText');
        const modalTotal = document.getElementById('qrisModalTotal');
        const vaTagihan  = document.getElementById('vaTagihanText');

        if (directImg) directImg.src = urlSmall;
        if (modalImg) modalImg.src = urlLarge;
        if (tagihanTxt) tagihanTxt.innerText = 'Rp ' + formatRupiah(total);
        if (modalTotal) modalTotal.innerText = 'Rp ' + formatRupiah(total);
        if (vaTagihan) vaTagihan.innerText = 'Rp ' + formatRupiah(total);
    }

    // Verifikasi Pembayaran QRIS Dinamis
    function verifyQrisPayment() {
        const keys = Object.keys(cart);
        if (!keys.length) {
            alert('Pilih barang terlebih dahulu untuk melakukan transaksi!');
            return;
        }

        const btn = document.getElementById('btnVerifyQris');
        const icon = document.getElementById('qrisSpinIcon');
        const statusBox = document.getElementById('qrisVerificationStatus');
        const totalText = document.getElementById('textTotalClean').innerText;

        if (btn) btn.disabled = true;
        if (icon) icon.classList.add('fa-spin');
        const label = document.getElementById('qrisStatusLabel');
        if (label) label.innerText = 'Memverifikasi sinyal pembayaran QRIS...';

        setTimeout(function () {
            isQrisVerified = true;
            if (btn) btn.disabled = false;
            if (icon) icon.classList.remove('fa-spin');

            playPaymentSuccessSound();

            if (statusBox) {
                statusBox.className = 'p-2.5 rounded-xl bg-emerald-500/20 border border-emerald-400 text-center text-xs';
                statusBox.innerHTML = `
                    <div class="flex items-center justify-center gap-2 text-emerald-300 font-black text-xs">
                        <i class="fa-solid fa-circle-check text-base text-emerald-400"></i>
                        <span>PEMBAYARAN QRIS BERHASIL & LUNAS (${totalText})</span>
                    </div>
                    <div class="text-[10px] text-emerald-200/90 mt-0.5 font-medium">Terverifikasi otomatis via DANA / GoPay / M-Banking</div>
                `;
            }
            calculatePos();
        }, 600);
    }

    function openEnlargedQris() { document.getElementById('qrisModal').classList.remove('hidden'); }
    function closeQrisModal()   { document.getElementById('qrisModal').classList.add('hidden');    }

    // ============================================================
    // 5. PENANGANAN KLIK TOMBOL "PROSES PEMBAYARAN" / "BAYAR SEKARANG"
    // ============================================================
    function handleProsesPembayaran() {
        const keys = Object.keys(cart);

        // 1. Validasi Keranjang Belanja
        if (!keys.length) {
            alert('Keranjang belanja masih kosong! Silakan scan barcode atau pilih barang dari katalog terlebih dahulu.');
            focusBarcode();
            return;
        }

        // Hitung total belanja
        let subtotal = 0;
        keys.forEach(k => subtotal += cart[k].price * cart[k].qty);
        const diskon = parseInt(document.getElementById('inputDiskon').value) || 0;
        const total  = Math.max(0, subtotal - diskon);

        if (total <= 0) {
            alert('Total tagihan belanja harus lebih besar dari 0!');
            return;
        }

        // 2. Validasi Berdasarkan Metode
        if (paymentMethod === 'tunai') {
            let cashVal = parseInt(document.getElementById('inputCash').value) || 0;

            // Jika belum diisi sama sekali, otomatis jadikan Uang Pas
            if (cashVal <= 0) {
                setCash('exact');
                cashVal = total;
            }

            if (cashVal < total) {
                const kurang = total - cashVal;
                alert('Nominal uang tunai kurang Rp ' + formatRupiah(kurang) + '!\nSilakan masukkan nominal yang cukup atau klik tombol "Uang Pas (F3)".');
                const ic = document.getElementById('inputCash');
                if (ic) { ic.focus(); ic.select(); }
                return;
            }
        } else if (paymentMethod === 'qris') {
            isQrisVerified = true;
        } else if (paymentMethod === 'va') {
            isVaVerified = true;
        }

        // 3. Tampilkan Pop-Up Modal Konfirmasi Berhasil & Mainkan Audio
        openPaymentConfirmModal(total);
    }

    function openPaymentConfirmModal(total) {
        const keys = Object.keys(cart);
        let subtotal = 0;
        keys.forEach(k => subtotal += cart[k].price * cart[k].qty);
        const diskon = parseInt(document.getElementById('inputDiskon').value) || 0;
        if (typeof total === 'undefined') {
            total = Math.max(0, subtotal - diskon);
        }

        const cashVal = parseInt(document.getElementById('inputCash').value) || total;

        // Isi Data Modal
        document.getElementById('modalFakturNo').innerText   = noFaktur;
        document.getElementById('modalWaktu').innerText      = new Date().toLocaleTimeString('id-ID') + ' WIB';
        document.getElementById('modalMemberName').innerText = selectedMember ? (selectedMember.nama + (selectedMember.nim ? ' (' + selectedMember.nim + ')' : '')) : 'Pelanggan Umum';
        document.getElementById('modalTotalBayar').innerText = 'Rp ' + formatRupiah(total);

        // Badge & Rincian Nominal
        const badge     = document.getElementById('modalBadgeMetode');
        const rowTunai  = document.getElementById('rowTunaiDiterima');
        const rowKembal = document.getElementById('rowKembalian');

        if (paymentMethod === 'tunai') {
            badge.innerText = 'Tunai (Cash)';
            badge.className = 'font-extrabold px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-800';
            rowTunai.style.display = '';
            rowKembal.style.display = '';
            document.getElementById('modalUangDiterima').innerText = 'Rp ' + formatRupiah(cashVal);
            document.getElementById('modalKembalian').innerText    = 'Rp ' + formatRupiah(Math.max(0, cashVal - total));
        } else if (paymentMethod === 'qris') {
            badge.innerText = 'QRIS / Dompet Digital';
            badge.className = 'font-extrabold px-2.5 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800';
            rowTunai.style.display = 'none';
            rowKembal.style.display = 'none';
        } else {
            badge.innerText = 'Bank VA (' + selectedBankVaName + ')';
            badge.className = 'font-extrabold px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800';
            rowTunai.style.display = 'none';
            rowKembal.style.display = 'none';
        }

        // Ringkasan Item
        document.getElementById('modalItemCount').innerText = keys.length + ' Macam Barang';
        document.getElementById('modalItemSummary').innerHTML = keys.map(k => {
            const item = cart[k];
            return `
                <div class="flex justify-between items-center py-0.5">
                    <span class="truncate max-w-[65%] text-slate-700 font-medium">
                        ${escapeHtml(item.name)} <span class="text-emerald-700 font-bold">x${item.qty}</span>
                    </span>
                    <span class="font-bold text-slate-900 font-mono">Rp ${formatRupiah(item.price * item.qty)}</span>
                </div>
            `;
        }).join('');

        // Buka Pop-Up Modal
        document.getElementById('successModal').classList.remove('hidden');

        // Mainkan Efek Audio Suara "Beep" / Chime Berhasil
        playPaymentSuccessSound();
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
    }

    function submitFinalTransaction() {
        closeSuccessModal();
        document.getElementById('mainPosForm').submit();
    }

    // ============================================================
    // 6. CETAK STRUK / FAKTUR OTOMATIS
    // ============================================================
    function printReceipt() {
        const keys = Object.keys(cart);
        if (!keys.length) return;

        let subtotal = 0;
        keys.forEach(k => subtotal += cart[k].price * cart[k].qty);
        const diskon    = parseInt(document.getElementById('inputDiskon').value) || 0;
        const total     = Math.max(0, subtotal - diskon);
        const cashVal   = parseInt(document.getElementById('inputCash').value) || total;
        const kembalian = (paymentMethod === 'tunai') ? Math.max(0, cashVal - total) : 0;
        const kasirName = '{{ $kasir ? addslashes($kasir->name) : "Kasir Utama (24430056)" }}';
        const waktu     = new Date().toLocaleString('id-ID');
        const metode    = (paymentMethod === 'tunai') ? 'TUNAI (CASH)' : ((paymentMethod === 'qris') ? 'QRIS / DOMPET DIGITAL' : ('BANK VA (' + selectedBankVaName + ')'));
        const mName     = selectedMember ? selectedMember.nama : 'Pelanggan Umum';
        const mNim      = selectedMember ? (selectedMember.nim || selectedMember.id_anggota) : '';

        const rows = keys.map(k => {
            const item = cart[k];
            return `
                <tr>
                    <td style="padding:2px 4px;font-size:11px;">${escapeHtml(item.name)}</td>
                    <td style="padding:2px 4px;text-align:center;font-size:11px;">${item.qty}</td>
                    <td style="padding:2px 4px;text-align:right;font-size:11px;">${formatRupiah(item.price)}</td>
                    <td style="padding:2px 4px;text-align:right;font-size:11px;font-weight:700;">${formatRupiah(item.price * item.qty)}</td>
                </tr>
            `;
        }).join('');

        const html = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran — ${noFaktur}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Courier New', monospace; font-size:12px; color:#111; padding:12px; background:#fff; }
        .c { text-align:center; }
        .b { font-weight:bold; }
        .hr { border:none; border-top:1px dashed #777; margin:6px 0; }
        table { width:100%; border-collapse:collapse; }
        th { font-size:11px; text-align:left; border-bottom:1px dashed #777; padding:3px 4px; }
        .total td { font-weight:bold; font-size:13px; padding-top:4px; }
        @media print {
            body { padding:0; }
            @page { margin:4mm; size:80mm auto; }
        }
    </style>
</head>
<body>
    <div class="c b" style="font-size:15px;letter-spacing:1px;">KOPMA 24430056</div>
    <div class="c" style="font-size:11px;">KOPERASI MAHASISWA TERPADU</div>
    <div class="c" style="font-size:10px;color:#555;">Gedung Student Center Lt. 1 &bull; NIM: 24430056</div>
    <hr class="hr">
    <div style="font-size:11px;line-height:1.4;">
        <div>No. Faktur : <b>${noFaktur}</b></div>
        <div>Waktu      : ${waktu} WIB</div>
        <div>Kasir      : ${kasirName}</div>
        <div>Pembeli    : ${escapeHtml(mName)}${mNim ? ' (' + mNim + ')' : ''}</div>
    </div>
    <hr class="hr">
    <table>
        <thead>
            <tr>
                <th style="width:45%">Barang</th>
                <th style="width:10%;text-align:center">Qty</th>
                <th style="width:22%;text-align:right">Harga</th>
                <th style="width:23%;text-align:right">Sub</th>
            </tr>
        </thead>
        <tbody>${rows}</tbody>
    </table>
    <hr class="hr">
    <table>
        <tr><td>Subtotal</td><td style="text-align:right">Rp ${formatRupiah(subtotal)}</td></tr>
        ${diskon > 0 ? `<tr><td>Diskon Potongan</td><td style="text-align:right">- Rp ${formatRupiah(diskon)}</td></tr>` : ''}
        <tr class="total"><td>TOTAL BAYAR</td><td style="text-align:right">Rp ${formatRupiah(total)}</td></tr>
        <tr><td>Metode Bayar</td><td style="text-align:right"><b>${metode}</b></td></tr>
        ${paymentMethod === 'tunai' ? `
            <tr><td>Uang Diterima</td><td style="text-align:right">Rp ${formatRupiah(cashVal)}</td></tr>
            <tr><td>Kembalian</td><td style="text-align:right"><b>Rp ${formatRupiah(kembalian)}</b></td></tr>
        ` : (paymentMethod === 'qris' ? `
            <tr><td>Status QRIS</td><td style="text-align:right"><b>LUNAS (VERIFIED)</b></td></tr>
        ` : `
            <tr><td>No. VA Transfer</td><td style="text-align:right"><b>${currentVaNumber}</b></td></tr>
            <tr><td>Status Transfer</td><td style="text-align:right"><b>LUNAS (VERIFIED)</b></td></tr>
        `)}
    </table>
    <hr class="hr">
    <div class="c" style="font-size:10px;color:#555;margin-top:4px;">
        Terima kasih atas kunjungan Anda!<br>
        Barang yang sudah dibeli tidak dapat ditukar.
    </div>
    <div class="c" style="font-size:9px;color:#999;margin-top:4px;">
        kopma_24430056 &bull; Sistem POS v2.0
    </div>
</body>
</html>`;

        const pw = window.open('', '_blank', 'width=420,height=640,scrollbars=yes');
        if (!pw) {
            alert('Izinkan pop-up pada browser Anda untuk mencetak struk otomatis!');
            return;
        }
        pw.document.write(html);
        pw.document.close();
        pw.focus();
        setTimeout(function () { pw.print(); }, 400);
    }

    // ============================================================
    // 7. MANAJEMEN KERANJANG & KALKULASI REALTIME
    // ============================================================
    function addProductToCart(kode, name, price, maxStock) {
        if (maxStock <= 0) {
            alert('Maaf, stok untuk barang "' + name + '" sedang habis!');
            return;
        }
        playScanBeep();

        if (cart[kode]) {
            if (cart[kode].qty + 1 > maxStock) {
                alert('Jumlah belanja melebihi batas stok tersedia (' + maxStock + ')!');
                return;
            }
            cart[kode].qty++;
        } else {
            cart[kode] = { kode, name, price, qty: 1, maxStock };
        }
        isQrisVerified = false;
        renderCart();
    }

    function updateCartQty(kode, delta) {
        if (!cart[kode]) return;
        const nq = cart[kode].qty + delta;
        if (nq <= 0) {
            delete cart[kode];
        } else if (nq > cart[kode].maxStock) {
            alert('Jumlah belanja melebihi batas stok tersedia (' + cart[kode].maxStock + ')!');
            return;
        } else {
            playScanBeep();
            cart[kode].qty = nq;
        }
        isQrisVerified = false;
        renderCart();
    }

    function setDirectQty(kode, val) {
        if (!cart[kode]) return;
        let q = parseInt(val) || 0;
        if (q <= 0) {
            delete cart[kode];
        } else if (q > cart[kode].maxStock) {
            alert('Jumlah melebihi stok tersedia (' + cart[kode].maxStock + ')!');
            cart[kode].qty = cart[kode].maxStock;
        } else {
            cart[kode].qty = q;
        }
        isQrisVerified = false;
        renderCart();
    }

    function removeCartItem(kode) {
        delete cart[kode];
        isQrisVerified = false;
        renderCart();
    }

    function clearCart() {
        if (!Object.keys(cart).length) return;
        if (confirm('Kosongkan semua daftar belanjaan di keranjang?')) {
            cart = {};
            isQrisVerified = false;
            renderCart();
        }
    }

    function renderCart() {
        const container = document.getElementById('cartItemsList');
        const hidden    = document.getElementById('hiddenCartInputs');
        const keys      = Object.keys(cart);

        if (!keys.length) {
            container.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-400">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-300 text-2xl mb-3 shadow-inner">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div class="font-extrabold text-slate-700 text-sm">Keranjang Belanja Masih Kosong</div>
                    <div class="text-xs text-slate-400 max-w-xs mt-1">Pindai barcode barang atau klik produk pada katalog di sebelah kanan untuk memulai transaksi.</div>
                </div>`;
            hidden.innerHTML = '';
            calculatePos();
            return;
        }

        let html = '', hid = '';
        keys.forEach((kode, idx) => {
            const item = cart[kode];
            const sub = item.price * item.qty;
            hid  += `<input type="hidden" name="items[${idx}][kode_barang]" value="${item.kode}">
                     <input type="hidden" name="items[${idx}][jumlah]" value="${item.qty}">`;
            html += `
                <div class="p-3 bg-white border border-slate-200/90 rounded-2xl shadow-sm flex items-center justify-between gap-3 text-xs hover:border-emerald-400 transition-all">
                    <div class="flex-1 overflow-hidden">
                        <div class="flex items-center gap-1.5">
                            <span class="font-mono text-[10px] font-bold text-slate-400 bg-slate-100 px-1 py-0.5 rounded">${item.kode}</span>
                            <span class="font-extrabold text-slate-900 truncate">${escapeHtml(item.name)}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">Rp ${formatRupiah(item.price)} / pcs</div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="updateCartQty('${item.kode}', -1)" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-600 flex items-center justify-center font-black transition-colors">-</button>
                        <input type="number" min="1" max="${item.maxStock}" value="${item.qty}" onchange="setDirectQty('${item.kode}', this.value)"
                               class="w-10 text-center font-black text-slate-900 text-xs py-1 border border-slate-200 rounded-lg">
                        <button type="button" onclick="updateCartQty('${item.kode}', 1)" class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 flex items-center justify-center font-black transition-colors">+</button>
                    </div>
                    <div class="text-right min-w-[80px]">
                        <div class="font-black text-slate-900 text-xs">Rp ${formatRupiah(sub)}</div>
                        <button type="button" onclick="removeCartItem('${item.kode}')" class="text-[10px] font-bold text-rose-500 hover:text-rose-700 transition-colors">
                            <i class="fa-solid fa-trash-can mr-0.5"></i> Hapus
                        </button>
                    </div>
                </div>`;
        });

        container.innerHTML = html;
        hidden.innerHTML = hid;
        calculatePos();
    }

    function calculatePos() {
        let subtotal = 0, qty = 0;
        const keys = Object.keys(cart);
        keys.forEach(k => {
            subtotal += cart[k].price * cart[k].qty;
            qty += cart[k].qty;
        });

        const diskon = parseInt(document.getElementById('inputDiskon').value) || 0;
        const total  = Math.max(0, subtotal - diskon);

        document.getElementById('displayTotal').innerText   = 'Rp ' + formatRupiah(total);
        document.getElementById('displayQty').innerText     = qty + ' Pcs';
        document.getElementById('displayItems').innerText   = keys.length + ' Baris Item';
        document.getElementById('textSubtotal').innerText   = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('textTotalClean').innerText = 'Rp ' + formatRupiah(total);
        document.getElementById('hiddenDiskon').value       = diskon;

        // Perbarui QR Code Dinamis
        updateDynamicQrisCode(total);

        // Kalkulasi Kembalian Uang Tunai
        const cashVal = parseInt(document.getElementById('inputCash').value) || 0;
        const change  = cashVal - total;
        const chTxt   = document.getElementById('textChange');
        const chBox   = document.getElementById('changeBox');
        const btnTxt  = document.getElementById('btnSubmitText');

        if (paymentMethod === 'tunai') {
            document.getElementById('hiddenNominalUang').value = cashVal;
            if (change >= 0) {
                chTxt.innerText = 'Rp ' + formatRupiah(change);
                chTxt.className = 'text-base font-black text-emerald-700';
                chBox.className = 'p-2.5 rounded-xl border border-emerald-200 bg-emerald-50/70 flex items-center justify-between';
            } else {
                chTxt.innerText = 'Kurang Rp ' + formatRupiah(Math.abs(change));
                chTxt.className = 'text-base font-black text-rose-600';
                chBox.className = 'p-2.5 rounded-xl border border-rose-200 bg-rose-50/70 flex items-center justify-between';
            }
            btnTxt.innerText = 'Proses Pembayaran Tunai [Enter]';
        } else if (paymentMethod === 'qris') {
            document.getElementById('hiddenNominalUang').value = total;
            chTxt.innerText = 'Rp 0 (QRIS Lunas)';
            chTxt.className = 'text-base font-black text-emerald-700';
            chBox.className = 'p-2.5 rounded-xl border border-emerald-200 bg-emerald-50 flex items-center justify-between';
            btnTxt.innerText = isQrisVerified ? 'Konfirmasi QRIS Lunas & Simpan [Enter]' : 'Proses Pembayaran QRIS [Enter]';
        } else {
            document.getElementById('hiddenNominalUang').value = total;
            chTxt.innerText = 'Rp 0 (Transfer VA Lunas)';
            chTxt.className = 'text-base font-black text-blue-700';
            chBox.className = 'p-2.5 rounded-xl border border-blue-200 bg-blue-50 flex items-center justify-between';
            btnTxt.innerText = isVaVerified ? 'Konfirmasi VA Lunas & Simpan [Enter]' : 'Proses Pembayaran ' + selectedBankVaName + ' VA [Enter]';
        }
    }

    function setCash(val) {
        let sub = 0;
        Object.keys(cart).forEach(k => sub += cart[k].price * cart[k].qty);
        const diskon = parseInt(document.getElementById('inputDiskon').value) || 0;
        const target = Math.max(0, sub - diskon);

        document.getElementById('inputCash').value = (val === 'exact') ? target : val;
        calculatePos();
    }

    // ============================================================
    // 8. PENCARIAN & PEMILIHAN MEMBER
    // ============================================================
    function focusBarcode()   { document.getElementById('barcodeInput').focus(); }
    function openMemberSearch() {
        const i = document.getElementById('memberSearchInput');
        i.focus(); i.select();
    }

    function selectMember(id) {
        const m = allMembers.find(x => x.id_anggota === id);
        if (!m) return;
        selectedMember = m;
        document.getElementById('hiddenIdAnggota').value        = m.id_anggota;
        document.getElementById('activeMemberName').innerText    = m.nama;
        document.getElementById('activeMemberNim').innerText     = m.nim ? 'NIM: ' + m.nim : 'ID: ' + m.id_anggota;
        document.getElementById('activeMemberHp').innerHTML      = '<i class="fa-solid fa-phone text-[10px] mr-1"></i>' + (m.no_hp || '-');
        document.getElementById('activeMemberJurusan').innerText = m.jurusan || 'Anggota KOPMA';
        document.getElementById('memberSearchBox').classList.add('hidden');
        document.getElementById('memberSelectedBox').classList.remove('hidden');
        document.getElementById('memberDropdown').classList.add('hidden');
        document.getElementById('memberSearchInput').value = '';
    }

    function clearMember() {
        selectedMember = null;
        document.getElementById('hiddenIdAnggota').value = '';
        document.getElementById('memberSelectedBox').classList.add('hidden');
        document.getElementById('memberSearchBox').classList.remove('hidden');
        document.getElementById('memberSearchInput').value = '';
        document.getElementById('memberSearchInput').focus();
    }

    function setPelangganUmum() { clearMember(); }

    // ============================================================
    // 9. FILTER KATEGORI KATALOG
    // ============================================================
    function filterCategory(catId) {
        document.querySelectorAll('.cat-pill').forEach(b => {
            b.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
            b.classList.add('bg-slate-100', 'text-slate-600');
        });
        const activeBtn = document.querySelector('.cat-pill[data-category="' + catId + '"]');
        if (activeBtn) {
            activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
            activeBtn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
        }
        document.querySelectorAll('.product-item').forEach(card => {
            card.style.display = (catId === 'all' || card.getAttribute('data-category') === catId) ? '' : 'none';
        });
    }

    // ============================================================
    // 10. HELPER FORMATTING
    // ============================================================
    function formatRupiah(n) { return new Intl.NumberFormat('id-ID').format(n); }
    function escapeHtml(t) {
        if (!t) return '';
        return String(t)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>
@endpush
@endsection
