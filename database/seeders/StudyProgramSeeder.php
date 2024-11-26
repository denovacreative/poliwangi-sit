<?php

namespace Database\Seeders;

use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudyProgram::insert([
            [
                'id' => 'bbe32aca-5907-4f3a-8ff1-3f427abf62d1',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'education_level_id' => 23,
                'code' => '58302',
                'name' => 'Teknologi Rekayasa Perangkat Lunak',
                'name_en' => 'Software Engineering Technology',
                'alias' => 'TRPL',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => 'Sarjana Terapan Komputer',
                'title_alias' => 'S.Tr.Kom',
                'title_en' => null
            ],
            [
                'id' => '98a2fa2a-b61d-41fe-be59-8750253c1bab',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'education_level_id' => 23,
                'code' => '36301',
                'name' => 'Teknologi Rekayasa Manufaktur',
                'name_en' => 'Manufacturing Engineering Technology',
                'alias' => 'TRPM',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => 'Sarjana Terapan Teknik',
                'title_alias' => 'S.Tr.T',
                'title_en' => null
            ],
            [
                'id' => '98a2fa2a-b61d-41fe-be59-8750253c3bab',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'education_level_id' => 23,
                'code' => '56301',
                'name' => 'Teknologi Rekayasa Komputer',
                'name_en' => 'Computer Engineering Technology',
                'alias' => 'TRK',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => 'Sarjana Terapan Komputer',
                'title_alias' => 'S.Tr.Kom',
                'title_en' => null
            ],
            [
                'id' => 'ed4513bd-e325-472f-b7f7-5fb1c5d031b0',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'education_level_id' => 22,
                'code' => '22401',
                'name' => 'Teknik Sipil',
                'name_en' => 'Civil Engineering',
                'alias' => 'TS',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => 'Ahli Madya Teknik',
                'title_alias' => 'A.Md.T',
                'title_en' => 'Diploma of Engineering'
            ],
            // Butuh penyesuaian
            [
                'id' => '36710971-fef4-4f55-b34e-b2844aa3124c',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'education_level_id' => 23,
                'code' => '21302',
                'name' => 'Teknik Manufakturing Kapal',
                'name_en' => 'Ship Manufacture Engineering',
                'alias' => 'TMK',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => 'Ahli Madya Teknik',
                'title_alias' => 'A.Md.T',
                'title_en' => 'Diploma of Engineering'
            ],
            [
                'id' => 'c5f1eaf9-4d29-44fc-aba1-3e78642e6557',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'education_level_id' => 23,
                'code' => '61316',
                'name' => 'Bisnis Digital',
                'name_en' => 'Digital Business',
                'alias' => 'BD',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => 'Sarjana Terapan Komputer',
                'title_alias' => 'S.Tr.Kom',
                'title_en' => null
            ],
            [
                'id' => 'c5de86f9-c6b4-4740-961b-32da3b1c003a',
                'major_id' => 'fe200eb0-7dfa-47ef-a323-1a52d7641985',
                'education_level_id' => 23,
                'code' => '41311',
                'name' => 'Agribisnis',
                'name_en' => 'Agribusiness',
                'alias' => 'AGB',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => '',
                'title_alias' => 'S.Tr.T',
                'title_en' => null
            ],
            [
                'id' => '81d614e0-3998-4d0a-9e1c-156e47ec7e6f',
                'major_id' => '88c3966d-cf97-4aa8-a1a0-e7c363ed7ffe',
                'education_level_id' => 23,
                'code' => '41333',
                'name' => 'Teknologi Pengolahan Hasil Ternak',
                'name_en' => 'Teknologi Pengolahan Hasil Ternak',
                'alias' => 'TPHT',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => '',
                'title_alias' => 'S.Tr.Tp',
                'title_en' => null
            ],
            [
                'id' => '8b4f352d-7caf-453a-9e40-1441d0b570d4',
                'major_id' => '1a00c70c-a689-4461-be11-7f1c51e821a2',
                'education_level_id' => 23,
                'code' => '93301',
                'name' => 'Manajemen Bisnis Pariwisata',
                'name_en' => '',
                'alias' => 'MBP',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => '',
                'title_alias' => 'S.Tr.Tp',
                'title_en' => null
            ],
            [
                'id' => '11950448-b1ce-4af0-b80e-459d5081b758',
                'major_id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'education_level_id' => 22,
                'code' => '21401',
                'name' => 'Teknik Mesin',
                'name_en' => 'Mechanical Engineering',
                'alias' => 'TM',
                'phone_number' => null,
                'address' => null,
                'is_active' => true,
                'acreditation' => 'A',
                'acreditation_number' => null,
                'acreditation_date' => null,
                'title' => '',
                'title_alias' => 'A.Md',
                'title_en' => null
            ],
        ]);
    }
}
