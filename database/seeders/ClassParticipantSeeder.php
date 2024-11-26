<?php

namespace Database\Seeders;

use App\Models\ClassParticipant;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ClassParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassParticipant::insert([
            [
                'id' => Uuid::uuid4(),
                'student_id' => '19c962da-d9e9-4ff3-9e5e-41bb0e28acde',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'is_class_coordinator' => true,
                'created_at' => Carbon::now(),
            ],
            [
                'id' => UUid::uuid4(),
                'student_id' => 'ac185491-3d65-4a72-9a60-adc633d9bdaf',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'is_class_coordinator' => false,
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Uuid::uuid4(),
                'student_id' => '7ff96e76-183b-434c-a4fe-0c25180e2ae7',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'is_class_coordinator' => false,
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Uuid::uuid4(),
                'student_id' => '788c8f50-ed3c-42fb-8eee-a4f6c963b91b',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'is_class_coordinator' => false,
                'created_at' => Carbon::now(),
            ],
            [
                'id' => Uuid::uuid4(),
                'student_id' => '6be384d9-5cf7-4604-8db5-51bcab53b8eb',
                'college_class_id' => '3f05dee7-6cc9-4eb2-b418-89b11239d34a',
                'is_class_coordinator' => false,
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
