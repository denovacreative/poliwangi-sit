<?php

namespace Database\Seeders;

use App\Models\CourseCurriculum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CourseCurriculum::create([
            'id' => 'f9b4e853-e4e0-401b-8b0f-808a8e8c4783',
            'course_id' => 'dbdafc88-482e-4e2b-b9a4-1c810bafd174',
            'curriculum_id' => '9023010f-1818-4aff-a9c8-bb4902c3b9d2',
            'semester' => 1,
            'credit_total' => 5,
            'credit_meeting' => 1,
            'credit_practicum' => 2,
            'credit_practice' => 1,
            'credit_simulation' => 1,
            'is_mandatory' => false,
            'created_at' => Carbon::now(),
        ]);
    }
}
