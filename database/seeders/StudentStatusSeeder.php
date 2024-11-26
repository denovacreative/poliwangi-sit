<?php

namespace Database\Seeders;

use App\Models\StudentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentStatus::insert([
            [
                'id' => 'A',
                'name' => 'Aktif',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => true,
                'is_default' => true
            ],
            [
                'id' => 'C',
                'name' => 'Cuti Akademik',
                'is_submited' => true,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'D',
                'name' => 'Drop Out/Dikeluarkan',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'G',
                'name' => 'Sedang Double Degree',
                'is_submited' => true,
                'is_active' => true,
                'is_college' => true,
                'is_default' => true
            ],
            [
                'id' => 'H',
                'name' => 'Hilang',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'K',
                'name' => 'Mengundurkan Diri/Keluar',
                'is_submited' => true,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'KM',
                'name' => 'Kampus Merdeka',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => true,
                'is_default' => true
            ],
            [
                'id' => 'L',
                'name' => 'Lulus',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'LL',
                'name' => 'Lainnya',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'M',
                'name' => 'Mutasi',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => false
            ],
            [
                'id' => 'N',
                'name' => 'Non Aktif',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'P',
                'name' => 'Putus Sekolah',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'T',
                'name' => 'Transfer',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => true
            ],
            [
                'id' => 'W',
                'name' => 'Wafat',
                'is_submited' => false,
                'is_active' => true,
                'is_college' => false,
                'is_default' => false
            ],
        ]);
    }
}
