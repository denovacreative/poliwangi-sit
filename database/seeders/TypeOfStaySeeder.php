<?php

namespace Database\Seeders;

use App\Models\TypeOfStay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfStaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TypeOfStay::insert([
            array('name' => '-'),
            array('name' => 'Bersama orang tua'),
            array('name' => 'Wali'),
            array('name' => 'Kost'),
            array('name' => 'Asrama'),
            array('name' => 'Panti asuhan'),
            array('name' => 'Lainnya')
        ]);
    }
}
