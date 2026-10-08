-- p02_ddl_24430056.sql
USE kopma_056;

-- Buat tabel anggota
CREATE TABLE IF NOT EXISTS anggota (
    id_anggota VARCHAR(10) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    no_hp VARCHAR(15),
    tgl_bergabung DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buat tabel barang
CREATE TABLE IF NOT EXISTS barang (
    kode_barang VARCHAR(10) PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tugas Modul 2: Buat tabel transaksi (Foreign Key)
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota VARCHAR(10),
    tgl_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_bayar INT DEFAULT 0,
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Modifikasi struktur tabel (ADD COLUMN & INDEX)
ALTER TABLE barang ADD COLUMN kategori VARCHAR(50) AFTER nama_barang;
CREATE INDEX idx_nama_barang ON barang(nama_barang);