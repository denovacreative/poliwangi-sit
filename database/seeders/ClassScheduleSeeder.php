<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\CollegeClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassSchedule::insert([
            [
                'id' => 'b5235fc0-0ed1-429d-b8ca-73aec68a63dc',
                'employee_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae87b',
                'meeting_type_id' => '3',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'meeting_number' => 1,
                'time_start' => '08:00:00',
                'time_end' => '10:00:00',
                'date' => '2022-12-05',
                'learning_method' => 'offline',
                'credit' => 5,
                'status' => 'done',
            ],
            [
                'id' => 'fb5f46f2-49e4-4410-8541-54342d4c2ef1',
                'employee_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae87b',
                'meeting_type_id' => '3',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'meeting_number' => 2,
                'time_start' => '08:00:00',
                'time_end' => '10:00:00',
                'date' => '2022-12-06',
                'learning_method' => 'offline',
                'credit' => 5,
                'status' => 'done',
            ],
        ]);
    }
}
