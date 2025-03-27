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
        Schema::create('jenis_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('perencanaan');
            $table->string('verifikasi');
            $table->string('pengadaan');
            $table->string('pelaksanaan');
            $table->string('laporan');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_monitorings');
    }
};
