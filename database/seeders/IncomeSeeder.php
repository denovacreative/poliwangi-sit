<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Income::insert([
            array('name' => ' -'),
            array('name' => 'Kurang dari Rp 1.000.000'),
            array('name' => 'Rp 1.000.000 - Rp 2.000.000'),
            array('name' => 'Lebih dari Rp 2.000.000'),
            array('name' => 'Lainnya'),
            array('name' => 'Kurang dari Rp. 500,000'),
            array('name' => 'Rp. 500,000 - Rp. 999,999'),
            array('name' => 'Rp. 1,000,000 - Rp. 1,999,999'),
            array('name' => 'Rp. 2,000,000 - Rp. 4,999,999'),
            array('name' => 'Rp. 5,000,000 - Rp. 20,000,000'),
            array('name' => 'Lebih dari Rp. 20,000,000')
        ]);
    }
}
