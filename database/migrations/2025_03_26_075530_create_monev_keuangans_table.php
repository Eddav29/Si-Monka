<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('monev_keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pekerjaan_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_monitoring', ['perencanaan', 'verifikasi', 'pengadaan', 'pelaksanaan', 'laporan']);
            $table->enum('status_monitoring', ['Belum Dimulai', 'Sedang Berjalan', 'Selesai']);
            $table->double('program');
            $table->double('realisasi');
            $table->text('evaluasi')->nullable();
            $table->string('pic');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monev_keuangan');
    }
};
