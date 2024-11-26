<?php

namespace Database\Seeders;

use App\Models\RegistrationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegistrationType::insert([
            ['name' => 'Peserta Didik Baru', 'is_school_register' => false],
            ['name' => 'Transfer', 'is_school_register' => false],
        ]);
    }
}
