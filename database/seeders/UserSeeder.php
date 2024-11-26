<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Root',
            'email' => 'root@gmail.com',
            'username' => 'root',
            'password' => Hash::make('root'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Developer');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Default',
            'email' => 'default@gmail.com',
            'username' => 'default',
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Default');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'username' => 'administrator',
            'password' => Hash::make('admin'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Administrator');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Ketua Jurusan',
            'email' => 'ketuajurusan@gmail.com',
            'username' => 'ketuajurusan',
            'password' => Hash::make('kajur'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Ketua Jurusan');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Ketua Program Studi',
            'email' => 'ketuaprogramstudi@gmail.com',
            'username' => 'ketuaprogramstudi',
            'password' => Hash::make('kaprodi'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Ketua Program Studi');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Dosen',
            'email' => 'dosen@gmail.com',
            'username' => 'dosen',
            'password' => Hash::make('dosen'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Dosen');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'username' => 'mahasiswa',
            'password' => Hash::make('mahasiswa'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Mahasiswa');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Admin Program Studi',
            'email' => 'adminprogramstudi@gmail.com',
            'username' => 'adminprogramstudi',
            'password' => Hash::make('adminprodi'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Admin Program Studi');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Akademik',
            'email' => 'akademik@gmail.com',
            'username' => 'akademik',
            'password' => Hash::make('akademik'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Akademik');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Admin Feeder',
            'email' => 'adminfeeder@gmail.com',
            'username' => 'adminfeeder',
            'password' => Hash::make('feeder'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Admin Feeder');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Direktur',
            'email' => 'direktur@gmail.com',
            'username' => 'direktur',
            'password' => Hash::make('direktur'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Direktur');

        User::create([
            'unitable_type' => null,
            'unitable_id' => null,
            'userable_type' => null,
            'userable_id' => null,
            'sso_id' => null,
            'name' => 'Wakil Direktur',
            'email' => 'wakildirektur@gmail.com',
            'username' => 'wakildirektur',
            'password' => Hash::make('wadir'),
            'picture' => 'default.png',
            'is_active' => true,
            'expired_at' => null
        ])->assignRole('Wakil Direktur');
    }
}
