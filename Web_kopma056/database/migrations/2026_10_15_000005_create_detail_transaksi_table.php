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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksi');
            $table->string('kode_barang', 20);
            $table->integer('harga_satuan')->default(0);
            $table->integer('jumlah')->default(1);
            $table->integer('subtotal')->default(0);
            $table->timestamps();

            $table->foreign('id_transaksi')
                  ->references('id_transaksi')
                  ->on('transaksi')
                  ->onDelete('cascade');

            $table->foreign('kode_barang')
                  ->references('kode_barang')
                  ->on('barang')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
