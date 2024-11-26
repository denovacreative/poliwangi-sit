<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\CollegeClass;
use App\Models\Employee;
use App\Models\TeachingLecturer;
use App\Services\FeederService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class EmployeeFeederController extends Controller
{
    public function sync(Request $request)
    {
        try {
            if (!method_exists($this, $request->act)) {
                return $this->errorResponse(500, 'Aksi tidak di temukan!');
            }
            $action = $request->act;
            return $this->$action($request);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    // Employee
    public function getEmployee($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('DetailBiodataDosen');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Employee::where('feeder_id', $value['id_dosen'])->first();
                if (!$check) {
                    Employee::create([
                        'id' => $value['id_dosen'],
                        'nip' => $value['nip'],
                        'nik' => $value['nik'],
                        'nidn' => $value['nidn'],
                        'name' => $value['nama_dosen'],
                        'gender' => $value['jenis_kelamin'],
                        'birthplace' => $value['tempat_lahir'],
                        'birthdate' => Carbon::parse($value['tanggal_lahir'])->format('Y-m-d'),
                        'phone_number' => $value['handphone'],
                        'house_phone_number' => $value['telepon'],
                        'personal_email' => $value['email'],
                        'street' => $value['jalan'],
                        'neighbourhood' => $value['rt'],
                        'hamlet' => $value['rw'],
                        'village_lev_1' => $value['ds_kel'],
                        'village_lev_2' => $value['dusun'],
                        'postal_code' => $value['kode_pos'],
                        'tax_number' => $value['npwp'],
                        'mother_name' => $value['nama_ibu_kandung'],
                        'cpns_number' => $value['no_sk_cpns'],
                        'cpns_date' => $value['tanggal_sk_cpns'],
                        'appointment_number' => $value['no_sk_pengangkatan'],
                        'appointment_end_date' => null,
                        'family_name' => $value['nama_suami_istri'],
                        'family_nip' => $value['nip_suami_istri'],
                        'is_rps' => false,
                        'employee_active_status_id' => $value['id_status_aktif'],
                        // 'employee_type_id' => $value['id_jenis_sdm'],
                        'religion_id' => $value['id_agama'],
                        'family_profession_id' => $value['id_pekerjaan_suami_istri'],
                        'region_id' => $value['id_wilayah'],
                        'feeder_id' => $value['id_dosen'],
                        'feeder_status' => 'SUKSES',
                    ]);
                } else {
                    $check->update([
                        'nip' => $value['nip'],
                        'nik' => $value['nik'],
                        'nidn' => $value['nidn'],
                        'name' => $value['nama_dosen'],
                        'gender' => $value['jenis_kelamin'],
                        'birthplace' => $value['tempat_lahir'],
                        'birthdate' => Carbon::parse($value['tanggal_lahir'])->format('Y-m-d'),
                        'phone_number' => $value['handphone'],
                        'house_phone_number' => $value['telepon'],
                        'personal_email' => $value['email'],
                        'street' => $value['jalan'],
                        'neighbourhood' => $value['rt'],
                        'hamlet' => $value['rw'],
                        'village_lev_1' => $value['ds_kel'],
                        'village_lev_2' => $value['dusun'],
                        'postal_code' => $value['kode_pos'],
                        'tax_number' => $value['npwp'],
                        'mother_name' => $value['nama_ibu_kandung'],
                        'cpns_number' => $value['no_sk_cpns'],
                        'cpns_date' => $value['tanggal_sk_cpns'],
                        'appointment_number' => $value['no_sk_pengangkatan'],
                        'appointment_end_date' => null,
                        'family_name' => $value['nama_suami_istri'],
                        'family_nip' => $value['nip_suami_istri'],
                        'is_rps' => false,
                        'employee_active_status_id' => $value['id_status_aktif'],
                        // 'employee_type_id' => $value['id_jenis_sdm'],
                        'religion_id' => $value['id_agama'],
                        'family_profession_id' => $value['id_pekerjaan_suami_istri'],
                        'region_id' => $value['id_wilayah'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync dosen', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Teaching Lecturer
    public function getTeachingLecturer($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => $request->limit,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetDosenPengajarKelasKuliah', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = TeachingLecturer::where('feeder_id', $value['id_aktivitas_mengajar'])->first();
                if (!$check) {
                    $findCollegeClass = CollegeClass::where('feeder_id', $value['id_kelas_kuliah'])->first();
                    if ($findCollegeClass) {
                        TeachingLecturer::create([
                            'id' => $value['id_aktivitas_mengajar'],
                            'lecture_substance_id' => $value['id_substansi'],
                            'evaluation_type_id' => $value['id_jenis_evaluasi'],
                            'employee_id' => $value['id_dosen'],
                            'weekly_schedule_id' => null,
                            'college_class_id' => $findCollegeClass->id,
                            'credit_total' => $findCollegeClass->credit_total,
                            'credit_meeting' => $findCollegeClass->credit_meeting,
                            'credit_practicum' => $findCollegeClass->credit_practicum,
                            'credit_practice' => $findCollegeClass->credit_practice,
                            'credit_simulation' => $findCollegeClass->credit_simulation,
                            'meeting_plan' => $value['rencana_minggu_pertemuan'] == null ? 0  : $value['rencana_minggu_pertemuan'],
                            'meeting_realization' => $value['realisasi_minggu_pertemuan'] == null ? 0 : $value['realisasi_minggu_pertemuan'],
                            'is_score_entry' => false,
                            'feeder_id' => $value['id_aktivitas_mengajar'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync dosen ajar', $res);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    //Upload Lecturer
    public function uploadEmployee($request)
    {
        try{

            
            set_time_limit(0);
            
            if(!isset($request->employee)){
                $data = 'tidak ada';
                $employee = Employee::where('feeder_id', NULL)->get();
            }else{
                $data = 'ada';
                for ($i=0; $i < count($request->employee); $i++) { 
                    $get = Employee::where('id', $request->employee[$i])->first();
                    $employee[] = [
                        'id' => $get->id,
                    ];
                }
            }
            
            return response()->json([
                'data' => $employee
            ], 500);
            
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //Upload Teaching Lecturer
    public function uploadTeachingLecturer($request)
    {
        set_time_limit(0);
        try{

            if(!isset($request->teachingLecturer)){
                $data = TeachingLecturer::with(['employee', 'collegeClass', 'lectureSubstance'])->where('feeder_id', null);

                if(
                    isset($request->academic_period_id) &&
                     $request->academic_period_id != null &&
                     $request->academic_period_id != "" &&
                     $request->academic_period_id != 'all'
                ){
                    $data->whereHas('collegeClass', function($q) use($request){
                        $q->where('academic_period_id', $request->academic_period_id);
                    });
                }
                if(
                    isset($request->study_program_id) &&
                     $request->study_program_id != null &&
                     $request->study_program_id != "" &&
                     $request->study_program_id != 'all'
                ){
                    $data->whereHas('collegeClass', function($q) use($request){
                        $q->where('study_program_id', $request->study_program_id);
                    });
                }

                $datas = $data->get();

                foreach($datas as $d){
                    $list = [
                        'record' => [
                            "id_registrasi_dosen" => $d->employee->reg_id,
                            "id_kelas_kuliah" =>  $d->college_class_id,
                            "id_substansi" =>  $d->lecture_substance_id,
                            "sks_substansi_total" =>  $d->credit_total ?? null,
                            "sks_tm_subst" => $d->credit_meeting ?? null,
                            "sks_prak_subst" => $d->credit_practicum ?? null,
                            "sks_prak_lap_subst" => $d->credit_practice ?? null,
                            "sks_sim_subst" => $d->credit_simulation ?? null,
                            "rencana_minggu_pertemuan" => $d->meeting_plan,
                            "realisasi_minggu_pertemuan" => $d->meeting_realization,
                            "id_jenis_evaluasi" => $d->evaluation_type_id
                        ],
                    ];
                    $data = new FeederService('InsertDosenPengajarKelasKuliah', $list);
                    $res = $data->runWS();

                    $success = $res['error_code'] == 0 ? 1 : 0;
    
                    TeachingLecturer::where('id', $d->id)->update([
                        'feeder_id' => $success == 1 ? $res['data']['id_aktivitas_mengajar'] : null,
                        'feeder_status' => ($success == 1 ? 'SUKSES' : 'GAGAL'),
                        'feeder_description' => $res['error_desc']
                    ]);

                }

            }else{
                for ($i=0; $i < count($request->teachingLecturer); $i++) {
                    $d = TeachingLecturer::with(['employee', 'collegeClass', 'lectureSubstance'])->where('feeder_id', null)->where('id', $request->teachingLecturer[$i])->first();

                    $list = [
                        'record' => [
                            "id_registrasi_dosen" => $d->employee->reg_id,
                            "id_kelas_kuliah" =>  $d->college_class_id,
                            "id_substansi" =>  $d->lecture_substance_id,
                            "sks_substansi_total" =>  $d->credit_total ?? null,
                            "sks_tm_subst" => $d->credit_meeting ?? null,
                            "sks_prak_subst" => $d->credit_practicum ?? null,
                            "sks_prak_lap_subst" => $d->credit_practice ?? null,
                            "sks_sim_subst" => $d->credit_simulation ?? null,
                            "rencana_minggu_pertemuan" => $d->meeting_plan,
                            "realisasi_minggu_pertemuan" => $d->meeting_realization,
                            "id_jenis_evaluasi" => $d->evaluation_type_id
                        ],
                    ];
                    $data = new FeederService('InsertDosenPengajarKelasKuliah', $list);
                    $res = $data->runWS();

                    $success = $res['error_code'] == 0 ? 1 : 0;
    
                    TeachingLecturer::where('id', $d->id)->update([
                        'feeder_id' => $success == 1 ? $res['data']['id_aktivitas_mengajar'] : null,
                        'feeder_status' => ($success == 1 ? 'SUKSES' : 'GAGAL'),
                        'feeder_description' => $res['error_desc']
                    ]);
                }
            }

            return $this->successResponse('Berhasil Upload Feeder Dosen Ajar');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //Delete Teaching Lecturer
    public function deleteTeachingLecturer($request)
    {
        set_time_limit(0);
        try{
            for ($i=0; $i < count($request->teachingLecturer); $i++) {
                $d = TeachingLecturer::with(['employee', 'collegeClass', 'lectureSubstance'])->where('id', $request->teachingLecturer[$i])->first();

                $list = [
                    'key' => [
                        'id_aktivitas_mengajar' => $d->feeder_id
                    ],
                ];

                $data = new FeederService('DeleteDosenPengajarKelasKuliah', $list);
                $res = $data->runWS();

                $success = $res['error_code'] == 0 ? 1 : 0;

                TeachingLecturer::where('id', $d->id)->update([
                    'feeder_id' => $success == 1 ? NULL : $d->feeder_id,
                    'feeder_status' => ($success == 1 ? 'SUKSES' : 'GAGAL'),
                    'feeder_description' => $res['error_desc']
                ]);
            }

            return $this->successResponse('Berhasil Hapus Feeder Dosen Ajar');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
    //update Teaching Lecturer
    public function updateTeachingLecturer($request){
        set_time_limit(0);
        
        try{
            for ($i=0; $i < count($request->teachingLecturer); $i++) {
                $d = TeachingLecturer::with(['employee', 'collegeClass', 'lectureSubstance'])->where('id', $request->teachingLecturer[$i])->first();

                $list = [
                    'record' => [
                        "id_registrasi_dosen" => $d->employee->reg_id,
                        "id_kelas_kuliah" =>  $d->college_class_id,
                        "id_substansi" =>  $d->lecture_substance_id,
                        "sks_substansi_total" =>  $d->credit_total ?? null,
                        "sks_tm_subst" => $d->credit_meeting ?? null,
                        "sks_prak_subst" => $d->credit_practicum ?? null,
                        "sks_prak_lap_subst" => $d->credit_practice ?? null,
                        "sks_sim_subst" => $d->credit_simulation ?? null,
                        "rencana_minggu_pertemuan" => $d->meeting_plan,
                        "realisasi_minggu_pertemuan" => $d->meeting_realization,
                        "id_jenis_evaluasi" => $d->evaluation_type_id
                    ],
                    'key' => [
                        'id_aktivitas_mengajar' => $d->feeder_id
                    ],
                ];

                $data = new FeederService('UpdateDosenPengajarKelasKuliah', $list);
                $res = $data->runWS();

                $success = $res['error_code'] == 0 ? 1 : 0;

                TeachingLecturer::where('id', $d->id)->update([
                    'feeder_id' => $success == 1 ? $res['data']['id_aktivitas_mengajar'] : $d->feeder_id,
                    'feeder_status' => ($success == 1 ? 'SUKSES' : 'GAGAL'),
                    'feeder_description' => $res['error_desc']
                ]);
            }

            return $this->successResponse('Berhasil Ubah Feeder Dosen Ajar');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
