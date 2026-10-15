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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('no_faktur', 30)->unique();
            $table->string('id_anggota', 15)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('tgl_transaksi')->useCurrent();
            $table->integer('total_belanja')->default(0);
            $table->integer('diskon')->default(0);
            $table->integer('total_bayar')->default(0);
            $table->integer('nominal_uang')->default(0);
            $table->integer('kembalian')->default(0);
            $table->timestamps();

            $table->foreign('id_anggota')
                  ->references('id_anggota')
                  ->on('anggota')
                  ->onDelete('set null');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
