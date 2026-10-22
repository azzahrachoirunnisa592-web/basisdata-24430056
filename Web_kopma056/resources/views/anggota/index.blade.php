@extends('layouts.app')

@section('title', 'Data Anggota')
@section('header_title', 'Data Anggota')

@section('content')
<div class="space-y-6">
    
    <!-- Header Aksi & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
        <form action="{{ route('anggota.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, No. HP, NIM, atau jurusan..." 
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 py-2 px-3 bg-white focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>

            <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors">
                Filter
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('anggota.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">
                Reset
            </a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambahAnggota').classList.remove('hidden')" 
                class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 flex items-center gap-2 self-start sm:self-auto transition-colors">
            <i class="fa-solid fa-user-plus"></i>
            <span>Daftar Anggota Baru</span>
        </button>
    </div>

    <!-- Tabel Daftar Anggota -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-xs uppercase font-bold tracking-wider">
                        <th class="py-3.5 px-6">ID Anggota</th>
                        <th class="py-3.5 px-6">NIM</th>
                        <th class="py-3.5 px-6">Nama Mahasiswa</th>
                        <th class="py-3.5 px-6">Jurusan</th>
                        <th class="py-3.5 px-6">Kontak</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($anggota as $a)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-emerald-800 text-xs">
                            {{ $a->id_anggota }}
                        </td>
                        <td class="py-4 px-6 font-mono text-xs text-slate-700 font-semibold">
                            {{ $a->nim ?: '-' }}
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-800">
                            {{ $a->nama }}
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-600">
                            {{ $a->jurusan ?: '-' }}
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-500">
                            <div>{{ $a->no_hp ?: '-' }}</div>
                            <div class="text-[11px] text-slate-400">{{ $a->email }}</div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($a->status === 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                    Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" 
                                        onclick="openEditModal('{{ $a->id_anggota }}', '{{ $a->nim }}', '{{ addslashes($a->nama) }}', '{{ addslashes($a->jurusan) }}', '{{ $a->no_hp }}', '{{ $a->email }}', '{{ $a->status }}')"
                                        class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('anggota.destroy', $a->id_anggota) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota ini?');">
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
                            Tidak ada data anggota yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anggota->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $anggota->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div id="modalTambahAnggota" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden animate-scale-up">
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-emerald-600"></i>
                Pendaftaran Anggota Baru
            </h3>
            <button onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('anggota.store') }}" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID Anggota *</label>
                    <input type="text" name="id_anggota" required placeholder="ANG-016" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIM Mahasiswa</label>
                    <input type="text" name="nim" placeholder="24430099" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama" required placeholder="Nama lengkap mahasiswa" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Program Studi / Jurusan</label>
                <input type="text" name="jurusan" placeholder="Teknik Informatika, Sistem Informasi, dsb." class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">No. Handphone (WhatsApp)</label>
                    <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Mahasiswa</label>
                    <input type="email" name="email" placeholder="nama@kampus.ac.id" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Status Keanggotaan *</label>
                <select name="status" required class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                    <option value="aktif">Aktif</option>
                    <option value="non-aktif">Non-Aktif</option>
                </select>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalTambahAnggota').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 font-bold shadow-md shadow-emerald-600/20">Daftarkan Anggota</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Anggota -->
<div id="modalEditAnggota" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden animate-scale-up">
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-emerald-600"></i>
                Edit Data Anggota
            </h3>
            <button onclick="document.getElementById('modalEditAnggota').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editAnggotaForm" method="POST" class="p-6 space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID Anggota</label>
                    <input type="text" id="edit_id_anggota" readonly class="w-full rounded-xl border-slate-200 p-2.5 border bg-slate-100 font-mono text-slate-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIM Mahasiswa</label>
                    <input type="text" id="edit_nim" name="nim" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500 font-mono">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" id="edit_nama" name="nama" required class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Program Studi / Jurusan</label>
                <input type="text" id="edit_jurusan" name="jurusan" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">No. Handphone</label>
                    <input type="text" id="edit_no_hp" name="no_hp" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email</label>
                    <input type="email" id="edit_email" name="email" class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Status Keanggotaan *</label>
                <select id="edit_status" name="status" required class="w-full rounded-xl border-slate-200 p-2.5 border focus:ring-2 focus:ring-emerald-500">
                    <option value="aktif">Aktif</option>
                    <option value="non-aktif">Non-Aktif</option>
                </select>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modalEditAnggota').classList.add('hidden')" class="px-4 py-2 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 font-bold shadow-md shadow-emerald-600/20">Perbarui Anggota</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(id, nim, nama, jurusan, hp, email, status) {
        document.getElementById('editAnggotaForm').action = '/anggota/' + id;
        document.getElementById('edit_id_anggota').value = id;
        document.getElementById('edit_nim').value = nim || '';
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_jurusan').value = jurusan || '';
        document.getElementById('edit_no_hp').value = hp || '';
        document.getElementById('edit_email').value = email || '';
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditAnggota').classList.remove('hidden');
    }
</script>
@endpush
@endsection
