<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Shu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive production-grade dummy data.
     * Identitas Pemilik Sistem: Annisa Choirunnisa (NIM: 24430056)
     */
    public function run(): void
    {
        // ============================================================
        // 1. SEED USERS & ROLES
        // ============================================================
        $admin = User::updateOrCreate(
            ['email' => 'annisa@kopma.ac.id'],
            [
                'name'     => 'Annisa Choirunnisa (24430056)',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        $pengurus = User::updateOrCreate(
            ['email' => 'pengurus@kopma.ac.id'],
            [
                'name'     => 'Pengurus KOPMA',
                'password' => Hash::make('pengurus123'),
                'role'     => 'pengurus',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@kopma.ac.id'],
            [
                'name'     => 'Petugas Kasir KOPMA',
                'password' => Hash::make('kasir123'),
                'role'     => 'kasir',
            ]
        );

        $userAnggota = User::updateOrCreate(
            ['email' => 'anggota@kopma.ac.id'],
            [
                'name'     => 'Annisa (Demo Anggota 24430056)',
                'password' => Hash::make('anggota123'),
                'role'     => 'anggota',
            ]
        );

        // ============================================================
        // 2. SEED KATEGORI PRODUK
        // ============================================================
        $kategoriList = [
            ['id_kategori' => 1, 'nama_kategori' => 'Alat Tulis Kantor (ATK)',      'keterangan' => 'Perlengkapan tulis, buku, map, dan alat perkuliahan'],
            ['id_kategori' => 2, 'nama_kategori' => 'Makanan & Minuman',             'keterangan' => 'Snack, roti, minuman botol dingin, dan mie instan'],
            ['id_kategori' => 3, 'nama_kategori' => 'Sembako & Kebutuhan Pokok',     'keterangan' => 'Beras, minyak goreng, gula, dan sembako keluarga'],
            ['id_kategori' => 4, 'nama_kategori' => 'Merchandise KOPMA',             'keterangan' => 'Kaos, tote bag, tumbler, dan cinderamata resmi kampus'],
            ['id_kategori' => 5, 'nama_kategori' => 'Aksesoris & Gadget',            'keterangan' => 'Flashdisk, kabel data, OTG, dan aksesori digital'],
        ];
        foreach ($kategoriList as $kat) {
            Kategori::updateOrCreate(['id_kategori' => $kat['id_kategori']], $kat);
        }

        // ============================================================
        // 3. SEED 40+ BARANG PRODUK (DENGAN VARIASI STOK KRITIS & HERO)
        // ============================================================
        $barangList = [
            // ATK (Kategori 1)
            ['kode_barang' => 'BRG001', 'id_kategori' => 1, 'nama_barang' => 'Buku Tulis Spiral A5 Hardcover',      'harga_beli' => 6000,   'harga_jual' => 8000,   'stok' => 65,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG002', 'id_kategori' => 1, 'nama_barang' => 'Pulpen Gel 0.5mm Hitam Joyko',         'harga_beli' => 2500,   'harga_jual' => 3500,   'stok' => 150, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG003', 'id_kategori' => 1, 'nama_barang' => 'Pensil 2B Faber-Castell Asli',         'harga_beli' => 3000,   'harga_jual' => 4000,   'stok' => 80,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG004', 'id_kategori' => 1, 'nama_barang' => 'Penghapus Joyko Dust-Free Hitam',      'harga_beli' => 1500,   'harga_jual' => 2500,   'stok' => 60,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG005', 'id_kategori' => 1, 'nama_barang' => 'Map Kertas Folio Stopmap Biola',       'harga_beli' => 1200,   'harga_jual' => 2000,   'stok' => 95,  'satuan' => 'lembar'],
            ['kode_barang' => 'BRG006', 'id_kategori' => 1, 'nama_barang' => 'Kertas HVS A4 80gr PaperOne',          'harga_beli' => 48000,  'harga_jual' => 55000,  'stok' => 3,   'satuan' => 'rim'], // Stok kritis
            ['kode_barang' => 'BRG007', 'id_kategori' => 1, 'nama_barang' => 'Tipe-X Kertas Correction Tape Joyko',  'harga_beli' => 5500,   'harga_jual' => 7500,   'stok' => 35,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG008', 'id_kategori' => 1, 'nama_barang' => 'Penggaris Besi 30cm Anti Karat',       'harga_beli' => 4000,   'harga_jual' => 6000,   'stok' => 28,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG009', 'id_kategori' => 1, 'nama_barang' => 'Sticky Notes Warna-Warni Pastel 3x3',  'harga_beli' => 4500,   'harga_jual' => 6500,   'stok' => 42,  'satuan' => 'pack'],
            ['kode_barang' => 'BRG010', 'id_kategori' => 1, 'nama_barang' => 'Spidol Boardmarker Snowman Hitam',     'harga_beli' => 7500,   'harga_jual' => 9500,   'stok' => 40,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG011', 'id_kategori' => 1, 'nama_barang' => 'Stabilo Boss Original Kuning Neon',    'harga_beli' => 8500,   'harga_jual' => 11000,  'stok' => 32,  'satuan' => 'pcs'],

            // Makanan & Minuman (Kategori 2)
            ['kode_barang' => 'BRG012', 'id_kategori' => 2, 'nama_barang' => 'Air Mineral Botol 600ml Dingin',       'harga_beli' => 2800,   'harga_jual' => 4000,   'stok' => 90,  'satuan' => 'botol'],
            ['kode_barang' => 'BRG013', 'id_kategori' => 2, 'nama_barang' => 'Kopi Susu Gula Aren Botol KOPMA',     'harga_beli' => 9000,   'harga_jual' => 13000,  'stok' => 4,   'satuan' => 'botol'], // Stok kritis
            ['kode_barang' => 'BRG014', 'id_kategori' => 2, 'nama_barang' => 'Roti Bakar Coklat Keju Fresh',         'harga_beli' => 5000,   'harga_jual' => 7000,   'stok' => 30,  'satuan' => 'bungkus'],
            ['kode_barang' => 'BRG015', 'id_kategori' => 2, 'nama_barang' => 'Mie Instan Sedap Cup Kari Ayam',       'harga_beli' => 4800,   'harga_jual' => 6500,   'stok' => 55,  'satuan' => 'cup'],
            ['kode_barang' => 'BRG016', 'id_kategori' => 2, 'nama_barang' => 'Biskuit Gandum Coklat Roma',           'harga_beli' => 6500,   'harga_jual' => 8500,   'stok' => 38,  'satuan' => 'pack'],
            ['kode_barang' => 'BRG017', 'id_kategori' => 2, 'nama_barang' => 'Teh Pucuk Harum Melati 350ml',        'harga_beli' => 2800,   'harga_jual' => 4000,   'stok' => 70,  'satuan' => 'botol'],
            ['kode_barang' => 'BRG018', 'id_kategori' => 2, 'nama_barang' => 'Susu Ultra Milk Coklat 250ml',         'harga_beli' => 5500,   'harga_jual' => 7000,   'stok' => 48,  'satuan' => 'kotak'],
            ['kode_barang' => 'BRG019', 'id_kategori' => 2, 'nama_barang' => 'Keripik Singkong Pedas Manis 100gr',  'harga_beli' => 7000,   'harga_jual' => 10000,  'stok' => 26,  'satuan' => 'bungkus'],
            ['kode_barang' => 'BRG020', 'id_kategori' => 2, 'nama_barang' => 'Chitato Rasa Sapi Panggang 68gr',      'harga_beli' => 8500,   'harga_jual' => 11500,  'stok' => 35,  'satuan' => 'bungkus'],
            ['kode_barang' => 'BRG021', 'id_kategori' => 2, 'nama_barang' => 'Pocari Sweat Isotonik Botol 500ml',    'harga_beli' => 6500,   'harga_jual' => 8500,   'stok' => 40,  'satuan' => 'botol'],

            // Sembako & Kebutuhan Pokok (Kategori 3)
            ['kode_barang' => 'BRG022', 'id_kategori' => 3, 'nama_barang' => 'Beras Ramos Premium Pandan Wangi 5kg', 'harga_beli' => 68000,  'harga_jual' => 76000,  'stok' => 18,  'satuan' => 'karung'],
            ['kode_barang' => 'BRG023', 'id_kategori' => 3, 'nama_barang' => 'Minyak Goreng Bimoli Pouch 1 Liter',   'harga_beli' => 17000,  'harga_jual' => 20000,  'stok' => 25,  'satuan' => 'pouch'],
            ['kode_barang' => 'BRG024', 'id_kategori' => 3, 'nama_barang' => 'Gula Pasir Kristal Putih Gulaku 1kg',  'harga_beli' => 15500,  'harga_jual' => 18000,  'stok' => 30,  'satuan' => 'kg'],
            ['kode_barang' => 'BRG025', 'id_kategori' => 3, 'nama_barang' => 'Tepung Terigu Segitiga Biru 1kg',      'harga_beli' => 11000,  'harga_jual' => 13500,  'stok' => 22,  'satuan' => 'kg'],
            ['kode_barang' => 'BRG026', 'id_kategori' => 3, 'nama_barang' => 'Kecap Manis Bango Refill 550ml',      'harga_beli' => 21000,  'harga_jual' => 24500,  'stok' => 16,  'satuan' => 'pouch'],
            ['kode_barang' => 'BRG027', 'id_kategori' => 3, 'nama_barang' => 'Garam Dapur Beryodium Halus 250gr',    'harga_beli' => 2500,   'harga_jual' => 3500,   'stok' => 45,  'satuan' => 'bungkus'],
            ['kode_barang' => 'BRG028', 'id_kategori' => 3, 'nama_barang' => 'Telur Ayam Ras Segar 1kg (Isi 16)',    'harga_beli' => 27000,  'harga_jual' => 31000,  'stok' => 15,  'satuan' => 'kg'],

            // Merchandise KOPMA (Kategori 4)
            ['kode_barang' => 'BRG029', 'id_kategori' => 4, 'nama_barang' => 'Kaos Koperasi Mahasiswa Cotton 30s',  'harga_beli' => 50000,  'harga_jual' => 68000,  'stok' => 28,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG030', 'id_kategori' => 4, 'nama_barang' => 'Tote Bag Kanvas Premium Kampus KOPMA', 'harga_beli' => 25000,  'harga_jual' => 36000,  'stok' => 32,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG031', 'id_kategori' => 4, 'nama_barang' => 'Tumbler Stainless Vacuum 500ml KOPMA','harga_beli' => 35000,  'harga_jual' => 49000,  'stok' => 20,  'satuan' => 'unit'],
            ['kode_barang' => 'BRG032', 'id_kategori' => 4, 'nama_barang' => 'Gantungan Kunci Akrilik KOPMA Eksklusif','harga_beli' => 6000, 'harga_jual' => 10000,  'stok' => 50,  'satuan' => 'pcs'],
            ['kode_barang' => 'BRG033', 'id_kategori' => 4, 'nama_barang' => 'Stiker Vinyl Logo Kampus UV Cut-Out',  'harga_beli' => 1500,   'harga_jual' => 3000,   'stok' => 160, 'satuan' => 'lembar'],
            ['kode_barang' => 'BRG034', 'id_kategori' => 4, 'nama_barang' => 'Lanyard ID Card Kampus KOPMA Printing', 'harga_beli' => 8000,   'harga_jual' => 15000,  'stok' => 45,  'satuan' => 'pcs'],

            // Aksesoris & Gadget (Kategori 5)
            ['kode_barang' => 'BRG035', 'id_kategori' => 5, 'nama_barang' => 'Flashdisk SanDisk Cruzer Blade 32GB', 'harga_beli' => 45000,  'harga_jual' => 58000,  'stok' => 2,   'satuan' => 'unit'], // Stok kritis
            ['kode_barang' => 'BRG036', 'id_kategori' => 5, 'nama_barang' => 'Kabel Data Fast Charging Type-C 1M',  'harga_beli' => 18000,  'harga_jual' => 26000,  'stok' => 24,  'satuan' => 'unit'],
            ['kode_barang' => 'BRG037', 'id_kategori' => 5, 'nama_barang' => 'Earphone Stereo In-Ear Jack 3.5mm',    'harga_beli' => 22000,  'harga_jual' => 32000,  'stok' => 19,  'satuan' => 'unit'],
            ['kode_barang' => 'BRG038', 'id_kategori' => 5, 'nama_barang' => 'OTG Adapter USB to Type-C Metal',     'harga_beli' => 8000,   'harga_jual' => 14000,  'stok' => 35,  'satuan' => 'unit'],
            ['kode_barang' => 'BRG039', 'id_kategori' => 5, 'nama_barang' => 'Mouse Wireless Ergonomis 2.4GHz',     'harga_beli' => 42000,  'harga_jual' => 56000,  'stok' => 12,  'satuan' => 'unit'],
            ['kode_barang' => 'BRG040', 'id_kategori' => 5, 'nama_barang' => 'Mousepad Polos Anti Slip Hitam',       'harga_beli' => 7000,   'harga_jual' => 12000,  'stok' => 40,  'satuan' => 'pcs'],
        ];

        foreach ($barangList as $b) {
            Barang::updateOrCreate(['kode_barang' => $b['kode_barang']], $b);
        }

        // ============================================================
        // 4. SEED 30 ANGGOTA KOPMA LENGKAP & REALISTIS
        // ============================================================
        $anggotaList = [
            ['id_anggota' => 'ANG-056', 'nim' => '24430056', 'nama' => 'Annisa Choirunnisa',  'jurusan' => 'Teknik Informatika',         'no_hp' => '081234567056', 'email' => 'annisa.24430056@kampus.ac.id', 'tgl_bergabung' => '2024-09-01', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-001', 'nim' => '24430001', 'nama' => 'Budi Santoso',         'jurusan' => 'Sistem Informasi',           'no_hp' => '081234567001', 'email' => 'budi.001@kampus.ac.id',         'tgl_bergabung' => '2024-09-02', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-002', 'nim' => '24430012', 'nama' => 'Citra Lestari',        'jurusan' => 'Manajemen Bisnis',           'no_hp' => '081234567002', 'email' => 'citra.012@kampus.ac.id',        'tgl_bergabung' => '2024-09-05', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-003', 'nim' => '24430018', 'nama' => 'Dedi Pratama',         'jurusan' => 'Teknik Elektro',             'no_hp' => '081234567003', 'email' => 'dedi.018@kampus.ac.id',         'tgl_bergabung' => '2024-09-10', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-004', 'nim' => '24430025', 'nama' => 'Eka Rahmawati',        'jurusan' => 'Akuntansi',                  'no_hp' => '081234567004', 'email' => 'eka.025@kampus.ac.id',          'tgl_bergabung' => '2024-09-12', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-005', 'nim' => '24430030', 'nama' => 'Fajar Hidayat',        'jurusan' => 'Ilmu Komunikasi',            'no_hp' => '081234567005', 'email' => 'fajar.030@kampus.ac.id',        'tgl_bergabung' => '2024-09-15', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-006', 'nim' => '24430035', 'nama' => 'Gita Permata',         'jurusan' => 'Teknik Informatika',         'no_hp' => '081234567006', 'email' => 'gita.035@kampus.ac.id',         'tgl_bergabung' => '2024-09-18', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-007', 'nim' => '24430042', 'nama' => 'Hendra Wijaya',        'jurusan' => 'Sistem Informasi',           'no_hp' => '081234567007', 'email' => 'hendra.042@kampus.ac.id',       'tgl_bergabung' => '2024-09-20', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-008', 'nim' => '24430048', 'nama' => 'Indah Kusuma',         'jurusan' => 'Desain Komunikasi Visual',   'no_hp' => '081234567008', 'email' => 'indah.048@kampus.ac.id',        'tgl_bergabung' => '2024-09-22', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-009', 'nim' => '24430050', 'nama' => 'Joko Susilo',          'jurusan' => 'Manajemen',                  'no_hp' => '081234567009', 'email' => 'joko.050@kampus.ac.id',         'tgl_bergabung' => '2024-09-25', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-010', 'nim' => '24430052', 'nama' => 'Kartika Sari',         'jurusan' => 'Teknik Industri',            'no_hp' => '081234567010', 'email' => 'kartika.052@kampus.ac.id',      'tgl_bergabung' => '2024-09-28', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-011', 'nim' => '24430054', 'nama' => 'Lukman Hakim',         'jurusan' => 'Teknik Informatika',         'no_hp' => '081234567011', 'email' => 'lukman.054@kampus.ac.id',       'tgl_bergabung' => '2024-10-01', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-012', 'nim' => '24430058', 'nama' => 'Maya Anggraini',       'jurusan' => 'Akuntansi',                  'no_hp' => '081234567012', 'email' => 'maya.058@kampus.ac.id',         'tgl_bergabung' => '2024-10-03', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-013', 'nim' => '24430060', 'nama' => 'Naufal Rizqi',         'jurusan' => 'Sistem Informasi',           'no_hp' => '081234567013', 'email' => 'naufal.060@kampus.ac.id',       'tgl_bergabung' => '2024-10-05', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-014', 'nim' => '24430062', 'nama' => 'Olivia Putri',         'jurusan' => 'Teknik Elektro',             'no_hp' => '081234567014', 'email' => 'olivia.062@kampus.ac.id',       'tgl_bergabung' => '2024-10-08', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-015', 'nim' => '24430065', 'nama' => 'Panji Gumilang',       'jurusan' => 'Teknik Mesin',               'no_hp' => '081234567015', 'email' => 'panji.065@kampus.ac.id',        'tgl_bergabung' => '2024-10-10', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-016', 'nim' => '24430068', 'nama' => 'Rina Marlina',         'jurusan' => 'Ekonomi Pembangunan',        'no_hp' => '081234567016', 'email' => 'rina.068@kampus.ac.id',         'tgl_bergabung' => '2024-10-12', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-017', 'nim' => '24430070', 'nama' => 'Satria Pratama',       'jurusan' => 'Hubungan Internasional',     'no_hp' => '081234567017', 'email' => 'satria.070@kampus.ac.id',       'tgl_bergabung' => '2024-10-15', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-018', 'nim' => '24430072', 'nama' => 'Tia Amalia',           'jurusan' => 'Ilmu Komunikasi',            'no_hp' => '081234567018', 'email' => 'tia.072@kampus.ac.id',          'tgl_bergabung' => '2024-10-18', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-019', 'nim' => '24430075', 'nama' => 'Wahyu Hidayat',        'jurusan' => 'Teknik Sipil',               'no_hp' => '081234567019', 'email' => 'wahyu.075@kampus.ac.id',        'tgl_bergabung' => '2024-10-20', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-020', 'nim' => '24430080', 'nama' => 'Zahra Amelia',         'jurusan' => 'Teknik Informatika',         'no_hp' => '081234567020', 'email' => 'zahra.080@kampus.ac.id',        'tgl_bergabung' => '2024-10-25', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-021', 'nim' => '24430082', 'nama' => 'Agus Priyono',         'jurusan' => 'Manajemen Bisnis',           'no_hp' => '081234567021', 'email' => 'agus.082@kampus.ac.id',         'tgl_bergabung' => '2024-11-01', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-022', 'nim' => '24430085', 'nama' => 'Bella Safira',         'jurusan' => 'Akuntansi',                  'no_hp' => '081234567022', 'email' => 'bella.085@kampus.ac.id',        'tgl_bergabung' => '2024-11-05', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-023', 'nim' => '24430088', 'nama' => 'Cahyo Nugroho',        'jurusan' => 'Teknik Elektro',             'no_hp' => '081234567023', 'email' => 'cahyo.088@kampus.ac.id',        'tgl_bergabung' => '2024-11-10', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-024', 'nim' => '24430090', 'nama' => 'Dina Mariana',         'jurusan' => 'Desain Produk',              'no_hp' => '081234567024', 'email' => 'dina.090@kampus.ac.id',         'tgl_bergabung' => '2024-11-15', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-025', 'nim' => '24430092', 'nama' => 'Eko Prasetyo',         'jurusan' => 'Teknik Mesin',               'no_hp' => '081234567025', 'email' => 'eko.092@kampus.ac.id',          'tgl_bergabung' => '2024-11-20', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-026', 'nim' => '24430094', 'nama' => 'Fitri Handayani',      'jurusan' => 'Sistem Informasi',           'no_hp' => '081234567026', 'email' => 'fitri.094@kampus.ac.id',        'tgl_bergabung' => '2024-11-25', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-027', 'nim' => '24430096', 'nama' => 'Gilang Ramadhan',      'jurusan' => 'Ilmu Komunikasi',            'no_hp' => '081234567027', 'email' => 'gilang.096@kampus.ac.id',       'tgl_bergabung' => '2024-12-01', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-028', 'nim' => '24430098', 'nama' => 'Hani Puspitasari',     'jurusan' => 'Teknik Informatika',         'no_hp' => '081234567028', 'email' => 'hani.098@kampus.ac.id',        'tgl_bergabung' => '2024-12-05', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-029', 'nim' => '24430100', 'nama' => 'Irfan Maulana',        'jurusan' => 'Manajemen',                  'no_hp' => '081234567029', 'email' => 'irfan.100@kampus.ac.id',        'tgl_bergabung' => '2024-12-10', 'status' => 'aktif'],
        ];

        foreach ($anggotaList as $a) {
            Anggota::updateOrCreate(['id_anggota' => $a['id_anggota']], $a);
        }

        // ============================================================
        // 5. SEED DATA SIMPANAN ANGGOTA LENGKAP
        // ============================================================
        Simpanan::truncate();
        $months = [
            '2024-09-10', '2024-10-10', '2024-11-10', '2024-12-10',
            '2025-01-10', '2025-02-10', '2025-03-10', '2025-04-10',
            '2025-05-10', '2025-06-10', '2025-07-10', '2025-08-10',
            '2025-09-10', '2025-10-10', '2025-11-10', '2025-12-10',
            '2026-01-10', '2026-02-10', '2026-03-10', '2026-04-10',
            '2026-05-10', '2026-06-10', '2026-07-10', '2026-08-10',
            '2026-09-10', '2026-10-10'
        ];

        foreach ($anggotaList as $idx => $a) {
            // 1. Simpanan Pokok
            Simpanan::create([
                'id_anggota'     => $a['id_anggota'],
                'jenis_simpanan' => 'pokok',
                'nominal'        => 100000,
                'tanggal'        => $a['tgl_bergabung'],
                'keterangan'     => 'Simpanan pokok pendaftaran anggota resmi KOPMA',
            ]);

            // 2. Simpanan Wajib Rutin
            foreach ($months as $mDate) {
                if ($mDate >= $a['tgl_bergabung']) {
                    Simpanan::create([
                        'id_anggota'     => $a['id_anggota'],
                        'jenis_simpanan' => 'wajib',
                        'nominal'        => 25000,
                        'tanggal'        => $mDate,
                        'keterangan'     => 'Simpanan wajib bulan ' . Carbon::parse($mDate)->translatedFormat('F Y'),
                    ]);
                }
            }

            // 3. Simpanan Sukarela berkala untuk anggota aktif
            if (in_array($a['id_anggota'], ['ANG-056', 'ANG-001', 'ANG-002', 'ANG-004', 'ANG-007', 'ANG-012', 'ANG-020'])) {
                Simpanan::create([
                    'id_anggota'     => $a['id_anggota'],
                    'jenis_simpanan' => 'sukarela',
                    'nominal'        => ($idx + 2) * 50000,
                    'tanggal'        => '2026-04-15',
                    'keterangan'     => 'Setoran sukarela tabungan anggota semester genap',
                ]);
                Simpanan::create([
                    'id_anggota'     => $a['id_anggota'],
                    'jenis_simpanan' => 'sukarela',
                    'nominal'        => ($idx + 3) * 60000,
                    'tanggal'        => '2026-08-20',
                    'keterangan'     => 'Setoran sukarela tambahan tabungan modal bersama',
                ]);
            }
        }

        // ============================================================
        // 6. SEED DATA PINJAMAN (Pinjaman Sehat, Lunas & Macet)
        // ============================================================
        Pinjaman::truncate();
        $pinjamanList = [
            [
                'id_anggota'         => 'ANG-056', // Annisa (Sehat)
                'jumlah_pinjaman'    => 2000000,
                'sisa_pinjaman'      => 400000,
                'angsuran_per_bulan' => 200000,
                'tgl_pinjaman'       => '2025-01-10',
                'tgl_jatuh_tempo'    => '2026-01-10',
                'status'             => 'aktif',
                'keterangan'         => 'Pinjaman modal usaha koperasi mahasiswa mandiri',
            ],
            [
                'id_anggota'         => 'ANG-003', // Dedi Pratama (KREDIT MACET)
                'jumlah_pinjaman'    => 3500000,
                'sisa_pinjaman'      => 3500000,
                'angsuran_per_bulan' => 350000,
                'tgl_pinjaman'       => '2025-05-01',
                'tgl_jatuh_tempo'    => '2026-05-01',
                'status'             => 'macet',
                'keterangan'         => 'Peringatan SP-3: Menunggak pembayaran angsuran lebih dari 90 hari',
            ],
            [
                'id_anggota'         => 'ANG-005', // Fajar Hidayat (KREDIT MACET)
                'jumlah_pinjaman'    => 2500000,
                'sisa_pinjaman'      => 2100000,
                'angsuran_per_bulan' => 250000,
                'tgl_pinjaman'       => '2025-06-15',
                'tgl_jatuh_tempo'    => '2026-06-15',
                'status'             => 'macet',
                'keterangan'         => 'Peringatan SP-2: Tidak ada konfirmasi pembayaran 60 hari',
            ],
            [
                'id_anggota'         => 'ANG-001', // Budi Santoso (Lunas)
                'jumlah_pinjaman'    => 1000000,
                'sisa_pinjaman'      => 0,
                'angsuran_per_bulan' => 200000,
                'tgl_pinjaman'       => '2024-10-01',
                'tgl_jatuh_tempo'    => '2025-03-01',
                'status'             => 'lunas',
                'keterangan'         => 'Pinjaman telah dilunasi tepat waktu',
            ],
            [
                'id_anggota'         => 'ANG-007', // Hendra Wijaya (Aktif Berjalan)
                'jumlah_pinjaman'    => 4000000,
                'sisa_pinjaman'      => 1600000,
                'angsuran_per_bulan' => 400000,
                'tgl_pinjaman'       => '2025-07-01',
                'tgl_jatuh_tempo'    => '2026-07-01',
                'status'             => 'aktif',
                'keterangan'         => 'Pinjaman pengadaan laptop & alat kuliah',
            ],
            [
                'id_anggota'         => 'ANG-015', // Panji Gumilang (Lunas)
                'jumlah_pinjaman'    => 1500000,
                'sisa_pinjaman'      => 0,
                'angsuran_per_bulan' => 300000,
                'tgl_pinjaman'       => '2025-02-01',
                'tgl_jatuh_tempo'    => '2025-07-01',
                'status'             => 'lunas',
                'keterangan'         => 'Pinjaman biaya praktikum dan riset skripsi',
            ],
        ];
        foreach ($pinjamanList as $p) {
            Pinjaman::create($p);
        }

        // ============================================================
        // 7. SEED DATA SHU (SISA HASIL USAHA)
        // ============================================================
        Shu::truncate();
        $shuList = [
            ['id_anggota' => 'ANG-056', 'tahun' => 2024, 'shu_jasa_simpanan' => 125000, 'shu_jasa_usaha' => 180000, 'total_shu' => 305000, 'status' => 'sudah_dibagi', 'tgl_pembagian' => '2025-03-15'],
            ['id_anggota' => 'ANG-056', 'tahun' => 2025, 'shu_jasa_simpanan' => 175000, 'shu_jasa_usaha' => 240000, 'total_shu' => 415000, 'status' => 'belum_dibagi', 'tgl_pembagian' => null],
            ['id_anggota' => 'ANG-001', 'tahun' => 2024, 'shu_jasa_simpanan' => 95000,  'shu_jasa_usaha' => 140000, 'total_shu' => 235000, 'status' => 'sudah_dibagi', 'tgl_pembagian' => '2025-03-15'],
            ['id_anggota' => 'ANG-001', 'tahun' => 2025, 'shu_jasa_simpanan' => 120000, 'shu_jasa_usaha' => 165000, 'total_shu' => 285000, 'status' => 'belum_dibagi', 'tgl_pembagian' => null],
            ['id_anggota' => 'ANG-002', 'tahun' => 2025, 'shu_jasa_simpanan' => 110000, 'shu_jasa_usaha' => 150000, 'total_shu' => 260000, 'status' => 'belum_dibagi', 'tgl_pembagian' => null],
            ['id_anggota' => 'ANG-004', 'tahun' => 2025, 'shu_jasa_simpanan' => 135000, 'shu_jasa_usaha' => 195000, 'total_shu' => 330000, 'status' => 'belum_dibagi', 'tgl_pembagian' => null],
            ['id_anggota' => 'ANG-007', 'tahun' => 2025, 'shu_jasa_simpanan' => 145000, 'shu_jasa_usaha' => 210000, 'total_shu' => 355000, 'status' => 'belum_dibagi', 'tgl_pembagian' => null],
            ['id_anggota' => 'ANG-012', 'tahun' => 2025, 'shu_jasa_simpanan' => 105000, 'shu_jasa_usaha' => 160000, 'total_shu' => 265000, 'status' => 'belum_dibagi', 'tgl_pembagian' => null],
        ];
        foreach ($shuList as $sh) {
            Shu::create($sh);
        }

        // ============================================================
        // 8. SEED RATUSAN TRANSAKSI PENJUALAN HISTORIS (700+ TRANSAKSI)
        // ============================================================
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DetailTransaksi::truncate();
        Transaksi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $allBarangObj = Barang::all()->keyBy('kode_barang');
        $availableCodes = $allBarangObj->keys()->toArray();
        $membersIds = array_column($anggotaList, 'id_anggota');

        // Rentang transaksi dari 1 Januari 2026 sampai 22 Oktober 2026 (sekitar 295 hari)
        $startDate = Carbon::create(2026, 1, 1);
        $endDate   = Carbon::create(2026, 10, 22);

        $trxCounter = 1;
        $curr = $startDate->copy();

        while ($curr->lte($endDate)) {
            // Rata-rata 2 - 4 transaksi per hari
            $dailyCount = rand(2, 4);

            for ($d = 0; $d < $dailyCount; $d++) {
                $hoursPool = [8, 9, 10, 11, 12, 12, 13, 13, 14, 15, 16, 17, 18, 19, 20];
                $hour = $hoursPool[array_rand($hoursPool)];
                $minute = rand(5, 55);
                $second = rand(10, 50);

                $trxTime = $curr->copy()->setTime($hour, $minute, $second);

                // Pembeli: 75% Member Koperasi, 25% Pelanggan Umum
                $isMember = (rand(1, 100) <= 75);
                $selectedAnggota = $isMember ? $membersIds[array_rand($membersIds)] : null;

                // Pilih 1 hingga 4 barang acak
                $itemCount = rand(1, 4);
                $pickedCodes = (array) array_rand(array_flip($availableCodes), $itemCount);

                $subtotal = 0;
                $lineItems = [];

                foreach ($pickedCodes as $c) {
                    $b = $allBarangObj[$c];
                    $qty = rand(1, 3);
                    $lineSub = $b->harga_jual * $qty;
                    $subtotal += $lineSub;

                    $lineItems[] = [
                        'kode_barang'  => $b->kode_barang,
                        'harga_satuan' => $b->harga_jual,
                        'jumlah'       => $qty,
                        'subtotal'     => $lineSub,
                    ];
                }

                $diskon = ($subtotal > 50000 && rand(1, 3) == 1) ? 2000 : 0;
                $totalBayar = max(0, $subtotal - $diskon);

                // Metode Pembayaran (Tunai, QRIS, VA / Digital)
                $methodRoll = rand(1, 100);
                if ($methodRoll <= 45) {
                    $paymentMethod = 'tunai';
                    $nominalUang = (ceil($totalBayar / 10000) * 10000 + (rand(0, 1) ? 10000 : 0));
                    $kembalian = max(0, $nominalUang - $totalBayar);
                } elseif ($methodRoll <= 80) {
                    $paymentMethod = 'qris';
                    $nominalUang = $totalBayar;
                    $kembalian = 0;
                } else {
                    $paymentMethod = 'va';
                    $nominalUang = $totalBayar;
                    $kembalian = 0;
                }

                $fakturStr = sprintf("TRX-%s-%03d", $trxTime->format('Ymd'), $trxCounter);

                $trx = Transaksi::create([
                    'no_faktur'     => $fakturStr,
                    'id_anggota'    => $selectedAnggota,
                    'user_id'       => $kasir->id,
                    'tgl_transaksi' => $trxTime,
                    'total_belanja' => $subtotal,
                    'diskon'        => $diskon,
                    'total_bayar'   => $totalBayar,
                    'nominal_uang'  => $nominalUang,
                    'kembalian'     => $kembalian,
                ]);

                foreach ($lineItems as $item) {
                    DetailTransaksi::create([
                        'id_transaksi' => $trx->id_transaksi,
                        'kode_barang'  => $item['kode_barang'],
                        'harga_satuan' => $item['harga_satuan'],
                        'jumlah'       => $item['jumlah'],
                        'subtotal'     => $item['subtotal'],
                    ]);
                }

                $trxCounter++;
            }

            $curr->addDay();
        }
    }
}
