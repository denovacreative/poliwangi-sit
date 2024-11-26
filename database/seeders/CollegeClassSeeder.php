<?php

namespace Database\Seeders;

use App\Models\CollegeClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class CollegeClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CollegeClass::insert([
            [
                'id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'academic_period_id' => '20221',
                'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab',
                'course_id' => 'dbdafc88-482e-4e2b-b9a4-1c810bafd174',
                'lecture_system_id' => '1',
                'name' => 'Tes 1',
                'capacity' => '30',
                'date_start' => '2022-11-28',
                'date_end' => '2022-12-28',
                'number_of_meeting' => 16,
                'credit_total' => 20,
                'credit_meeting' => 5,
                'credit_practicum' => 5,
                'credit_practice' => 5,
                'credit_simulation' => 5,
                'case_discussion' => null,
                'is_lock_score' => false,
            ],
            [
                'id' => '615647d7-df3d-4479-b1d9-291d37c7aa87',
                'academic_period_id' => '20221',
                'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c3bab',
                'course_id' => 'dbdafc88-482e-4e2b-b9a4-1c810bafd174',
                'lecture_system_id' => '1',
                'name' => 'Tes 2',
                'capacity' => '25',
                'date_start' => '2022-11-11',
                'date_end' => '2022-12-11',
                'number_of_meeting' => 16,
                'credit_total' => 20,
                'credit_meeting' => 5,
                'credit_practicum' => 5,
                'credit_practice' => 5,
                'credit_simulation' => 5,
                'case_discussion' => null,
                'is_lock_score' => false,
            ],
        ]);
    }
}
