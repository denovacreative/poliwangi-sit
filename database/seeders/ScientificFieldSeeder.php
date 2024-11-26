<?php

namespace Database\Seeders;

use App\Models\ScientificField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScientificFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScientificField::insert([
            ['code' => '111', 'name' => 'Fisika'],
            ['code' => '112', 'name' => 'Kimia'],
            ['code' => '113', 'name' => 'Biologi (dan Bioteknologi Umum)'],
            ['code' => '114', 'name' => 'Bidang IPA Lain Yang Belum Tercantum'],
            ['code' => '121', 'name' => 'Matematika'],
            ['code' => '122', 'name' => 'Statistika'],
            ['code' => '123', 'name' => 'Ilmu Komputer'],
            ['code' => '124', 'name' => 'Bidang Matematika Lain Yang Belum Tercantum'],
            ['code' => '131', 'name' => 'Astronomi'],
            ['code' => '132', 'name' => 'Geografi'],
        ]);
    }
}
