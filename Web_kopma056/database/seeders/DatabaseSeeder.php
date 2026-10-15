<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::updateOrCreate(
            ['email' => 'annisa@kopma.ac.id'],
            [
                'name' => 'Annisa Choirunnisa (24430056)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@kopma.ac.id'],
            [
                'name' => 'Petugas Kasir KOPMA',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ]
        );

        // 2. Seed Kategori
        $kategoriList = [
            ['id_kategori' => 1, 'nama_kategori' => 'Alat Tulis Kantor (ATK)', 'keterangan' => 'Perlengkapan tulis, buku, dan perkakas kuliah'],
            ['id_kategori' => 2, 'nama_kategori' => 'Makanan & Minuman', 'keterangan' => 'Snack, roti, minuman botol, dan makanan siap saji'],
            ['id_kategori' => 3, 'nama_kategori' => 'Merchandise KOPMA', 'keterangan' => 'Kaos, totebag, cinderamata resmi Koperasi Mahasiswa'],
            ['id_kategori' => 4, 'nama_kategori' => 'Aksesoris & Gadget', 'keterangan' => 'Flashdisk, kabel data, dan kebutuhan digital mahasiswa'],
        ];

        foreach ($kategoriList as $kat) {
            Kategori::updateOrCreate(['id_kategori' => $kat['id_kategori']], $kat);
        }

        // 3. Seed Barang (Tepat 16 Item Barang)
        $barangList = [
            ['kode_barang' => 'BRG001', 'id_kategori' => 1, 'nama_barang' => 'Buku Tulis Spiral A5 Hardcover', 'harga_beli' => 6000, 'harga_jual' => 8000, 'stok' => 45, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG002', 'id_kategori' => 1, 'nama_barang' => 'Pulpen Gel 0.5mm Hitam Joyko', 'harga_beli' => 2500, 'harga_jual' => 3500, 'stok' => 120, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG003', 'id_kategori' => 1, 'nama_barang' => 'Pensil 2B Faber-Castell Asli', 'harga_beli' => 3000, 'harga_jual' => 4000, 'stok' => 60, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG004', 'id_kategori' => 1, 'nama_barang' => 'Penghapus Joyko Dust-Free Hitam', 'harga_beli' => 1500, 'harga_jual' => 2500, 'stok' => 50, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG005', 'id_kategori' => 1, 'nama_barang' => 'Map Kertas Folio Stopmap Biola', 'harga_beli' => 1200, 'harga_jual' => 2000, 'stok' => 85, 'satuan' => 'lembar'],
            ['kode_barang' => 'BRG006', 'id_kategori' => 1, 'nama_barang' => 'Kertas HVS A4 80gr PaperOne', 'harga_beli' => 48000, 'harga_jual' => 55000, 'stok' => 18, 'satuan' => 'rim'],
            ['kode_barang' => 'BRG007', 'id_kategori' => 2, 'nama_barang' => 'Air Mineral Botol 600ml Dingin', 'harga_beli' => 2800, 'harga_jual' => 4000, 'stok' => 72, 'satuan' => 'botol'],
            ['kode_barang' => 'BRG008', 'id_kategori' => 2, 'nama_barang' => 'Kopi Susu Botol Dingin KOPMA', 'harga_beli' => 9000, 'harga_jual' => 12000, 'stok' => 35, 'satuan' => 'botol'],
            ['kode_barang' => 'BRG009', 'id_kategori' => 2, 'nama_barang' => 'Roti Bakar Coklat Keju Fresh', 'harga_beli' => 5000, 'harga_jual' => 7000, 'stok' => 24, 'satuan' => 'bungkus'],
            ['kode_barang' => 'BRG010', 'id_kategori' => 2, 'nama_barang' => 'Mie Instan Sedap Cup Kari Ayam', 'harga_beli' => 4800, 'harga_jual' => 6500, 'stok' => 48, 'satuan' => 'cup'],
            ['kode_barang' => 'BRG011', 'id_kategori' => 2, 'nama_barang' => 'Biskuit Gandum Coklat Roma', 'harga_beli' => 6500, 'harga_jual' => 8500, 'stok' => 30, 'satuan' => 'pack'],
            ['kode_barang' => 'BRG012', 'id_kategori' => 3, 'nama_barang' => 'Kaos Koperasi Mahasiswa Cotton 30s', 'harga_beli' => 50000, 'harga_jual' => 65000, 'stok' => 25, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG013', 'id_kategori' => 3, 'nama_barang' => 'Tote Bag Kanvas Premium Kampus', 'harga_beli' => 25000, 'harga_jual' => 35000, 'stok' => 30, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG014', 'id_kategori' => 3, 'nama_barang' => 'Gantungan Kunci Akrilik KOPMA', 'harga_beli' => 6000, 'harga_jual' => 10000, 'stok' => 40, 'satuan' => 'pcs'],
            ['kode_barang' => 'BRG015', 'id_kategori' => 3, 'nama_barang' => 'Stiker Vinyl Logo Kampus UV Cut', 'harga_beli' => 1500, 'harga_jual' => 3000, 'stok' => 150, 'satuan' => 'lembar'],
            ['kode_barang' => 'BRG016', 'id_kategori' => 4, 'nama_barang' => 'Flashdisk SanDisk Cruzer Blade 32GB', 'harga_beli' => 45000, 'harga_jual' => 58000, 'stok' => 15, 'satuan' => 'unit'],
        ];

        foreach ($barangList as $b) {
            Barang::updateOrCreate(['kode_barang' => $b['kode_barang']], $b);
        }

        // 4. Seed Anggota (14 Anggota Aktif + 2 Non-Aktif = 14 Aktif)
        $anggotaList = [
            ['id_anggota' => 'ANG-056', 'nim' => '24430056', 'nama' => 'Annisa Choirunnisa', 'jurusan' => 'Teknik Informatika', 'no_hp' => '081234567056', 'email' => 'annisa.24430056@kampus.ac.id', 'tgl_bergabung' => '2024-09-01', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-001', 'nim' => '24430001', 'nama' => 'Budi Santoso', 'jurusan' => 'Sistem Informasi', 'no_hp' => '081234567001', 'email' => 'budi.001@kampus.ac.id', 'tgl_bergabung' => '2024-09-02', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-002', 'nim' => '24430012', 'nama' => 'Citra Lestari', 'jurusan' => 'Manajemen Bisnis', 'no_hp' => '081234567002', 'email' => 'citra.012@kampus.ac.id', 'tgl_bergabung' => '2024-09-05', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-003', 'nim' => '24430018', 'nama' => 'Dedi Pratama', 'jurusan' => 'Teknik Elektro', 'no_hp' => '081234567003', 'email' => 'dedi.018@kampus.ac.id', 'tgl_bergabung' => '2024-09-10', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-004', 'nim' => '24430025', 'nama' => 'Eka Rahmawati', 'jurusan' => 'Akuntansi', 'no_hp' => '081234567004', 'email' => 'eka.025@kampus.ac.id', 'tgl_bergabung' => '2024-09-12', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-005', 'nim' => '24430030', 'nama' => 'Fajar Hidayat', 'jurusan' => 'Ilmu Komunikasi', 'no_hp' => '081234567005', 'email' => 'fajar.030@kampus.ac.id', 'tgl_bergabung' => '2024-09-15', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-006', 'nim' => '24430035', 'nama' => 'Gita Permata', 'jurusan' => 'Teknik Informatika', 'no_hp' => '081234567006', 'email' => 'gita.035@kampus.ac.id', 'tgl_bergabung' => '2024-09-18', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-007', 'nim' => '24430042', 'nama' => 'Hendra Wijaya', 'jurusan' => 'Sistem Informasi', 'no_hp' => '081234567007', 'email' => 'hendra.042@kampus.ac.id', 'tgl_bergabung' => '2024-09-20', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-008', 'nim' => '24430048', 'nama' => 'Indah Kusuma', 'jurusan' => 'Desain Komunikasi Visual', 'no_hp' => '081234567008', 'email' => 'indah.048@kampus.ac.id', 'tgl_bergabung' => '2024-09-22', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-009', 'nim' => '24430050', 'nama' => 'Joko Susilo', 'jurusan' => 'Manajemen', 'no_hp' => '081234567009', 'email' => 'joko.050@kampus.ac.id', 'tgl_bergabung' => '2024-09-25', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-010', 'nim' => '24430052', 'nama' => 'Kartika Sari', 'jurusan' => 'Teknik Industri', 'no_hp' => '081234567010', 'email' => 'kartika.052@kampus.ac.id', 'tgl_bergabung' => '2024-09-28', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-011', 'nim' => '24430054', 'nama' => 'Lukman Hakim', 'jurusan' => 'Teknik Informatika', 'no_hp' => '081234567011', 'email' => 'lukman.054@kampus.ac.id', 'tgl_bergabung' => '2024-10-01', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-012', 'nim' => '24430058', 'nama' => 'Maya Anggraini', 'jurusan' => 'Akuntansi', 'no_hp' => '081234567012', 'email' => 'maya.058@kampus.ac.id', 'tgl_bergabung' => '2024-10-03', 'status' => 'aktif'],
            ['id_anggota' => 'ANG-013', 'nim' => '24430060', 'nama' => 'Naufal Rizqi', 'jurusan' => 'Sistem Informasi', 'no_hp' => '081234567013', 'email' => 'naufal.060@kampus.ac.id', 'tgl_bergabung' => '2024-10-05', 'status' => 'aktif'],
            // 2 Anggota Non-Aktif
            ['id_anggota' => 'ANG-014', 'nim' => '24430070', 'nama' => 'Oki Kurniawan', 'jurusan' => 'Teknik Mesin', 'no_hp' => '081234567014', 'email' => 'oki.070@kampus.ac.id', 'tgl_bergabung' => '2023-08-15', 'status' => 'non-aktif'],
            ['id_anggota' => 'ANG-015', 'nim' => '24430080', 'nama' => 'Putri Handayani', 'jurusan' => 'Sastra Inggris', 'no_hp' => '081234567015', 'email' => 'putri.080@kampus.ac.id', 'tgl_bergabung' => '2023-08-20', 'status' => 'non-aktif'],
        ];

        foreach ($anggotaList as $a) {
            Anggota::updateOrCreate(['id_anggota' => $a['id_anggota']], $a);
        }

        // 5. Seed Transaksi Penjualan (Total Pendapatan Tepat Rp 237.000)
        $transaksiData = [
            [
                'no_faktur' => 'TRX-20261015-001',
                'id_anggota' => 'ANG-056', // Annisa
                'user_id' => $kasir->id,
                'tgl_transaksi' => Carbon::parse('2026-10-15 08:30:00'),
                'total_belanja' => 15000,
                'diskon' => 0,
                'total_bayar' => 15000,
                'nominal_uang' => 20000,
                'kembalian' => 5000,
                'items' => [
                    ['kode_barang' => 'BRG007', 'harga_satuan' => 4000, 'jumlah' => 2, 'subtotal' => 8000],
                    ['kode_barang' => 'BRG009', 'harga_satuan' => 7000, 'jumlah' => 1, 'subtotal' => 7000],
                ]
            ],
            [
                'no_faktur' => 'TRX-20261015-002',
                'id_anggota' => 'ANG-001', // Budi
                'user_id' => $kasir->id,
                'tgl_transaksi' => Carbon::parse('2026-10-15 09:45:00'),
                'total_belanja' => 65000,
                'diskon' => 0,
                'total_bayar' => 65000,
                'nominal_uang' => 100000,
                'kembalian' => 35000,
                'items' => [
                    ['kode_barang' => 'BRG012', 'harga_satuan' => 65000, 'jumlah' => 1, 'subtotal' => 65000],
                ]
            ],
            [
                'no_faktur' => 'TRX-20261015-003',
                'id_anggota' => 'ANG-002', // Citra
                'user_id' => $admin->id,
                'tgl_transaksi' => Carbon::parse('2026-10-15 11:15:00'),
                'total_belanja' => 58000,
                'diskon' => 0,
                'total_bayar' => 58000,
                'nominal_uang' => 60000,
                'kembalian' => 2000,
                'items' => [
                    ['kode_barang' => 'BRG016', 'harga_satuan' => 58000, 'jumlah' => 1, 'subtotal' => 58000],
                ]
            ],
            [
                'no_faktur' => 'TRX-20261015-004',
                'id_anggota' => 'ANG-003', // Dedi
                'user_id' => $kasir->id,
                'tgl_transaksi' => Carbon::parse('2026-10-15 13:20:00'),
                'total_belanja' => 38000,
                'diskon' => 0,
                'total_bayar' => 38000,
                'nominal_uang' => 50000,
                'kembalian' => 12000,
                'items' => [
                    ['kode_barang' => 'BRG001', 'harga_satuan' => 8000, 'jumlah' => 3, 'subtotal' => 24000],
                    ['kode_barang' => 'BRG002', 'harga_satuan' => 3500, 'jumlah' => 4, 'subtotal' => 14000],
                ]
            ],
            [
                'no_faktur' => 'TRX-20261015-005',
                'id_anggota' => null, // Pelanggan Umum
                'user_id' => $kasir->id,
                'tgl_transaksi' => Carbon::parse('2026-10-15 14:50:00'),
                'total_belanja' => 45000,
                'diskon' => 0,
                'total_bayar' => 45000,
                'nominal_uang' => 50000,
                'kembalian' => 5000,
                'items' => [
                    ['kode_barang' => 'BRG013', 'harga_satuan' => 35000, 'jumlah' => 1, 'subtotal' => 35000],
                    ['kode_barang' => 'BRG014', 'harga_satuan' => 10000, 'jumlah' => 1, 'subtotal' => 10000],
                ]
            ],
            [
                'no_faktur' => 'TRX-20261015-006',
                'id_anggota' => 'ANG-004', // Eka
                'user_id' => $kasir->id,
                'tgl_transaksi' => Carbon::parse('2026-10-15 16:10:00'),
                'total_belanja' => 16000,
                'diskon' => 0,
                'total_bayar' => 16000,
                'nominal_uang' => 20000,
                'kembalian' => 4000,
                'items' => [
                    ['kode_barang' => 'BRG008', 'harga_satuan' => 12000, 'jumlah' => 1, 'subtotal' => 12000],
                    ['kode_barang' => 'BRG007', 'harga_satuan' => 4000, 'jumlah' => 1, 'subtotal' => 4000],
                ]
            ],
        ];

        foreach ($transaksiData as $tData) {
            $items = $tData['items'];
            unset($tData['items']);

            $transaksi = Transaksi::updateOrCreate(
                ['no_faktur' => $tData['no_faktur']],
                $tData
            );

            foreach ($items as $item) {
                DetailTransaksi::updateOrCreate(
                    [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'kode_barang' => $item['kode_barang'],
                    ],
                    [
                        'harga_satuan' => $item['harga_satuan'],
                        'jumlah' => $item['jumlah'],
                        'subtotal' => $item['subtotal'],
                    ]
                );
            }
        }
    }
}
