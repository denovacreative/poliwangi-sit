<?php

namespace Database\Seeders;

use App\Models\Transportation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transportation::insert([
            array('code' => '1', 'name' => 'Jalan kaki'),
            array('code' => '2', 'name' => 'Kendaraan pribadi'),
            array('code' => '3', 'name' => 'Angkutan umum/bus/pete-pete'),
            array('code' => '4', 'name' => 'Mobil/bus antar jemput'),
            array('code' => '5', 'name' => 'Kereta api'),
            array('code' => '6', 'name' => 'Ojek'),
            array('code' => '7', 'name' => 'Andong/bendi/sado/dokar/delman/becak'),
            array('code' => '8', 'name' => 'Perahu penyeberangan/rakit/getek'),
            array('code' => '11', 'name' => 'Kuda'),
            array('code' => '12', 'name' => 'Sepeda'),
            array('code' => '13', 'name' => 'Sepeda motor'),
            array('code' => '14', 'name' => 'Mobil pribadi'),
            array('code' => '99', 'name' => 'Lainnya')
        ]);
    }
}
