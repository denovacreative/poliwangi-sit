<?php

namespace Database\Seeders;

use App\Models\MeetingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeetingType::insert([
            [
                'code' => 'A',
                'name' => 'UAS',
                'alias' => 'UAS',
                'type' => 'final_exam',
                'is_presence' => true,
                'is_exam' => true
            ],
            [
                'code' => 'IBDH',
                'name' => 'IBADAH',
                'alias' => 'IBADAH',
                'type' => 'none',
                'is_presence' => false,
                'is_exam' => false
            ],
            [
                'code' => 'K',
                'name' => 'KULIAH',
                'alias' => 'KULIAH',
                'type' => 'college',
                'is_presence' => true,
                'is_exam' => false
            ],
            [
                'code' => 'P',
                'name' => 'PRAKTIKUM',
                'alias' => 'PRAKTIKUM',
                'type' => 'none',
                'is_presence' => true,
                'is_exam' => false
            ],
            [
                'code' => 'RMD',
                'name' => 'REMEDIAL',
                'alias' => 'REMEDIAL',
                'type' => 'none',
                'is_presence' => true,
                'is_exam' => true
            ],
            [
                'code' => 'UTS',
                'name' => 'UTS',
                'alias' => 'UTS',
                'type' => 'mid_exam',
                'is_presence' => true,
                'is_exam' => true
            ],
        ]);
    }
}
