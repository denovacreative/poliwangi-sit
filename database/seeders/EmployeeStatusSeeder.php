<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeStatus::insert([
            ['code' => 'AA', 'name' => 'Aktif'],
            ['code' => 'CL', 'name' => 'Cuti Luar Tanggungan'],
            ['code' => 'K2', 'name' => 'Kontrak Kedua'],
            ['code' => 'KH', 'name' => 'Kontrak Habis'],
            ['code' => 'M', 'name' => 'Meninggal Dunia'],
            ['code' => 'M5', 'name' => 'Mangkir 5 Kali Berturut-turut'],
            ['code' => 'MD', 'name' => 'Mengundurkan Diri'],
            ['code' => 'PD', 'name' => 'Pensiun Dini'],
            ['code' => 'PH', 'name' => 'PHK'],
            ['code' => 'Pl', 'name' => 'Pelanggaran'],
            ['code' => 'PN', 'name' => 'Pensiun Normal'],
            ['code' => 'PS', 'name' => 'Pernikahan Sesama Karyawan'],
            ['code' => 'SB', 'name' => 'Kesalahan Berat'],
            ['code' => 'SP', 'name' => 'Sakit Berkepanjangan'],
            ['code' => 'TA', 'name' => 'Tidak Aktif'],
            ['code' => 'TB', 'name' => 'Tugas Belajar'],
            ['code' => 'TW', 'name' => 'Ditahan Pihak Berwajib'],
        ]);
    }
}
