<?php

namespace Database\Seeders;

use App\Models\Score;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Score::insert([
            [
                'id' => Uuid::uuid4(),
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'student_id' => '19c962da-d9e9-4ff3-9e5e-41bb0e28acde',
                'mid_exam' => 0,
                'final_exam' => 0,
                'coursework' => 0,
                'quiz' => 0,
                'attendance' => 0,
                'practice' => 0,
                'final_score' => 0,
                'remedial_score' => 0,
                'final_grade' => 'E',
                'score' => 0,
                'grade' => 'E',
                'index_score' => 0,
            ],
            [
                'id' => Uuid::uuid4(),
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'student_id' => 'ac185491-3d65-4a72-9a60-adc633d9bdaf',
                'mid_exam' => 0,
                'final_exam' => 0,
                'coursework' => 0,
                'quiz' => 0,
                'attendance' => 0,
                'practice' => 0,
                'final_score' => 0,
                'remedial_score' => 0,
                'final_grade' => 'E',
                'score' => 0,
                'grade' => 'E',
                'index_score' => 0,
            ],
            [
                'id' => Uuid::uuid4(),
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'student_id' => '7ff96e76-183b-434c-a4fe-0c25180e2ae7',
                'mid_exam' => 0,
                'final_exam' => 0,
                'coursework' => 0,
                'quiz' => 0,
                'attendance' => 0,
                'practice' => 0,
                'final_score' => 0,
                'remedial_score' => 0,
                'final_grade' => 'E',
                'score' => 0,
                'grade' => 'E',
                'index_score' => 0,
            ],
            [
                'id' => Uuid::uuid4(),
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'student_id' => '788c8f50-ed3c-42fb-8eee-a4f6c963b91b',
                'mid_exam' => 0,
                'final_exam' => 0,
                'coursework' => 0,
                'quiz' => 0,
                'attendance' => 0,
                'practice' => 0,
                'final_score' => 0,
                'remedial_score' => 0,
                'final_grade' => 'E',
                'score' => 0,
                'grade' => 'E',
                'index_score' => 0,
            ],
            [
                'id' => Uuid::uuid4(),
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'student_id' => '6be384d9-5cf7-4604-8db5-51bcab53b8eb',
                'mid_exam' => 0,
                'final_exam' => 0,
                'coursework' => 0,
                'quiz' => 0,
                'attendance' => 0,
                'practice' => 0,
                'final_score' => 0,
                'remedial_score' => 0,
                'final_grade' => 'E',
                'score' => 0,
                'grade' => 'E',
                'index_score' => 0,
            ],
        ]);
    }
}
