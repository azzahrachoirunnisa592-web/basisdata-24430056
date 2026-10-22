<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%")
                  ->orWhere('id_anggota', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $anggota = $query->orderBy('nama')->paginate(15);

        return view('anggota.index', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|string|unique:anggota,id_anggota|max:15',
            'nim' => 'nullable|string|unique:anggota,nim|max:15',
            'nama' => 'required|string|max:100',
            'jurusan' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:anggota,email|max:100',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        Anggota::create($request->all());

        return redirect()->route('anggota.index')->with('success', 'Anggota koperasi berhasil didaftarkan.');
    }

    public function update(Request $request, $id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);

        $request->validate([
            'nim' => 'nullable|string|max:15|unique:anggota,nim,' . $id_anggota . ',id_anggota',
            'nama' => 'required|string|max:100',
            'jurusan' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100|unique:anggota,email,' . $id_anggota . ',id_anggota',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $anggota->update($request->only([
            'nim',
            'nama',
            'jurusan',
            'no_hp',
            'email',
            'status',
        ]));

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);

        if ($anggota->transaksi()->count() > 0) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena memiliki riwayat transaksi koperasi.');
        }

        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
