<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Curriculum::insert([
            [
                'id' => '9023010f-1818-4aff-a9c8-bb4902c3b9d2',
                'study_program_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab',
                'academic_period_id' => '20221',
                'name' => 'Kurikulum TRPL 2022',
                'credit_total' => '5',
                'mandatory_credit' => '0',
                'choice_credit' => '5',
            ]
        ]);
    }
}
