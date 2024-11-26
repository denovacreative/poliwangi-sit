<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profession::insert([
            array('code' => '0', 'name' => ' -'),
            array('code' => '1', 'name' => 'Tidak bekerja'),
            array('code' => '2', 'name' => 'Nelayan'),
            array('code' => '3', 'name' => 'Petani'),
            array('code' => '4', 'name' => 'Peternak'),
            array('code' => '5', 'name' => 'PNS/TNI/Polri'),
            array('code' => '6', 'name' => 'Karyawan Swasta'),
            array('code' => '7', 'name' => 'Pedagang Kecil'),
            array('code' => '8', 'name' => 'Pedagang Besar'),
            array('code' => '9', 'name' => 'Wiraswasta'),
            array('code' => '10', 'name' => 'Wirausaha'),
            array('code' => '11', 'name' => 'Buruh'),
            array('code' => '12', 'name' => 'Pensiunan'),
            array('code' => '98', 'name' => 'Sudah Meninggal'),
            array('code' => '99', 'name' => 'Lainnya')
        ]);
    }
}
