# Dokumen Kebutuhan Data - Toko Online Annisa (TOA)

## 1. Proses Bisnis (Minimal 4)
1. **Pendaftaran & Kelola Pelanggan:** Registrasi akun baru, pembaruan profil, dan verifikasi data identitas pelanggan.
2. **Pengelolaan Katalog Produk:** Penambahan produk baru, kategori barang, penyesuaian harga, dan pembaruan stok.
3. **Pemesanan & Transaksi Penjualan:** Pemilihan barang oleh pelanggan, pembuatan pesanan, kalkulasi total biaya, dan penerbitan nota/faktur.
4. **Pembayaran & Pengiriman Barang:** Verifikasi bukti pembayaran, pemrosesan resi pengiriman, serta pembaruan status pengiriman pesanan.

## 2. Entitas Kandidat (Minimal 6)
1. `Pelanggan`
2. `Kategori`
3. `Produk`
4. `Pesanan`
5. `Detail_Pesanan`
6. `Pembayaran`
7. `Pengiriman`

## 3. Aturan Bisnis (Minimal 8)
1. Setiap pelanggan harus memiliki email dan nomor telepon yang unik.
2. Satu produk wajib terikat pada satu kategori barang.
3. Harga produk harus berpotongan harga positif (tidak boleh bernilai negatif atau 0).
4. Pesanan hanya dapat dibuat oleh pelanggan yang terdaftar.
5. Satu transaksi pesanan dapat terdiri dari satu atau beberapa produk (detail pesanan).
6. Stok produk akan berkurang secara otomatis ketika pesanan berhasil dikonfirmasi.
7. Pembayaran harus dilakukan sesuai dengan total nominal pada pesanan sebelum batas waktu habis.
8. Nomor resi pengiriman dipenerbitkan setelah pembayaran terverifikasi.

## 4. Kebutuhan Informasi (Minimal 5)
1. Laporan rekapitulasi penjualan bulanan per kategori produk.
2. Daftar stok produk yang berada di bawah batas minimum (butuh *restock*).
3. Riwayat transaksi belanja per pelanggan beserta status pembayarannya.
4. Laporan pendapatan harian berdasarkan metode pembayaran.
5. Daftar pesanan yang sedang dalam proses pengiriman beserta nomor resinya.

## 5. Matriks CRUD
| Entitas | Create | Read | Update | Delete | Alasan/Catatan khusus |
| :--- | :---: | :---: | :---: | :---: | :--- |
| `Pelanggan` | v | v | v | v | Pendaftaran, lihat profil, ubah data, dan hapus akun. |
| `Kategori` | v | v | v | v | Pengelolaan kategori oleh admin. |
| `Produk` | v | v | v | v | Pengelolaan master data produk. |
| `Pesanan` | v | v | v | x | Pesanan yang sudah dibuat tidak boleh dihapus (*audit trail*), hanya diubah statusnya. |
| `Detail_Pesanan` | v | v | v | x | Rincian item tidak dihapus demi menjaga integritas riwayat nota. |
| `Pembayaran` | v | v | v | x | Catatan transaksi keuangan dilarang keras untuk dihapus. |
| `Pengiriman` | v | v | v | x | Riwayat logistik disimpan untuk rekam jejak pengiriman. |

## 6. Kamus Data Awal (20 Elemen)
| No | Nama Elemen | Tipe Data | Deskripsi | Penanggung Jawab |
| :-: | :--- | :--- | :--- | :--- |
| 1 | `id_pelanggan` | VARCHAR(10) | ID unik untuk setiap pelanggan | Tim Sistem / Admin |
| 2 | `nama_lengkap` | VARCHAR(100) | Nama lengkap pelanggan | Pelanggan |
| 3 | `email` | VARCHAR(100) | Alamat email aktif pelanggan | Pelanggan |
| 4 | `no_hp` | VARCHAR(15) | Nomor kontak telepon/WA | Pelanggan |
| 5 | `alamat_kirim` | TEXT | Alamat pengiriman pesanan | Pelanggan |
| 6 | `id_kategori` | VARCHAR(5) | Kode unik kategori produk | Admin Katalog |
| 7 | `nama_kategori` | VARCHAR(50) | Nama kelompok/kategori barang | Admin Katalog |
| 8 | `kode_produk` | VARCHAR(10) | Kode unik identitas produk | Admin Katalog |
| 9 | `nama_produk` | VARCHAR(100) | Nama barang yang dijual | Admin Katalog |
| 10 | `harga_satuan` | INT | Harga jual barang per unit | Admin Katalog |
| 11 | `stok_barang` | INT | Jumlah ketersediaan fisik barang | Admin Gudang |
| 12 | `id_pesanan` | VARCHAR(15) | Nomor nota/faktur transaksi | Sistem Otomatis |
| 13 | `tgl_pesanan` | DATETIME | Waktu transaksi dilakukan | Sistem Otomatis |
| 14 | `total_harga` | INT | Total akumulasi pembayaran | Sistem Otomatis |
| 15 | `status_pesanan` | VARCHAR(20) | Status (Menunggu/Lunas/Kirim) | Admin Penjualan |
| 16 | `id_detail` | INT | ID baris rincian barang pesanan | Sistem Otomatis |
| 17 | `jumlah_beli` | INT | Kuantitas barang yang dibeli | Pelanggan |
| 18 | `id_pembayaran` | VARCHAR(15) | Kode referensi pembayaran | Tim Keuangan |
| 19 | `metode_bayar` | VARCHAR(20) | Metode (Transfer/QRIS/E-Wallet) | Pelanggan |
| 20 | `no_resi` | VARCHAR(30) | Nomor resi pelacakan kurir | Tim Logistik |

## 7. Kebutuhan Non-Fungsional & Data Pribadi
* **Identifikasi Data Pribadi (PII):** `nama_lengkap`, `email`, `no_hp`, dan `alamat_kirim`.
* **Aturan Akses Data:** 
  * Pelanggan hanya dapat melihat dan mengedit data pribadi milik mereka sendiri.
  * Admin Operasional dapat melihat `nama_lengkap` dan `alamat_kirim` untuk keperluan pengiriman barang.
  * Pihak ketiga (kurir/ekspedisi) hanya menerima cetakan label berupa `nama_lengkap`, `no_hp`, dan `alamat_kirim`.
  * Data kata sandi (*password*) wajib dienkripsi menggunakan *hashing* sebelum disimpan ke basis data.

## 8. Dokumen Sumber Fiktif (Bukti Pembayaran / Nota)
```text
==================================================
              TOKO ONLINE ANNISA (TOA)            
       Jl. Raya Utama No. 56, Kota Bandar Lampung 
==================================================
No. Pesanan : TOA-20261008-056
Tanggal     : 08 Oktober 2026
Pelanggan   : Annisa (081234567890)
--------------------------------------------------
Barang           Qty   Harga Satuan   Subtotal
--------------------------------------------------
Buku Tulis         2     Rp  5.000    Rp 10.000
Pulpen Black       5     Rp  3.000    Rp 15.000
--------------------------------------------------
Total Pembayaran                      Rp 25.000
Metode Pembayaran                     QRIS
Status                                LUNAS
==================================================