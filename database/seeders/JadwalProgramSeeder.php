<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\JadwalProgram;
use Carbon\Carbon;

class JadwalProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all programs
        $programs = Program::all();

        foreach ($programs as $program) {
            // Create a jadwal for each program with random dates
            JadwalProgram::create([
                'program_id' => $program->id,
                'desain' => Carbon::now()->addDays(rand(1, 30)),
                'verifikasi' => Carbon::now()->addDays(rand(31, 60)),
                'pbj' => Carbon::now()->addDays(rand(61, 90)),
                'pelaksanaan' => Carbon::now()->addDays(rand(91, 120)),
                'catatan' => 'Catatan untuk program ' . $program->name,
            ]);
        }
    }
}
