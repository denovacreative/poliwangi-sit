<?php

namespace Database\Seeders;

use App\Models\EvaluationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EvaluationType::insert([
            [
                'name' => 'Test 1'
            ]
        ]);
    }
}
