<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $barang = $query->orderBy('nama_barang')->paginate(15);
        $kategoriList = Kategori::all();

        return view('barang.index', compact('barang', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|unique:barang,kode_barang',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }

    public function update(Request $request, $kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);

        $request->validate([
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
        ]);

        $barang->update($request->only([
            'id_kategori',
            'nama_barang',
            'harga_beli',
            'harga_jual',
            'stok',
            'satuan',
        ]));

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy($kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);

        // Periksa apakah barang sudah pernah ditransaksikan
        if ($barang->detailTransaksi()->count() > 0) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
