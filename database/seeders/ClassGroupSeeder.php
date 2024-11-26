<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassGroup::insert([
            ['academic_year_id' => 2022, 'code' => '1TIA2022', 'name' => '1 TI A 2022', 'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab'],
            ['academic_year_id' => 2022, 'code' => '1TIB2022', 'name' => '1 TI B 2022', 'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab'],
            ['academic_year_id' => 2022, 'code' => '1TIC2022', 'name' => '1 TI C 2022', 'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab'],
            ['academic_year_id' => 2022, 'code' => '2TIA2022', 'name' => '2 TI A 2022', 'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab'],
            ['academic_year_id' => 2022, 'code' => '2TIB2022', 'name' => '2 TI B 2022', 'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab'],
        ]);
    }
}
