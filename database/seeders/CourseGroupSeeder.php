<?php

namespace Database\Seeders;

use App\Models\CourseGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseGroup::insert([
            ['code' => 'HLND', 'name' => 'Sastra Belanda'],
            ['code' => 'MBB', 'name' => 'Matakuliah Berkehidupan Bermasyarakat (MBB)'],
            ['code' => 'MAB', 'name' => 'Matakuliah Ilmu Alam Dasar dan Biomedik Dasar'],
            ['code' => 'MDK', 'name' => 'Matakuliah Ilmu Dasar Keperawatan'],
        ]);
    }
}
