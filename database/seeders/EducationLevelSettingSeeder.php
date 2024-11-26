<?php

namespace Database\Seeders;

use App\Models\EducationLevelSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationLevelSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EducationLevelSetting::insert([
            [
                'education_level_id' => 20,
                'study' => 2,
                'max_leave' => 21,
                'max_study' => 3,
            ],
            [
                'education_level_id' => 21,
                'study' => 4,
                'max_leave' => 21,
                'max_study' => 5,
            ],
            [
                'education_level_id' => 22,
                'study' => 6,
                'max_leave' => 2,
                'max_study' => 8,
            ],
            [
                'education_level_id' => 30,
                'study' => 8,
                'max_leave' => 4,
                'max_study' => 14,
            ],
            [
                'education_level_id' => 35,
                'study' => 4,
                'max_leave' => 2,
                'max_study' => 8,
            ],
            [
                'education_level_id' => 40,
                'study' => 6,
                'max_leave' => 0,
                'max_study' => 6,
            ],
            [
                'education_level_id' => 31,
                'study' => 4,
                'max_leave' => 0,
                'max_study' => 4,
            ],
            [
                'education_level_id' => 23,
                'study' => 8,
                'max_leave' => 4,
                'max_study' => 14,
            ],
        ]);
    }
}
