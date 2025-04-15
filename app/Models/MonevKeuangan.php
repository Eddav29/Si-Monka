<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MonevKeuangan extends Model
{
    use HasFactory;

    protected $table = 'monev_keuangans';
    protected $fillable = [
        'pekerjaan_id', 'jenis_monitoring', 'status_monitoring',
        'program', 'realisasi', 'evaluasi', 'pic', 'file', 'user_id',
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

    /**
     * file
     *
     * @return Attribute
     */
    protected function file(): Attribute
    {
        return Attribute::make(
            get: fn ($file) => url('/storage/products/' . $file),
        );
    }
}
