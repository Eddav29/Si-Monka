<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $table = 'pekerjaan';
    protected $fillable = [
        'nama_pekerjaan', 'volume', 'satuan', 'sumber_keterangan',
        'sub_unit', 'jenis_op', 'nilai_paket_pekerjaan', 'jadwal_mulai',
        'jadwal_selesai', 'status_proses', 'status_gr', 'keterangan',
        'user_id', 'jenis_investasi', 'div', 'nilai_item_investasi', 'pbj'
    ];

    public function keuangan()
    {
        return $this->hasOne(Keuangan::class);
    }

    public function program()
    {
        return $this->hasOne(Program::class);
    }

    public function monevKeuangan()
    {
        return $this->hasMany(MonevKeuangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
