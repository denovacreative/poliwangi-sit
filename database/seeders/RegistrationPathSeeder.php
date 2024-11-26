<?php

namespace Database\Seeders;

use App\Models\RegistrationPath;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegistrationPath::insert([
            ['name' => 'SNMPN'],
            ['name' => 'SBMPN'],
            ['name' => 'MANDIRI'],
            ['name' => 'PMDK'],
            ['name' => 'Prestasi'],
        ]);
    }
}
