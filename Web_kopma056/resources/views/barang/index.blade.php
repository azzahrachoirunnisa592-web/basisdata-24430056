@extends('layouts.app')

@section('title', 'Manajemen Barang')
@section('header_title', 'Manajemen Katalog Barang & Inventaris Stok')

@section('content')
<div class="space-y-6">
    
    <!-- Header Aksi & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <form action="{{ route('barang.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama barang..." 
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <select name="kategori" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 py-2 px-3 bg-white focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $k)
                <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
                @endforeach
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors">
                Filter
            </button>
            @if(request('search') || request('kategori'))
            <a href="{{ route('barang.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">
                Reset
            </a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahBarang').classList.remove('hidden')" 
                class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 flex items-center gap-2 self-start sm:self-auto transition-colors">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Barang Baru</span>
        </button>
    </div>

    <!-- Tabel Daftar Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-xs uppercase font-bold tracking-wider">
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-6">Nama Barang</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6 text-right">Harga Beli</th>
                        <th class="py-3.5 px-6 text-right">Harga Jual</th>
                        <th class="py-3.5 px-6 text-center">Stok</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($barang as $b)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-emerald-800 text-xs">
                            {{ $b->kode_barang }}
                        </td>
                        <td class="py-4 px-6 font-semibold text-slate-800">
                            {{ $b->nama_barang }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $b->kategori ? $b->kategori->nama_kategori : '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right font-mono text-xs text-slate-500">
                            Rp {{ number_format($b->harga_beli, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-right font-mono font-bold text-slate-900 text-xs">
                            Rp {{ number_format($b->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $b->stok > 20 ? 'bg-emerald-50 text-emerald-700' : ($b->stok > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                {{ $b->stok }} {{ $b->satuan }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" 
                                        onclick="openEditModal('{{ $b->kode_barang }}', '{{ addslashes($b->nama_barang) }}', '{{ $b->id_kategori }}', {{ $b->harga_beli }}, {{ $b->harga_jual }}, {{ $b->stok }}, '{{ $b->satuan }}')"
                                        class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('barang.destroy', $b->kode_barang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center text-xs transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            Tidak ada data barang yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barang->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $barang->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Barang -->
<div id="modalTambahBarang" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden animate-scale-up">
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-box text-emerald-600"></i>
                Tambah Barang Baru
            </h3>
            <button onclick="document.getElementById('modalTambahBarang').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('barang.store') }}" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kode Barang *</label>
                <input type="text" name="kode_barang" required placeholder="Contoh: BRG017" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Barang *</label>
                <input type="text" name="nama_barang" required placeholder="Nama produk / barang" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                <select name="id_kategori" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoriList as $k)
                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Harga Beli (Rp) *</label>
                    <input type="number" name="harga_beli" required min="0" placeholder="0" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Harga Jual (Rp) *</label>
                    <input type="number" name="harga_jual" required min="0" placeholder="0" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Stok Awal *</label>
                    <input type="number" name="stok" required min="0" placeholder="0" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Satuan *</label>
                    <input type="text" name="satuan" required value="pcs" placeholder="pcs, rim, botol" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahBarang').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 font-bold shadow-md shadow-emerald-600/20">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Barang -->
<div id="modalEditBarang" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden animate-scale-up">
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-emerald-600"></i>
                Edit Data Barang
            </h3>
            <button onclick="document.getElementById('modalEditBarang').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editBarangForm" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kode Barang</label>
                <input type="text" id="edit_kode_barang" readonly class="w-full rounded-xl border-slate-200 p-2.5 border bg-slate-100 font-mono text-slate-600">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Barang *</label>
                <input type="text" id="edit_nama_barang" name="nama_barang" required class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori</label>
                <select id="edit_id_kategori" name="id_kategori" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoriList as $k)
                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Harga Beli (Rp) *</label>
                    <input type="number" id="edit_harga_beli" name="harga_beli" required min="0" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Harga Jual (Rp) *</label>
                    <input type="number" id="edit_harga_jual" name="harga_jual" required min="0" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Stok Tersedia *</label>
                    <input type="number" id="edit_stok" name="stok" required min="0" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Satuan *</label>
                    <input type="text" id="edit_satuan" name="satuan" required class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalEditBarang').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 font-bold shadow-md shadow-emerald-600/20">Perbarui Barang</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(kode, nama, kategori, beli, jual, stok, satuan) {
        document.getElementById('editBarangForm').action = '/barang/' + kode;
        document.getElementById('edit_kode_barang').value = kode;
        document.getElementById('edit_nama_barang').value = nama;
        document.getElementById('edit_id_kategori').value = kategori || '';
        document.getElementById('edit_harga_beli').value = beli;
        document.getElementById('edit_harga_jual').value = jual;
        document.getElementById('edit_stok').value = stok;
        document.getElementById('edit_satuan').value = satuan;
        document.getElementById('modalEditBarang').classList.remove('hidden');
    }
</script>
@endpush
@endsection
