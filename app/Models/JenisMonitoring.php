<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisMonitoring extends Model
{
    use HasFactory;

    protected $table = 'jenis_monitoring';
    protected $fillable = [
        'perencanaan', 'verifikasi', 'pengadaan', 'pelaksanaan', 'laporan'
    ];
}
