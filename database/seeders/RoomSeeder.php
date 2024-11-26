<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Room;
use App\Models\StudyProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::insert([
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'code' => '001',
                'name' => 'Lab Program 1',
                'location' => 'Gedung TI',
                'capacity' => 30,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'code' => '002',
                'name' => 'Lab Program 2',
                'location' => 'Gedung TI',
                'capacity' => 30,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'code' => '003',
                'name' => 'Lab Hardware',
                'location' => 'Gedung TI',
                'capacity' => 30,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b6ae82b',
                'code' => '004',
                'name' => 'Lab Basis Data',
                'location' => 'Gedung TI',
                'capacity' => 30,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'code' => '005',
                'name' => 'Lab TUK',
                'location' => 'Gedung TI',
                'capacity' => 28,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'code' => '006',
                'name' => 'Lab Nirkabel',
                'location' => 'Gedung TI',
                'capacity' => 28,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b1ae82b',
                'code' => '007',
                'name' => 'Lab Desain',
                'location' => 'Gedung TI',
                'capacity' => 28,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b3ae82b',
                'code' => '008',
                'name' => 'Lab Mikrokontroler',
                'location' => 'Gedung TI',
                'capacity' => 28,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => Major::class,
                'unitable_id' => 'ec9fb2c6-a118-4748-91be-804d9b3ae82b',
                'code' => '009',
                'name' => 'Lab Multimedia',
                'location' => 'Gedung TI',
                'capacity' => 34,
                'type' => 'lab',
                'description' => 'Gedung Lab TI',
            ],
            [
                'unitable_type' => StudyProgram::class,
                'unitable_id' => '98a2fa2a-b61d-41fe-be59-8750253c9bab',
                'code' => '010',
                'name' => 'A3.05',
                'location' => 'Gedung 454',
                'capacity' => 60,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '011',
                'name' => 'C2.05',
                'location' => 'Gedung 454',
                'capacity' => 30,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '012',
                'name' => 'C3.03',
                'location' => 'Gedung 454',
                'capacity' => 30,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '013',
                'name' => 'C2.02',
                'location' => 'Gedung 454',
                'capacity' => 30,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '014',
                'name' => 'C2.01',
                'location' => 'Gedung 454',
                'capacity' => 60,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '015',
                'name' => 'B4.06',
                'location' => 'Gedung 454',
                'capacity' => 60,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '016',
                'name' => 'A4.05',
                'location' => 'Gedung 454',
                'capacity' => 30,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '017',
                'name' => 'B2.08',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '018',
                'name' => 'E3.4',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '019',
                'name' => 'E3.5',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '020',
                'name' => 'B2.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '021',
                'name' => 'B2.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '022',
                'name' => 'B2.06',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '023',
                'name' => 'B2.07',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '024',
                'name' => 'B3.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '025',
                'name' => 'B3.05',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '026',
                'name' => 'B3.06',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '027',
                'name' => 'B3.07',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '028',
                'name' => 'B4.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '029',
                'name' => 'B4.05',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '030',
                'name' => 'C3.01',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '031',
                'name' => 'C3.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '032',
                'name' => 'Lab Komputer Depan',
                'location' => 'Gedung Sebelah Rektorat',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung Sebelah Rektorat'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '033',
                'name' => 'A2.01',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '034',
                'name' => 'A2.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '035',
                'name' => 'A2.03',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '036',
                'name' => 'A2.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '037',
                'name' => 'A2.05',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '038',
                'name' => 'A3.01',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '039',
                'name' => 'B4.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '040',
                'name' => 'A3.03',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '041',
                'name' => 'A3.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '042',
                'name' => 'A4.01',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '043',
                'name' => 'A4.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '044',
                'name' => 'A4.03',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '045',
                'name' => 'A4.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '046',
                'name' => 'C2.03',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '047',
                'name' => 'C2.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '048',
                'name' => 'C3.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '049',
                'name' => 'C3.05',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '050',
                'name' => 'A3.02',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '051',
                'name' => 'B4.07',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '052',
                'name' => 'B3.04',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '053',
                'name' => 'C4.01',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '054',
                'name' => 'C3.1.1',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '055',
                'name' => 'C3.1.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '056',
                'name' => 'B3.1.1',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '057',
                'name' => 'B3.1.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '058',
                'name' => 'C3.3.1',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '059',
                'name' => 'C3.3.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '060',
                'name' => 'A4.5.1',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '061',
                'name' => 'A4.5.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '062',
                'name' => 'B4.1.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '063',
                'name' => 'B4.8.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '064',
                'name' => 'C4.5.1',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],
            [
                'unitable_type' => null,
                'unitable_id' => null,
                'code' => '065',
                'name' => 'C4.5.2',
                'location' => 'Gedung 454',
                'capacity' => 0,
                'type' => 'class',
                'description' => 'Gedung 454'
            ],

        ]);
    }
}
