<?php

namespace Database\Seeders;

use App\Models\Pekerjaan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user or create one if none exists
        $user = User::first() ?? User::factory()->create();
        
        // Sample data
        $pekerjaanData = [
            [
                'nama_pekerjaan' => 'Pembangunan Jalan',
                'volume' => 100,
                'satuan' => 'meter',
                'sumber_keterangan' => 'APBD',
                'sub_unit' => 'Unit A',
                'jenis_op' => 'Konstruksi',
                'nilai_paket_pekerjaan' => 500000000,
                'jadwal_mulai' => now(),
                'jadwal_selesai' => now()->addMonths(3),
                'status_proses' => 'Dalam Proses',
                'status_gr' => 'Aktif',
                'keterangan' => 'Pembangunan jalan lingkungan',
                'user_id' => $user->id,
                'jenis_investasi' => 'Infrastruktur',
                'div' => 'Divisi Pembangunan',
                'nilai_item_investasi' => 450000000,
                'pbj' => 'Tender'
            ],
            [
                'nama_pekerjaan' => 'Renovasi Gedung',
                'volume' => 1,
                'satuan' => 'unit',
                'sumber_keterangan' => 'APBN',
                'sub_unit' => 'Unit B',
                'jenis_op' => 'Renovasi',
                'nilai_paket_pekerjaan' => 300000000,
                'jadwal_mulai' => now()->addDays(10),
                'jadwal_selesai' => now()->addMonths(2),
                'status_proses' => 'Persiapan',
                'status_gr' => 'Aktif',
                'keterangan' => 'Renovasi gedung kantor',
                'user_id' => $user->id,
                'jenis_investasi' => 'Properti',
                'div' => 'Divisi Pemeliharaan',
                'nilai_item_investasi' => 280000000,
                'pbj' => 'Penunjukan Langsung'
            ],
        ];

        // Insert data
        foreach ($pekerjaanData as $data) {
            Pekerjaan::create($data);
        }
        
        // Alternatively, you can use factory if you have one
        // Pekerjaan::factory(10)->create();
    }
}
