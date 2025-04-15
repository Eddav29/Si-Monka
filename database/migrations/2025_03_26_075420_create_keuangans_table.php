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
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pekerjaan_id')->constrained()->onDelete('cascade');
            $table->decimal('rkap', 15, 2);
            $table->decimal('rkapt', 15, 2);
            $table->decimal('pjpsda', 15, 2);
            $table->decimal('rab', 15, 2);
            $table->string('nomor_io');
            $table->decimal('real_kontrak', 15, 2);
            $table->decimal('nilai_progres', 15, 2);
            $table->decimal('actual_spj', 15, 2);
            $table->decimal('actual_sap', 15, 2);
            $table->decimal('actual_pembayaran', 15, 2);
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangans');
    }
};
