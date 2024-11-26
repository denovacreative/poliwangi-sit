<?php

namespace Database\Seeders;

use App\Models\CourseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseType::insert([
            ['id' => 'A', 'name' => 'WAJIB PROGRAM STUDI'],
            ['id' => 'B', 'name' => 'PILIHAN'],
            ['id' => 'C', 'name' => 'WAJIB PEMINATAN'],
            ['id' => 'D', 'name' => 'PILIHAN PEMINATAN'],
            ['id' => 'S', 'name' => 'TUGAS AKHIR/SKRIPSI/TESIS/DISERTASI'],
            ['id' => 'W', 'name' => 'WAJIB NASIONAL'],
        ]);
    }
}
