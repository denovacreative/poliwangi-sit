<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TimeSlot::insert([
            ['time' => '07:00', 'type' => 'morning'],
            ['time' => '07:20', 'type' => 'morning'],
            ['time' => '07:30', 'type' => 'morning'],
            ['time' => '08:00', 'type' => 'morning'],
            ['time' => '08:20', 'type' => 'morning'],
            ['time' => '08:40', 'type' => 'morning'],
            ['time' => '08:50', 'type' => 'morning'],
            ['time' => '09:00', 'type' => 'morning'],
            ['time' => '09:10', 'type' => 'morning'],
            ['time' => '09:30', 'type' => 'morning'],
            ['time' => '09:40', 'type' => 'morning'],
            ['time' => '10:00', 'type' => 'morning'],
            ['time' => '10:20', 'type' => 'morning'],
            ['time' => '10:30', 'type' => 'morning'],
            ['time' => '11:00', 'type' => 'morning'],
            ['time' => '11:10', 'type' => 'morning'],
            ['time' => '11:20', 'type' => 'morning'],
            ['time' => '11:30', 'type' => 'morning'],
            ['time' => '12:00', 'type' => 'afternoon'],
            ['time' => '12:10', 'type' => 'afternoon'],
            ['time' => '12:30', 'type' => 'afternoon'],
            ['time' => '13:00', 'type' => 'afternoon'],
            ['time' => '13:10', 'type' => 'afternoon'],
            ['time' => '13:30', 'type' => 'afternoon'],
            ['time' => '13:50', 'type' => 'afternoon'],
            ['time' => '14:00', 'type' => 'afternoon'],
            ['time' => '14:30', 'type' => 'afternoon'],
            ['time' => '14:40', 'type' => 'afternoon'],
            ['time' => '15:00', 'type' => 'afternoon'],
            ['time' => '15:10', 'type' => 'afternoon'],
            ['time' => '15:30', 'type' => 'afternoon'],
            ['time' => '16:00', 'type' => 'afternoon'],
            ['time' => '16:20', 'type' => 'afternoon'],
            ['time' => '16:30', 'type' => 'afternoon'],
            ['time' => '17:00', 'type' => 'afternoon'],
            ['time' => '17:10', 'type' => 'afternoon'],
            ['time' => '17:30', 'type' => 'afternoon'],
            ['time' => '18:00', 'type' => 'night'],
            ['time' => '18:30', 'type' => 'night'],
            ['time' => '19:00', 'type' => 'night'],
            ['time' => '19:30', 'type' => 'night'],
            ['time' => '20:00', 'type' => 'night'],
            ['time' => '20:30', 'type' => 'night'],
            ['time' => '21:00', 'type' => 'night'],
        ]);
    }
}
