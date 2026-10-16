<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Simpanan Anggota
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id('id_simpanan');
            $table->string('id_anggota', 20);
            $table->enum('jenis_simpanan', ['pokok', 'wajib', 'sukarela']);
            $table->bigInteger('nominal');
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');
        });

        // Tabel Pinjaman Anggota
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id('id_pinjaman');
            $table->string('id_anggota', 20);
            $table->bigInteger('jumlah_pinjaman');
            $table->bigInteger('sisa_pinjaman');
            $table->bigInteger('angsuran_per_bulan');
            $table->date('tgl_pinjaman');
            $table->date('tgl_jatuh_tempo');
            $table->enum('status', ['aktif', 'lunas', 'macet'])->default('aktif');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');
        });

        // Tabel SHU (Sisa Hasil Usaha)
        Schema::create('shu', function (Blueprint $table) {
            $table->id('id_shu');
            $table->string('id_anggota', 20);
            $table->year('tahun');
            $table->bigInteger('shu_jasa_simpanan');
            $table->bigInteger('shu_jasa_usaha');
            $table->bigInteger('total_shu');
            $table->enum('status', ['belum_dibagi', 'sudah_dibagi'])->default('belum_dibagi');
            $table->date('tgl_pembagian')->nullable();
            $table->timestamps();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shu');
        Schema::dropIfExists('pinjaman');
        Schema::dropIfExists('simpanan');
    }
};
