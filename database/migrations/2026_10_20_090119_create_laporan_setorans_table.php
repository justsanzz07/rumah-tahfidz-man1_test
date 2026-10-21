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
        Schema::create('laporan_setorans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu_setoran');
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->nullOnDelete();
            $table->string('nama_petugas');
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->nullOnDelete();
            $table->string('nama_siswa');
            $table->string('kelas');
            $table->string('jurusan')->nullable();
            $table->string('tahun_ajaran')->default('2026/2027');
            $table->string('kategori'); // Ziyadah, Murojaah
            $table->string('surah');
            $table->string('ayat');
            $table->string('nilai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_setorans');
    }
};
