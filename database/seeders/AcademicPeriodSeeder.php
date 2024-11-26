<?php

namespace Database\Seeders;

use App\Models\AcademicPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicPeriod::insert([
            [
                'id' => '20221',
                'academic_year_id' => '2022',
                'semester' => '1',
                'name' => '2022 / 2023 Ganjil',
                'college_start_date' => '2022-11-01',
                'college_end_date' => '2022-12-01',
                'mid_exam_start_date' => '2022-11-01',
                'mid_exam_end_date' => '2022-12-01',
                'final_exam_start_date' => '2022-11-01',
                'final_exam_end_date' => '2022-12-01',
                'number_of_meeting' => '16',
                'is_active' => true,
                'is_use' => true,
            ]
        ]);
    }
}
