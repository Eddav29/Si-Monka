<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MonevKeuangan;
use App\Models\Pekerjaan;
use App\Models\User;
use Faker\Factory as Faker;

class MonevKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::pluck('id')->toArray();
        $pekerjaans = Pekerjaan::pluck('id')->toArray();

        if (empty($users) || empty($pekerjaans)) {
            $this->command->info('Please seed users and pekerjaan tables first.');
            return;
        }

        $jenis = ['perencanaan', 'verifikasi', 'pengadaan', 'pelaksanaan', 'laporan'];
        $status = ['Belum Dimulai', 'Sedang Berjalan', 'Selesai'];

        for ($i = 0; $i < 50; $i++) {
            $startDate = $faker->dateTimeBetween('-1 year', 'now');
            $endDate = $faker->dateTimeBetween($startDate, '+6 months');
            
            MonevKeuangan::create([
                'pekerjaan_id' => $faker->randomElement($pekerjaans),
                'jenis_monitoring' => $faker->randomElement($jenis),
                'status_monitoring' => $faker->randomElement($status),
                'program' => $faker->randomFloat(2, 1000, 100000),
                'realisasi' => $faker->randomFloat(2, 1000, 100000),
                'evaluasi' => $faker->paragraph(3),
                'pic' => $faker->name,
                'file' => 'dummy_file_' . $faker->word . '.pdf',
                'user_id' => $faker->randomElement($users),
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
            ]);
        }
    }
}
