<?php

namespace Database\Seeders;

use App\Models\Heregistration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeregistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Heregistration::insert([
            [
                'academic_period_id' => '',
                'student_id' => '',
                'attachment' => '',
                'payment_date' => '',
                'tuition_fee' => '',
                'is_scholarship' => '',
                'is_acc' => '',
                'validator_id' => ''
            ]
        ]);
    }
}
