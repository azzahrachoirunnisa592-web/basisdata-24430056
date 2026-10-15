@extends('layouts.app')

@section('title', 'Transaksi Kasir POS')
@section('header_title', 'Point of Sale (POS) - Kasir Koperasi Mahasiswa')

@section('content')
<div class="h-full flex flex-col lg:flex-row gap-6">
    
    <!-- KOLOM KIRI: KATALOG PRODUK & PENCARIAN -->
    <div class="lg:w-7/12 flex flex-col space-y-4">
        <!-- Pencarian & Filter Kategori -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 space-y-3">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="searchInput" placeholder="Cari nama barang atau kode produk (misal: Air Mineral, Pulpen, BRG001)..." 
                       class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            <!-- Tab Kategori -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                <button type="button" onclick="filterCategory('all')" 
                        class="cat-btn active px-3.5 py-1.5 rounded-lg font-semibold bg-emerald-600 text-white shadow-sm transition-all" data-category="all">
                    Semua Produk
                </button>
                @foreach($kategori as $kat)
                <button type="button" onclick="filterCategory('{{ $kat->id_kategori }}')" 
                        class="cat-btn px-3.5 py-1.5 rounded-lg font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all whitespace-nowrap" data-category="{{ $kat->id_kategori }}">
                    {{ $kat->nama_kategori }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Grid Katalog Barang -->
        <div class="flex-1 overflow-y-auto custom-scrollbar pr-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5" id="productGrid">
                @foreach($barang as $item)
                <div class="product-card bg-white p-4 rounded-xl shadow-sm border border-slate-200/80 hover:border-emerald-400 hover:shadow-md transition-all flex flex-col justify-between cursor-pointer group {{ $item->stok <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                     data-id="{{ $item->kode_barang }}"
                     data-name="{{ $item->nama_barang }}"
                     data-price="{{ $item->harga_jual }}"
                     data-stock="{{ $item->stok }}"
                     data-category="{{ $item->id_kategori }}"
                     onclick="addToCart('{{ $item->kode_barang }}', '{{ addslashes($item->nama_barang) }}', {{ $item->harga_jual }}, {{ $item->stok }})">
                    
                    <div>
                        <div class="flex items-start justify-between gap-1 mb-2">
                            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase bg-slate-100 px-1.5 py-0.5 rounded">
                                {{ $item->kode_barang }}
                            </span>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $item->stok > 10 ? 'bg-emerald-50 text-emerald-700' : ($item->stok > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                Stok: {{ $item->stok }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-xs line-clamp-2 group-hover:text-emerald-700 transition-colors">
                            {{ $item->nama_barang }}
                        </h4>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                        <div class="font-extrabold text-slate-900 text-sm">
                            Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                        </div>
                        <button type="button" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-colors text-xs">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: STRUK KASIR & PEMBAYARAN -->
    <div class="lg:w-5/12 flex flex-col">
        <form action="{{ route('pos.store') }}" method="POST" id="posForm" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 flex flex-col h-full overflow-hidden">
            @csrf

            <!-- Header Struk Kasir -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-emerald-600"></i>
                        Mesin Kasir POS
                    </h3>
                    <div class="text-xs text-slate-500 font-mono mt-0.5">
                        Faktur: <span class="font-bold text-emerald-800">{{ $noFakturBaru }}</span>
                    </div>
                </div>
                <input type="hidden" name="no_faktur" value="{{ $noFakturBaru }}">
                <button type="button" onclick="clearCart()" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-2.5 py-1.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-trash-can mr-1"></i> Reset
                </button>
            </div>

            <!-- Pilih Anggota Pembeli -->
            <div class="p-4 border-b border-slate-100 bg-emerald-50/40">
                <label class="block text-xs font-bold text-emerald-950 uppercase tracking-wider mb-1.5">
                    <i class="fa-solid fa-user-tag text-emerald-600 mr-1"></i> Pilih Anggota / Pembeli
                </label>
                <select name="id_anggota" id="id_anggota" class="w-full text-xs rounded-xl border-slate-200 bg-white p-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">-- Pelanggan Umum / Non-Anggota --</option>
                    @foreach($anggota as $a)
                    <option value="{{ $a->id_anggota }}">
                        {{ $a->nim ? '[' . $a->nim . '] ' : '' }}{{ $a->nama }} ({{ $a->jurusan }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Daftar Item Belanja (Keranjang) -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-2.5 min-h-[180px]" id="cartContainer">
                <div id="emptyCartNotice" class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mb-3">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="font-bold text-slate-700 text-sm">Keranjang Belanja Kosong</div>
                    <div class="text-xs text-slate-400 max-w-[200px] mt-1">Klik barang di katalog sebelah kiri untuk menambahkan ke struk kasir</div>
                </div>
            </div>

            <!-- Rangkuman Pembayaran & Tombol Bayar -->
            <div class="p-5 border-t border-slate-200 bg-slate-50/90 space-y-3.5">
                <div class="space-y-1.5 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal Belanja</span>
                        <span class="font-bold text-slate-800" id="subtotalText">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Diskon (Rp)</span>
                        <input type="number" name="diskon" id="diskonInput" value="0" min="0" oninput="calculateTotal()" 
                               class="w-24 text-right py-1 px-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500 font-bold">
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                        <span>TOTAL BAYAR</span>
                        <span class="text-emerald-700 text-lg" id="totalBayarText">Rp 0</span>
                    </div>
                </div>

                <!-- Input Uang Diterima & Kembalian -->
                <div class="pt-2 border-t border-slate-200 space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        Nominal Uang Tunai (Rp)
                    </label>
                    <input type="number" name="nominal_uang" id="nominalUang" required min="0" placeholder="0" oninput="calculateTotal()" 
                           class="w-full py-2.5 px-3 text-base font-extrabold text-slate-900 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-right">
                    
                    <!-- Quick cash buttons -->
                    <div class="grid grid-cols-4 gap-1.5 pt-1">
                        <button type="button" onclick="setCash('exact')" class="px-2 py-1 text-[11px] font-semibold bg-white border border-slate-200 rounded-lg hover:bg-slate-100">Uang Pas</button>
                        <button type="button" onclick="setCash(20000)" class="px-2 py-1 text-[11px] font-semibold bg-white border border-slate-200 rounded-lg hover:bg-slate-100">20.000</button>
                        <button type="button" onclick="setCash(50000)" class="px-2 py-1 text-[11px] font-semibold bg-white border border-slate-200 rounded-lg hover:bg-slate-100">50.000</button>
                        <button type="button" onclick="setCash(100000)" class="px-2 py-1 text-[11px] font-semibold bg-white border border-slate-200 rounded-lg hover:bg-slate-100">100.000</button>
                    </div>

                    <div class="flex justify-between items-center text-xs p-2.5 rounded-xl bg-slate-100 border border-slate-200">
                        <span class="font-bold text-slate-600">Kembalian:</span>
                        <span class="font-extrabold text-slate-900 text-sm" id="kembalianText">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="btnSubmitPos" disabled 
                        class="w-full py-3.5 rounded-xl font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition-all">
                    <i class="fa-solid fa-check-double"></i>
                    <span>Proses & Potong Stok Otomatis</span>
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    let cart = {};

    function addToCart(kode, name, price, maxStock) {
        if (maxStock <= 0) {
            alert('Stok barang ini sudah habis!');
            return;
        }

        if (cart[kode]) {
            if (cart[kode].qty + 1 > maxStock) {
                alert('Jumlah melebihi stok yang tersedia (' + maxStock + ' item)!');
                return;
            }
            cart[kode].qty += 1;
        } else {
            cart[kode] = {
                kode: kode,
                name: name,
                price: price,
                qty: 1,
                maxStock: maxStock
            };
        }

        renderCart();
    }

    function updateQty(kode, delta) {
        if (!cart[kode]) return;
        const newQty = cart[kode].qty + delta;
        if (newQty <= 0) {
            delete cart[kode];
        } else if (newQty > cart[kode].maxStock) {
            alert('Jumlah melebihi stok yang tersedia (' + cart[kode].maxStock + ' item)!');
            return;
        } else {
            cart[kode].qty = newQty;
        }
        renderCart();
    }

    function removeCartItem(kode) {
        delete cart[kode];
        renderCart();
    }

    function clearCart() {
        cart = {};
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartContainer');
        const emptyNotice = document.getElementById('emptyCartNotice');
        const btnSubmit = document.getElementById('btnSubmitPos');

        const keys = Object.keys(cart);
        if (keys.length === 0) {
            container.innerHTML = `
                <div id="emptyCartNotice" class="h-full flex flex-col items-center justify-center text-center p-8 text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mb-3">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="font-bold text-slate-700 text-sm">Keranjang Belanja Kosong</div>
                    <div class="text-xs text-slate-400 max-w-[200px] mt-1">Klik barang di katalog sebelah kiri untuk menambahkan ke struk kasir</div>
                </div>
            `;
            btnSubmit.disabled = true;
            calculateTotal();
            return;
        }

        let html = '';
        keys.forEach((kode, idx) => {
            const item = cart[kode];
            const subtotal = item.price * item.qty;
            html += `
                <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center justify-between gap-3 text-xs">
                    <input type="hidden" name="items[${idx}][kode_barang]" value="${item.kode}">
                    <input type="hidden" name="items[${idx}][jumlah]" value="${item.qty}">
                    
                    <div class="flex-1 overflow-hidden">
                        <div class="font-bold text-slate-800 truncate">${item.name}</div>
                        <div class="text-slate-500 font-mono text-[11px]">Rp ${formatRupiah(item.price)} x ${item.qty}</div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="updateQty('${item.kode}', -1)" class="w-6 h-6 rounded-md bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold hover:bg-slate-100">-</button>
                        <span class="w-6 text-center font-bold text-slate-800 text-xs">${item.qty}</span>
                        <button type="button" onclick="updateQty('${item.kode}', 1)" class="w-6 h-6 rounded-md bg-white border border-slate-200 text-slate-700 flex items-center justify-center font-bold hover:bg-slate-100">+</button>
                    </div>

                    <div class="text-right min-w-[70px]">
                        <div class="font-bold text-slate-900 text-xs">Rp ${formatRupiah(subtotal)}</div>
                        <button type="button" onclick="removeCartItem('${item.kode}')" class="text-[10px] text-rose-500 hover:text-rose-700">Hapus</button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        btnSubmit.disabled = false;
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        Object.keys(cart).forEach(kode => {
            subtotal += cart[kode].price * cart[kode].qty;
        });

        const diskon = parseInt(document.getElementById('diskonInput').value) || 0;
        const total = Math.max(0, subtotal - diskon);
        const cash = parseInt(document.getElementById('nominalUang').value) || 0;
        const change = cash - total;

        document.getElementById('subtotalText').innerText = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('totalBayarText').innerText = 'Rp ' + formatRupiah(total);

        const changeEl = document.getElementById('kembalianText');
        if (change >= 0) {
            changeEl.innerText = 'Rp ' + formatRupiah(change);
            changeEl.className = 'font-extrabold text-emerald-700 text-sm';
        } else {
            changeEl.innerText = 'Kurang Rp ' + formatRupiah(Math.abs(change));
            changeEl.className = 'font-extrabold text-rose-600 text-sm';
        }

        const btnSubmit = document.getElementById('btnSubmitPos');
        if (Object.keys(cart).length > 0 && cash >= total) {
            btnSubmit.disabled = false;
        } else {
            btnSubmit.disabled = true;
        }
    }

    function setCash(val) {
        let subtotal = 0;
        Object.keys(cart).forEach(kode => {
            subtotal += cart[kode].price * cart[kode].qty;
        });
        const diskon = parseInt(document.getElementById('diskonInput').value) || 0;
        const total = Math.max(0, subtotal - diskon);

        if (val === 'exact') {
            document.getElementById('nominalUang').value = total;
        } else {
            document.getElementById('nominalUang').value = val;
        }
        calculateTotal();
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Filter Kategori
    function filterCategory(catId) {
        document.querySelectorAll('.cat-btn').forEach(b => {
            b.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
            b.classList.add('bg-slate-100', 'text-slate-600');
        });

        const activeBtn = document.querySelector(`.cat-btn[data-category="${catId}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('bg-slate-100', 'text-slate-600');
            activeBtn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
        }

        const cards = document.querySelectorAll('.product-card');
        cards.forEach(c => {
            if (catId === 'all' || c.getAttribute('data-category') === catId) {
                c.style.display = 'flex';
            } else {
                c.style.display = 'none';
            }
        });
    }

    // Search filter
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        cards.forEach(c => {
            const name = c.getAttribute('data-name').toLowerCase();
            const id = c.getAttribute('data-id').toLowerCase();
            if (name.includes(query) || id.includes(query)) {
                c.style.display = 'flex';
            } else {
                c.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
