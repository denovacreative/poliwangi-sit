<?php

namespace Database\Seeders;

use App\Models\LectureSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LectureSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LectureSystem::insert([
            ['name' => 'Reguler'],
            ['name' => 'Karyawan'],
        ]);
    }
}
