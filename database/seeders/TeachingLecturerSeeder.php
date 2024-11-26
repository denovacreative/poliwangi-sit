<?php

namespace Database\Seeders;

use App\Models\TeachingLecturer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class TeachingLecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TeachingLecturer::insert([
            [
                'id' => 'a807eb8e-f28b-4f09-bc59-19f7275283a3',
                'evaluation_type_id' => 1,
                'employee_id' => '83ec3a2d-d4c7-417d-be37-3a494670ce81',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'credit_total' => 100,
                'credit_meeting' => 85,
                'credit_practicum' => 90,
                'credit_practice' => 90,
                'credit_simulation' => 85,
                'meeting_plan' => 1,
                'meeting_realization' => 1,
                'is_score_entry' => true
            ]
        ]);
    }
}
