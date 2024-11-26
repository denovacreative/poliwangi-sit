<?php

namespace Database\Seeders;

use App\Models\Presence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Presence::insert([
            [
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'class_schedule_id' => 'b5235fc0-0ed1-429d-b8ca-73aec68a63dc',
                'student_id' => '19c962da-d9e9-4ff3-9e5e-41bb0e28acde',
                'number_of_meeting' => 1,
                'date' => '2022-12-02',
                'status' => '0',
            ],
            [
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'class_schedule_id' => 'b5235fc0-0ed1-429d-b8ca-73aec68a63dc',
                'student_id' => 'ac185491-3d65-4a72-9a60-adc633d9bdaf',
                'number_of_meeting' => 1,
                'date' => '2022-12-02',
                'status' => '0',
            ],
            [
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'class_schedule_id' => 'b5235fc0-0ed1-429d-b8ca-73aec68a63dc',
                'student_id' => '7ff96e76-183b-434c-a4fe-0c25180e2ae7',
                'number_of_meeting' => 1,
                'date' => '2022-12-02',
                'status' => '0',
            ],
            [
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'class_schedule_id' => 'b5235fc0-0ed1-429d-b8ca-73aec68a63dc',
                'student_id' => '788c8f50-ed3c-42fb-8eee-a4f6c963b91b',
                'number_of_meeting' => 1,
                'date' => '2022-12-02',
                'status' => '0',
            ],
            [
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'class_schedule_id' => 'b5235fc0-0ed1-429d-b8ca-73aec68a63dc',
                'student_id' => '6be384d9-5cf7-4604-8db5-51bcab53b8eb',
                'number_of_meeting' => 1,
                'date' => '2022-12-02',
                'status' => '0',
            ],
        ]);
    }
}
