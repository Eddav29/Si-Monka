<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';
    protected $fillable = [
        'pekerjaan_id', 'pelaksanaan_program', 'status_program', 'realisasi_program'
    ];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }

    public function jadwalProgram()
    {
        return $this->hasOne(JadwalProgram::class);
    }
}
