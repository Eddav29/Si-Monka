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
        Schema::create('pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pekerjaan');
            $table->decimal('volume', 10, 2);
            $table->string('satuan');
            $table->string('sumber_keterangan');
            $table->string('sub_unit');
            $table->string('jenis_op');
            $table->decimal('nilai_paket_pekerjaan', 15, 2);
            $table->date('jadwal_mulai');
            $table->date('jadwal_selesai');
            $table->string('status_proses');
            $table->string('status_gr');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jenis_investasi');
            $table->string('div');
            $table->decimal('nilai_item_investasi', 15, 2);
            $table->string('pbj');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerjaans');
    }
};
