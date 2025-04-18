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
        Schema::create('jadwal_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->date('desain');
            $table->date('verifikasi');
            $table->date('pbj');
            $table->date('pelaksanaan');
            $table->text('catatan')->nullable();
            $table->timestamps(); 
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_programs');
    }
};
