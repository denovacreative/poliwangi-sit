<?php

namespace Database\Seeders;

use App\Models\AcademicActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicActivity::insert([
            array('name' => 'Perkuliahan', 'color' => 'F07427'),
            array('name' => 'KKN', 'color' => '#f0279c'),
            array('name' => 'PKL', 'color' => '#c4f027'),
            array('name' => 'Seminar Proposal', 'color' => '#3bf027'),
            array('name' => 'Penelitian', 'color' => '3827f0'),
            array('name' => 'Wisuda', 'color' => '7127f0'),
        ]);
    }
}
