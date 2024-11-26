<?php

namespace Database\Seeders;

use App\Models\StudentActivityCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentActivityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentActivityCategory::insert([
            ["id" => "1","name"=> "Laporan akhir studi"], 
            ["id" => "2","name"=> "Tugas akhir"], 
            ["id" => "3","name"=> "Tesis"], 
            ["id" => "4","name"=> "Disertasi"], 
            ["id" => "5","name"=> "Kuliah kerja nyata"], 
            ["id" => "6","name"=> "Kerja praktek\/PKL"], 
            ["id" => "7","name"=> "Bimbingan akademis"], 
            ["id" => "10","name"=> "Aktivitas kemahasiswaan"], 
            ["id" => "11","name"=> "Program kreativitas mahasiswa"], 
            ["id" => "12","name"=> "Kompetisi"]
        ]);
    }
}
