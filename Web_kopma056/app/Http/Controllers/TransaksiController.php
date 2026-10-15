<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['anggota', 'kasir', 'details.barang']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                  ->orWhereHas('anggota', function ($qa) use ($search) {
                      $qa->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_transaksi', $request->tanggal);
        }

        $transaksi = $query->orderBy('tgl_transaksi', 'desc')->paginate(15);
        $totalOmset = Transaksi::sum('total_bayar');

        return view('transaksi.index', compact('transaksi', 'totalOmset'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['anggota', 'kasir', 'details.barang'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }
}
