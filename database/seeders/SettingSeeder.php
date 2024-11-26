<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();
        Setting::insert([
            [
                'group' => 'college',
                'key' => 'college_name',
                'name' => 'Nama Kampus',
                'value' => 'Politeknik Negeri Banyuwangi',
                'is_default' => true,
            ],
            [
                'group' => 'college',
                'key' => 'college_description',
                'name' => 'Deskirpsi Kampus',
                'value' => 'Lorem ipsum dolor sit amet',
                'is_default' => true,
            ],
            [
                'group' => 'college',
                'key' => 'logo',
                'name' => 'Logo',
                'value' => 'logo.svg',
                'is_default' => true,
            ],
            [
                'group' => 'college',
                'key' => 'favicon',
                'name' => 'Icon',
                'value' => 'favicon.png',
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_username',
                'name' => 'Username Feeder',
                'value' => '005035',
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_password',
                'name' => 'Password Feeder',
                'value' => 'pangkalandataKM13',
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_url',
                'name' => 'URL Feeder',
                'value' => '103.109.210.2',
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_port',
                'name' => 'URL Port',
                'value' => '8100',
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_path',
                'name' => 'Path Feeder',
                'value' => '/ws/live2.php',
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_err_code',
                'name' => 'Kode Error Feeder',
                'value' => 0,
                'is_default' => true,
            ],
            [
                'group' => 'feeder',
                'key' => 'feeder_err_message',
                'name' => 'Status Feeder',
                'value' => '',
                'is_default' => true,
            ],
            [
                'group' => 'config',
                'key' => 'disable_app',
                'name' => 'Disable App',
                'value' => false,
                'is_default' => true,
            ],
        ]);
    }
}
