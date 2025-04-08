<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangans';
    protected $fillable = [
        'pekerjaan_id', 'rkap', 'rkapt', 'pjpsda', 'rab',
        'nomor_io', 'real_kontrak', 'nilai_progres', 'actual_spj',
        'actual_sap', 'actual_pembayaran'
    ];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }
}
