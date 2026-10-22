@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('header_title', 'Riwayat Transaksi Penjualan Koperasi')

@section('content')
<div class="space-y-6">
    
    <!-- Header Filter & Ringkasan -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <form action="{{ route('transaksi.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Faktur atau nama pembeli..." 
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" 
                       class="text-xs rounded-xl border-slate-200 py-2 px-3 bg-white focus:ring-2 focus:ring-emerald-500">
            </div>

            <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors">
                Filter
            </button>
            @if(request('search') || request('tanggal'))
            <a href="{{ route('transaksi.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">
                Reset
            </a>
            @endif
        </form>

        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            <a href="{{ route('transaksi.export.print', request()->query()) }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-print text-slate-500"></i>
                <span>Cetak / PDF</span>
            </a>
            <a href="{{ route('transaksi.export.csv', request()->query()) }}"
               class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 border border-slate-200 flex items-center gap-1.5 transition-colors">
                <i class="fa-solid fa-file-csv text-emerald-600"></i>
                <span>Ekspor Excel/CSV</span>
            </a>
            <a href="{{ route('pos.index') }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-cash-register"></i>
                <span>Buka Kasir POS</span>
            </a>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-xs uppercase font-bold tracking-wider">
                        <th class="py-3.5 px-6">No. Faktur</th>
                        <th class="py-3.5 px-6">Tanggal & Jam</th>
                        <th class="py-3.5 px-6">Pembeli / Anggota</th>
                        <th class="py-3.5 px-6">Kasir</th>
                        <th class="py-3.5 px-6">Ringkasan Produk</th>
                        <th class="py-3.5 px-6 text-right">Total Bayar</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-emerald-800 text-xs">
                            {{ $t->no_faktur }}
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600">
                            <div class="font-semibold text-slate-800">{{ $t->tgl_transaksi->format('d M Y') }}</div>
                            <div class="text-[11px] text-slate-400">{{ $t->tgl_transaksi->format('H:i:s') }} WIB</div>
                        </td>
                        <td class="py-4 px-6">
                            @if($t->anggota)
                                <div class="font-bold text-slate-800 text-xs">{{ $t->anggota->nama }}</div>
                                <div class="text-[11px] font-mono text-emerald-700 bg-emerald-50 inline-block px-1.5 py-0.5 rounded border border-emerald-200/60 mt-0.5">
                                    NIM: {{ $t->anggota->nim ?: $t->anggota->id_anggota }}
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600">
                                    Pelanggan Umum
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600">
                            {{ $t->kasir ? $t->kasir->name : 'Kasir Utama' }}
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600">
                            <div class="font-medium text-slate-800">{{ $t->details->count() }} jenis barang</div>
                            <div class="text-[11px] text-slate-400">{{ $t->details->sum('jumlah') }} unit total</div>
                        </td>
                        <td class="py-4 px-6 text-right font-mono font-extrabold text-slate-900 text-sm">
                            Rp {{ number_format($t->total_bayar, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('pos.receipt', $t->id_transaksi) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 rounded-lg border border-slate-200 transition-colors">
                                <i class="fa-solid fa-receipt text-emerald-600"></i>
                                Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            Tidak ada transaksi penjualan yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksi->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
