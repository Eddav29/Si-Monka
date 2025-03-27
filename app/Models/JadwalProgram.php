<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalProgram extends Model
{
    use HasFactory;

    protected $table = 'jadwal_program';
    protected $fillable = [
        'program_id', 'desain', 'verifikasi', 'pbj', 'pelaksanaan', 'catatan'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
