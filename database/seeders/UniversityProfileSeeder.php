<?php

namespace Database\Seeders;

use App\Models\UniversityProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UniversityProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UniversityProfile::create([
            'id' => '672ef5ee-b37a-4eeb-adba-1393148be35c',
            'employee_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae81b',
            'vice_chancellor' => '83ec3a2d-d4c7-417d-be37-3a494670ce85',
            'vice_chancellor_2' => '83ec3a2d-d4c7-417d-be37-3a494670ce84',
            'vice_chancellor_3' => '83ec3a2d-d4c7-417d-be37-3a494670ce89',
            'code' => '005035',
            'name' => 'Politeknik Negeri Banyuwangi',
            'name_en' => 'Banyuwangi State Polytechnic',
            'alias' => 'Poliwangi',
            'phone_number' => '(0333)636780',
            'address' => 'Jl.Raya Jember KM 13 Labanasem Kecamatan Kabat Kabupaten Banyuwangi',
            'acreditation' => 'B',
            'acreditation_number' => '-',
            'acreditation_date' => null,
            'establishment_number' => '14 Tahun 2013',
            'establishment_date' => '2013-02-22',
        ]);
    }
}
