<?php

namespace Database\Seeders;

use App\Models\Disability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disability::insert([
            ['name' => 'Tunarungu'],
            ['name' => 'Tunagrahita'],
            ['name' => 'Tunanetra'],
            ['name' => 'Tunadaksa'],
            ['name' => 'Tunalaras'],
            ['name' => 'Gangguan pemusatan perhatian dan hiperaktivitas (ADHD)'],
            ['name' => 'Autisme'],
            ['name' => 'Gangguan Ganda'],
            ['name' => 'Lamban Belajar'],
            ['name' => 'Kesulitan Belajar Khusus'],
            ['name' => 'Gangguan Kemampuan Komunikasi'],
            ['name' => 'Gifted'],
        ]);
    }
}
