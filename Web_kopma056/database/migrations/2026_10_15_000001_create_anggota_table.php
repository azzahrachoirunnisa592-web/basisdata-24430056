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
        Schema::create('anggota', function (Blueprint $table) {
            $table->string('id_anggota', 15)->primary();
            $table->string('nim', 15)->unique()->nullable();
            $table->string('nama', 100);
            $table->string('jurusan', 50)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->date('tgl_bergabung')->default(now()->toDateString());
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
