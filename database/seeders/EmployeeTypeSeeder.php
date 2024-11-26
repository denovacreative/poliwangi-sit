<?php

namespace Database\Seeders;

use App\Models\EmployeeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeType::insert([
            ['code' => '1', 'name' => 'Dosen Tetap PNS'],
            ['code' => '2', 'name' => 'Dosen Tetap Bukan PNS'],
            ['code' => '3', 'name' => 'Dosen Luar Biasa'],
            ['code' => '4', 'name' => 'Dosen Tamu'],
            ['code' => '5', 'name' => 'Asisten Dosen'],
            ['code' => '6', 'name' => 'Asisten Laboratorium'],
            ['code' => 'PI', 'name' => 'Dosen'],
        ]);
    }
}
