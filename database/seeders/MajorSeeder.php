<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Major::insert([
            [
                'id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'name' => 'Teknik Informatika',
                'name_en' => 'Informatics Engineering',
                'alias' => 'TI',
                'phone_number' => null,
                'address' => null,
            ],
            [
                'id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'name' => 'Teknik Mesin',
                'name_en' => 'Mechanical Engineering',
                'alias' => 'TM',
                'phone_number' => null,
                'address' => null,
            ],
            [
                'id' => 'ec9fb2c6-a118-4748-91be-804d9b3ae82b',
                'name' => 'Teknik Sipil',
                'name_en' => 'Civil Engineering',
                'alias' => 'TS',
                'phone_number' => null,
                'address' => null,
            ],
            [
                'id' => 'fe200eb0-7dfa-47ef-a323-1a52d7641985',
                'name' => 'Agribisnis',
                'name_en' => 'Agribusiness',
                'alias' => 'AGB',
                'phone_number' => null,
                'address' => null,
            ],
            [
                'id' => '88c3966d-cf97-4aa8-a1a0-e7c363ed7ffe',
                'name' => 'Teknologi Pengolahan Hasil Ternak',
                'name_en' => 'Teknologi Pengolahan Hasil Ternak',
                'alias' => 'AGB',
                'phone_number' => null,
                'address' => null,
            ],
            [
                'id' => '1a00c70c-a689-4461-be11-7f1c51e821a2',
                'name' => 'Manajemen Bisnis Pariwisata',
                'name_en' => 'Manajemen Bisnis Pariwisata',
                'alias' => 'AGB',
                'phone_number' => null,
                'address' => null,
            ],
        ]);
    }
}
