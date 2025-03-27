<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonevKeuangan extends Model
{
    use HasFactory;

    protected $table = 'monev_keuangan';
    protected $fillable = [
        'pekerjaan_id', 'jenis_monitoring', 'status_monitoring',
        'program', 'realisasi', 'evaluasi', 'pic', 'user_id',
        'tanggal_mulai', 'tanggal_selesai'
    ];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisMonitoring()
    {
        return $this->belongsTo(JenisMonitoring::class, 'jenis_monitoring');
    }
}
