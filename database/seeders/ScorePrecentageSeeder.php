<?php

namespace Database\Seeders;

use App\Models\ScorePercentage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorePrecentageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScorePercentage::insert([
            [
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'quiz' => 85,
                'coursework' => 85,
                'attendance' => 90,
                'mid_exam' => 90,
                'final_exam' => 90,
                'practice' => 95
            ]
        ]);
    }
}
