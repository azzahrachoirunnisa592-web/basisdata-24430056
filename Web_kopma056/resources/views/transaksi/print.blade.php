<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Penjualan — KOPMA 24430056</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #047857;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #064e3b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #555;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 11px;
        }
        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: left;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }
        .summary-box {
            margin-top: 15px;
            float: right;
            width: 300px;
            border: 1px solid #cbd5e1;
            padding: 10px;
            border-radius: 6px;
            background: #f8fafc;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 11px;
        }
        .signatures {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #333;
            font-weight: bold;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 15px; }
            @page { margin: 10mm; size: A4 portrait; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; background: #ecfdf5; border: 1px solid #6ee7b7; padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <span style="color: #065f46; font-weight: bold;">Dokumen Laporan Rekapitulasi Penjualan Koperasi Mahasiswa Siap Dicetak</span>
        <div>
            <button onclick="window.print()" style="background: #059669; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; margin-right: 8px;">
                Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" style="background: #e2e8f0; color: #475569; border: none; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>

    <div class="header">
        <h1>Koperasi Mahasiswa Terpadu (KOPMA)</h1>
        <p>Sistem Informasi Koperasi Mahasiswa &bull; kopma_24430056 &bull; Kampus Terpadu Gedung Student Center Lt. 1</p>
        <p style="font-weight: bold; color: #047857; margin-top: 5px;">LAPORAN REKAPITULASI TRANSAKSI PENJUALAN KASIR</p>
    </div>

    <div class="meta-info">
        <div>
            <div>Tanggal Cetak : <b>{{ now()->translatedFormat('d F Y, H:i') }} WIB</b></div>
            <div>Dicetak Oleh   : <b>{{ auth()->user() ? auth()->user()->name : 'Annisa (Admin - 24430056)' }}</b></div>
        </div>
        <div style="text-align: right;">
            <div>Total Transaksi: <b>{{ $transaksis->count() }} Data Transaksi</b></div>
            <div>Total Unit Barang Terjual: <b>{{ number_format($totalItems, 0, ',', '.') }} pcs</b></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 17%;">No. Faktur</th>
                <th style="width: 15%;">Tanggal & Jam</th>
                <th style="width: 20%;">Pembeli / Anggota</th>
                <th style="width: 14%;">Kasir</th>
                <th style="width: 14%;" class="text-right">Total Belanja</th>
                <th style="width: 16%;" class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $idx => $t)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="font-mono font-bold">{{ $t->no_faktur }}</td>
                <td>{{ $t->tgl_transaksi ? $t->tgl_transaksi->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    {{ $t->anggota ? $t->anggota->nama : 'Pelanggan Umum' }}
                    @if($t->anggota && $t->anggota->nim)
                        <span style="font-size: 10px; color: #64748b;">({{ $t->anggota->nim }})</span>
                    @endif
                </td>
                <td>{{ $t->kasir ? $t->kasir->name : 'Kasir Utama' }}</td>
                <td class="text-right font-mono">Rp {{ number_format($t->total_belanja, 0, ',', '.') }}</td>
                <td class="text-right font-mono font-bold">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">Tidak ada data transaksi penjualan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-row">
            <span>Jumlah Transaksi:</span>
            <b>{{ $transaksis->count() }}</b>
        </div>
        <div class="summary-row">
            <span>Total Produk Terjual:</span>
            <b>{{ number_format($totalItems, 0, ',', '.') }} pcs</b>
        </div>
        <div class="summary-row" style="border-top: 1px solid #cbd5e1; padding-top: 5px; margin-top: 5px; font-size: 13px; color: #064e3b;">
            <span>TOTAL OMZET BERSIH:</span>
            <b>Rp {{ number_format($totalOmset, 0, ',', '.') }}</b>
        </div>
    </div>
    <div style="clear: both;"></div>

    <div class="signatures">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div style="font-weight: bold; margin-top: 2px;">Ketua KOPMA</div>
            <div class="signature-line">Drs. H. Mulyadi, M.M.</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 2px;">NIP. 19780512 200501 1 002</div>
        </div>
        <div class="signature-box">
            <div>Penanggung Jawab Sistem,</div>
            <div style="font-weight: bold; margin-top: 2px;">Administrator KOPMA</div>
            <div class="signature-line">Annisa Choirunnisa</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 2px;">NIM: 24430056</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
