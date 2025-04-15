<?php

namespace Database\Seeders;

use App\Models\Keuangan;
use App\Models\Pekerjaan;
use Illuminate\Database\Seeder;

class KeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all pekerjaan IDs or create dummy data if none exists
        $pekerjaanIds = Pekerjaan::pluck('id')->toArray();

        if (empty($pekerjaanIds)) {
            // If no pekerjaan records exist, create a dummy one for testing
            $pekerjaan = Pekerjaan::factory()->create();
            $pekerjaanIds = [$pekerjaan->id];
        }

        // Create 10 keuangan records
        foreach ($pekerjaanIds as $pekerjaanId) {
            for ($i = 0; $i < 2; $i++) {
                Keuangan::create([
                    'pekerjaan_id' => $pekerjaanId,
                    'rkap' => rand(10000000, 100000000),
                    'rkapt' => rand(10000000, 100000000),
                    'pjpsda' => rand(10000000, 100000000),
                    'rab' => rand(10000000, 100000000),
                    'nomor_io' => 'IO-' . rand(1000, 9999),
                    'real_kontrak' => rand(10000000, 100000000),
                    'nilai_progres' => rand(10000000, 100000000),
                    'actual_spj' => rand(10000000, 100000000),
                    'actual_sap' => rand(10000000, 100000000),
                    'actual_pembayaran' => rand(10000000, 100000000),
                ]);
            }
        }
    }
}
