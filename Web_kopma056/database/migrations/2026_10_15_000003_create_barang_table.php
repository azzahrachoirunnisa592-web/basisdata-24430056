<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('kode_barang', 20)->primary();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('nama_barang', 100);
            $table->integer('harga_beli')->default(0);
            $table->integer('harga_jual')->default(0);
            $table->integer('stok')->default(0);
            $table->string('satuan', 20)->default('pcs');
            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
