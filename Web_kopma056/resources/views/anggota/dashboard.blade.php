@extends('layouts.app')

@section('title', 'Dashboard Anggota — KOMPA')
@section('header_title', 'Dashboard Anggota')

@section('content')
<div class="space-y-6">

    {{-- ===== HEADER KARTU ANGGOTA ===== --}}
    <div class="relative overflow-hidden rounded-2xl shadow-lg"
         style="background: linear-gradient(135deg, #0b1120 0%, #0f4a26 50%, #052e16 100%);">
        <div class="absolute inset-0 opacity-10">
            <i class="fa-solid fa-building-columns text-[280px] text-white absolute -right-10 -bottom-10 rotate-12"></i>
        </div>
        <div class="relative z-10 p-7 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-2xl font-black text-white shadow-xl shadow-emerald-500/30 flex-shrink-0">
                    {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                </div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold mb-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        ANGGOTA {{ strtoupper($anggota->status) }}
                    </div>
                    <h2 class="text-2xl font-extrabold text-white">{{ $anggota->nama }}</h2>
                    <div class="flex flex-wrap gap-3 mt-1 text-sm text-emerald-200/80">
                        <span><i class="fa-solid fa-id-badge mr-1 text-emerald-400"></i>{{ $anggota->id_anggota }}</span>
                        <span><i class="fa-solid fa-graduation-cap mr-1 text-emerald-400"></i>{{ $anggota->nim }}</span>
                        <span><i class="fa-solid fa-building mr-1 text-emerald-400"></i>{{ $anggota->jurusan }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 md:flex-col md:items-end">
                <div class="text-right">
                    <div class="text-[10px] text-emerald-400 uppercase tracking-wider font-bold">Bergabung Sejak</div>
                    <div class="text-white font-bold text-sm">{{ \Carbon\Carbon::parse($anggota->tgl_bergabung)->translatedFormat('d F Y') }}</div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-emerald-400 uppercase tracking-wider font-bold">Total Simpanan</div>
                    <div class="text-emerald-300 font-extrabold text-lg">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RINCIAN SIMPANAN (3 Kartu) ===== --}}
    <div>
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
            <i class="fa-solid fa-piggy-bank text-emerald-500"></i> Rincian Simpanan
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @php
                $simpananTypes = [
                    ['label'=>'Simpanan Pokok',    'nilai'=>$simpananPokok,    'icon'=>'fa-landmark',     'color'=>'emerald', 'desc'=>'Dibayar 1× saat mendaftar'],
                    ['label'=>'Simpanan Wajib',    'nilai'=>$simpananWajib,    'icon'=>'fa-calendar-check','color'=>'blue',    'desc'=>'Rutin setiap bulan'],
                    ['label'=>'Simpanan Sukarela', 'nilai'=>$simpananSukarela, 'icon'=>'fa-hand-holding-dollar','color'=>'violet','desc'=>'Sewaktu-waktu sesuai kemampuan'],
                ];
                $cardColors = [
                    'emerald' => ['bg'=>'bg-emerald-50 border-emerald-200','icon'=>'bg-emerald-100 text-emerald-700','val'=>'text-emerald-700'],
                    'blue'    => ['bg'=>'bg-blue-50 border-blue-200',      'icon'=>'bg-blue-100 text-blue-700',      'val'=>'text-blue-700'],
                    'violet'  => ['bg'=>'bg-violet-50 border-violet-200',  'icon'=>'bg-violet-100 text-violet-700',  'val'=>'text-violet-700'],
                ];
            @endphp
            @foreach($simpananTypes as $st)
            @php $cc = $cardColors[$st['color']]; @endphp
            <div class="bg-white rounded-2xl border {{ $cc['bg'] }} p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl {{ $cc['icon'] }} flex items-center justify-center">
                        <i class="fa-solid {{ $st['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ $st['label'] }}</div>
                        <div class="text-[10px] text-slate-400">{{ $st['desc'] }}</div>
                    </div>
                </div>
                <div class="text-2xl font-extrabold {{ $cc['val'] }}">
                    Rp {{ number_format($st['nilai'], 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ===== INFO PINJAMAN AKTIF ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-hand-holding-dollar text-amber-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Informasi Pinjaman Aktif</h3>
            </div>
            <div class="p-5">
                @if($pinjamanAktif)
                @php
                    $persen = $pinjamanAktif->jumlah_pinjaman > 0
                        ? round((($pinjamanAktif->jumlah_pinjaman - $pinjamanAktif->sisa_pinjaman) / $pinjamanAktif->jumlah_pinjaman) * 100)
                        : 0;
                    $hariJatuhTempo = now()->diffInDays($pinjamanAktif->tgl_jatuh_tempo, false);
                @endphp
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <div class="text-[10px] text-slate-400 uppercase tracking-wide font-bold">Total Pinjaman</div>
                            <div class="text-base font-extrabold text-slate-800">Rp {{ number_format($pinjamanAktif->jumlah_pinjaman, 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-rose-50 rounded-xl p-3">
                            <div class="text-[10px] text-rose-400 uppercase tracking-wide font-bold">Sisa Tagihan</div>
                            <div class="text-base font-extrabold text-rose-600">Rp {{ number_format($pinjamanAktif->sisa_pinjaman, 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-amber-50 rounded-xl p-3">
                            <div class="text-[10px] text-amber-500 uppercase tracking-wide font-bold">Angsuran/Bulan</div>
                            <div class="text-base font-extrabold text-amber-700">Rp {{ number_format($pinjamanAktif->angsuran_per_bulan, 0, ',', '.') }}</div>
                        </div>
                        <div class="rounded-xl p-3 {{ $hariJatuhTempo < 30 ? 'bg-rose-50' : 'bg-emerald-50' }}">
                            <div class="text-[10px] uppercase tracking-wide font-bold {{ $hariJatuhTempo < 30 ? 'text-rose-400' : 'text-emerald-500' }}">Jatuh Tempo</div>
                            <div class="text-sm font-extrabold {{ $hariJatuhTempo < 30 ? 'text-rose-600' : 'text-emerald-700' }}">
                                {{ $pinjamanAktif->tgl_jatuh_tempo->translatedFormat('d M Y') }}
                            </div>
                            <div class="text-[10px] {{ $hariJatuhTempo < 30 ? 'text-rose-400' : 'text-emerald-500' }}">
                                {{ $hariJatuhTempo > 0 ? $hariJatuhTempo . ' hari lagi' : 'Sudah jatuh tempo!' }}
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div>
                        <div class="flex justify-between text-xs text-slate-500 mb-1">
                            <span>Kemajuan Pembayaran</span>
                            <span class="font-bold text-emerald-600">{{ $persen }}%</span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-700"
                                 style="width: {{ $persen }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                            <span>Rp 0</span>
                            <span>Rp {{ number_format($pinjamanAktif->jumlah_pinjaman, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-2xl"></i>
                    </div>
                    <div class="font-bold text-slate-700">Tidak Ada Pinjaman Aktif</div>
                    <div class="text-xs text-slate-400 mt-1">Anda tidak memiliki pinjaman yang sedang berjalan</div>
                </div>
                @endif
            </div>
        </div>

        {{-- ===== SHU TAHUNAN ===== --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-emerald-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Sisa Hasil Usaha (SHU)</h3>
            </div>
            <div class="p-5 space-y-4">
                @if($shuTahunIni)
                {{-- SHU Tahun Ini --}}
                <div class="relative overflow-hidden rounded-xl p-4" style="background:linear-gradient(135deg,#052e16,#0f4a26)">
                    <div class="absolute right-2 bottom-2 opacity-10 text-[80px] text-white"><i class="fa-solid fa-coins"></i></div>
                    <div class="relative z-10">
                        <div class="text-[10px] text-emerald-300 uppercase tracking-wider font-bold mb-1">SHU Tahun {{ $shuTahunIni->tahun }}</div>
                        <div class="text-3xl font-extrabold text-white mb-2">Rp {{ number_format($shuTahunIni->total_shu, 0, ',', '.') }}</div>
                        <div class="flex gap-4 text-xs text-emerald-200/80">
                            <span><i class="fa-solid fa-piggy-bank mr-1"></i>Jasa Simpanan: Rp {{ number_format($shuTahunIni->shu_jasa_simpanan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex gap-4 text-xs text-emerald-200/80 mt-0.5">
                            <span><i class="fa-solid fa-store mr-1"></i>Jasa Usaha: Rp {{ number_format($shuTahunIni->shu_jasa_usaha, 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-3">
                            @if($shuTahunIni->status === 'belum_dibagi')
                            <span class="inline-flex items-center gap-1 text-[10px] bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-1 rounded-full font-bold">
                                <i class="fa-solid fa-clock"></i> Menunggu Pembagian
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[10px] bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2 py-1 rounded-full font-bold">
                                <i class="fa-solid fa-check-circle"></i> Sudah Dibagikan {{ $shuTahunIni->tgl_pembagian?->translatedFormat('d M Y') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- SHU Sebelumnya --}}
                @if($shuSebelumnya)
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <div class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-1">SHU Tahun {{ $shuSebelumnya->tahun }} (Historis)</div>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-extrabold text-slate-700">Rp {{ number_format($shuSebelumnya->total_shu, 0, ',', '.') }}</span>
                        <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full font-bold">Sudah Dibagi</span>
                    </div>
                </div>
                @endif
                @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center mb-3">
                        <i class="fa-solid fa-hourglass-half text-slate-400 text-2xl"></i>
                    </div>
                    <div class="font-bold text-slate-700">Data SHU Belum Tersedia</div>
                    <div class="text-xs text-slate-400 mt-1">SHU akan dihitung pada akhir tahun koperasi</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== TABEL RIWAYAT SIMPANAN ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i>
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Simpanan Terakhir</h3>
            </div>
            <span class="text-xs text-slate-400">{{ $riwayatSimpanan->count() }} entri terbaru</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-left">
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Jenis</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nominal</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatSimpanan as $s)
                    @php
                        $jenisColor = [
                            'pokok'    => 'bg-emerald-100 text-emerald-700',
                            'wajib'    => 'bg-blue-100 text-blue-700',
                            'sukarela' => 'bg-violet-100 text-violet-700',
                        ][$s->jenis_simpanan] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5 text-slate-500 text-xs">{{ $s->tanggal->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $jenisColor }}">
                                {{ ucfirst($s->jenis_simpanan) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-slate-800">Rp {{ number_format($s->nominal, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $s->keterangan ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat simpanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===== TABEL TRANSAKSI TERAKHIR ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-receipt text-emerald-500"></i>
            <h3 class="font-bold text-slate-800 text-sm">Transaksi Belanja Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-left">
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">No. Faktur</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Item</th>
                        <th class="px-5 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerakhir as $trx)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs text-slate-600">{{ $trx->no_faktur }}</td>
                        <td class="px-5 py-3.5 text-slate-500 text-xs">{{ $trx->tgl_transaksi->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-5 py-3.5 text-slate-600 text-xs">
                            {{ $trx->details->count() }} item
                            <span class="text-slate-400">({{ $trx->details->pluck('barang.nama_barang')->implode(', ') }})</span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-emerald-700">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
