<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Pekerjaan;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all pekerjaan records to distribute programs
        $pekerjaans = Pekerjaan::all();

        if ($pekerjaans->count() > 0) {
            // Create program records for each pekerjaan
            foreach ($pekerjaans as $pekerjaan) {
                // Create 2-5 programs per pekerjaan
                $count = rand(2, 5);
                for ($i = 0; $i < $count; $i++) {
                    Program::create([
                        'pekerjaan_id' => $pekerjaan->id,
                        'pelaksanaan_program' => 'Pelaksanaan program ' . ($i + 1) . ' untuk pekerjaan ' . $pekerjaan->id,
                        'status_program' => ['Belum dimulai', 'Sedang berjalan', 'Selesai'][rand(0, 2)],
                        'realisasi_program' => rand(0, 100) . '% terealisasi',
                    ]);
                }
            }
        } else {
            // If no pekerjaan records exist, create a message
            $this->command->info('No pekerjaan records found. Please run PekerjaanSeeder first.');
        }
    }
}
