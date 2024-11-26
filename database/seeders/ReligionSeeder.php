<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Religion::insert([
            ['id' => '1', 'name' => 'Islam'],
            ['id' => '2', 'name' => 'Kristen'],
            ['id' => '3', 'name' => 'Katholik'],
            ['id' => '4', 'name' => 'Hindu'],
            ['id' => '5', 'name' => 'Budha'],
            ['id' => '6', 'name' => 'Konghucu'],
            ['id' => '98', 'name' => 'Tidak diisi'],
            ['id' => '99', 'name' => 'Lainnya']
        ]);
    }
}
