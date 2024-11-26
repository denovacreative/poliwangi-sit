<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Graduation;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentActivityMember;
use App\Models\StudentActivitySupervisor;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use App\Models\Transcript;
use App\Services\FeederService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StudentFeederController extends Controller
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
    // Student
    public function getStudent($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => $request->limit,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $studentBiodata = new FeederService('GetDataLengkapMahasiswaProdi', $opt);
            $resStudentBiodata = $studentBiodata->runWS();
            
            foreach ($resStudentBiodata['data'] as $key => $value) {
                $findStudent = Student::where(['feeder_id' => $value['id_mahasiswa']])->first();
                $findStatus = StudentStatus::where(['name' => ucwords(strtolower($value['nama_status_mahasiswa']))])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if ($findStudyProgram) {
                    if (!$findStudent) {
                        Student::create([
                            'id' => $value['id_mahasiswa'],
                            'study_program_id' => $findStudyProgram->id,
                            'nim' => $value['nim'],
                            'name' => $value['nama_mahasiswa'],
                            'gender' => $value['jenis_kelamin'],
                            'birthplace' => $value['tempat_lahir'],
                            'birthdate' => $value['tanggal_lahir'],
                            'nik' => $value['nik'],
                            'nisn' => $value['nisn'],
                            'phone_number' => $value['handphone'],
                            'house_phone_number' => $value['telepon'],
                            'email' => $value['email'],
                            'tax_number' => $value['npwp'],
                            'street' => $value['jalan'],
                            'address' => null,
                            'neighbourhood' => $value['rt'],
                            'hamlet' => $value['rw'],
                            'village_lev_1' => $value['kelurahan'],
                            'village_lev_2' => $value['dusun'],
                            'postal_code' => $value['kode_pos'],
                            'kps_number' => $value['nomor_kps'],
                            'is_kps' => $value['penerima_kps'],
                            'father_nik' => $value['nik_ayah'],
                            'father_name' => $value['nama_ayah'],
                            'father_birthdate' => $value['tanggal_lahir_ayah'],
                            'father_education_id' => $value['id_pendidikan_ayah'],
                            'father_profession_id' => $value['id_pekerjaan_ayah'],
                            'father_income_id' => $value['id_penghasilan_ayah'] == 3 ? null : $value['id_penghasilan_ayah'],
                            'mother_nik' => $value['nik_ibu'],
                            'mother_name' => $value['nama_ibu_kandung'],
                            'mother_birthdate' => $value['tanggal_lahir_ibu'],
                            'mother_education_id' => $value['id_pendidikan_ibu'],
                            'mother_profession_id' => $value['id_pekerjaan_ibu'],
                            'mother_income_id' => $value['id_penghasilan_ibu'] == 3 ? null : $value['id_penghasilan_ibu'],
                            'guardian_name' => $value['nama_wali'],
                            'guardian_birthdate' => $value['tanggal_lahir_wali'],
                            'guardian_education_id' => $value['id_pendidikan_wali'],
                            'guardian_profession_id' => $value['id_pekerjaan_wali'],
                            'guardian_income_id' => $value['id_penghasilan_wali'] == 3 ? null : $value['id_penghasilan_wali'],
                            'is_valid' => true,
                            'academic_period_id' => $value['id_periode_masuk'],
                            'lecture_system_id' => 1,
                            'student_status_id' => isset($findStatus) ? $findStatus->id : null,
                            'religion_id' => $value['id_agama'],
                            'country_id' => $value['id_negara'],
                            'transportation_id' => $value['id_alat_transportasi'],
                            'region_id' => $value['id_wilayah'] == 0 ? null : $value['id_wilayah'],
                            'registration_path_id' => $value['jalur_masuk'],
                            'type_of_stay_id' => $value['id_jenis_tinggal'],
                            'reg_id' => $value['id_registrasi_mahasiswa'],
                            'feeder_id' => $value['id_mahasiswa'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    } else {
                        $findStudent->update([
                            'study_program_id' => $findStudyProgram->id,
                            'nim' => $value['nim'],
                            'name' => $value['nama_mahasiswa'],
                            'gender' => $value['jenis_kelamin'],
                            'birthplace' => $value['tempat_lahir'],
                            'birthdate' => $value['tanggal_lahir'],
                            'nik' => $value['nik'],
                            'nisn' => $value['nisn'],
                            'phone_number' => $value['handphone'],
                            'house_phone_number' => $value['telepon'],
                            'email' => $value['email'],
                            'tax_number' => $value['npwp'],
                            'street' => $value['jalan'],
                            'address' => null,
                            'neighbourhood' => $value['rt'],
                            'hamlet' => $value['rw'],
                            'village_lev_1' => $value['kelurahan'],
                            'village_lev_2' => $value['dusun'],
                            'postal_code' => $value['kode_pos'],
                            'kps_number' => $value['nomor_kps'],
                            'is_kps' => $value['penerima_kps'],
                            'father_nik' => $value['nik_ayah'],
                            'father_name' => $value['nama_ayah'],
                            'father_birthdate' => $value['tanggal_lahir_ayah'],
                            'father_education_id' => $value['id_pendidikan_ayah'],
                            'father_profession_id' => $value['id_pekerjaan_ayah'],
                            'father_income_id' => $value['id_penghasilan_ayah'] == 3 ? null : $value['id_penghasilan_ayah'],
                            'mother_nik' => $value['nik_ibu'],
                            'mother_name' => $value['nama_ibu_kandung'],
                            'mother_birthdate' => $value['tanggal_lahir_ibu'],
                            'mother_education_id' => $value['id_pendidikan_ibu'],
                            'mother_profession_id' => $value['id_pekerjaan_ibu'],
                            'mother_income_id' => $value['id_penghasilan_ibu'] == 3 ? null : $value['id_penghasilan_ibu'],
                            'guardian_name' => $value['nama_wali'],
                            'guardian_birthdate' => $value['tanggal_lahir_wali'],
                            'guardian_education_id' => $value['id_pendidikan_wali'],
                            'guardian_profession_id' => $value['id_pekerjaan_wali'],
                            'guardian_income_id' => $value['id_penghasilan_wali'] == 3 ? null : $value['id_penghasilan_wali'],
                            'is_valid' => true,
                            'academic_period_id' => $value['id_periode_masuk'],
                            'lecture_system_id' => 1,
                            'student_status_id' => isset($findStatus) ? $findStatus->id : null,
                            'religion_id' => $value['id_agama'],
                            'country_id' => $value['id_negara'],
                            'transportation_id' => $value['id_alat_transportasi'],
                            'region_id' => $value['id_wilayah'] == 0 ? null : $value['id_wilayah'],
                            'registration_path_id' => $value['jalur_masuk'],
                            'type_of_stay_id' => $value['id_jenis_tinggal'],
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync mahasiswa', $resStudentBiodata);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    // Student Activity
    public function getStudentActivity($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => 5,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetListAktivitasMahasiswa', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = StudentActivity::where('feeder_id', $value['id_aktivitas'])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if (!$check) {
                    if ($findStudyProgram) {
                        StudentActivity::create([
                            'id' => $value['id_aktivitas'],
                            'name' => $value['judul'],
                            'group' => null,
                            'location' => $value['lokasi'],
                            'start_date' => null,
                            'end_date' => null,
                            'type' => $value['jenis_anggota'],
                            'description' => $value['keterangan'],
                            'decree_number' => $value['sk_tugas'],
                            'decree_date' => $value['tanggal_sk_tugas'],
                            'is_mbkm' => $value['untuk_kampus_merdeka'],
                            'study_program_id' => $findStudyProgram->id,
                            'academic_period_id' => $value['id_semester'],
                            'student_activity_category_id' => $value['id_jenis_aktivitas'],
                            'feeder_id' => $value['id_aktivitas'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync aktivitas mahasiswa', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Student Activity Member
    public function getStudentActivityMember($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetListAnggotaAktivitasMahasiswa');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = StudentActivityMember::where('feeder_id', $value['id_anggota'])->first();
                if (!$check) {
                    $findStudent = Student::where(['nim' => $value['nim']])->first();
                    $findActivity = StudentActivity::where('feeder_id', $value['id_aktivitas'])->first();
                    if ($findStudent && $findActivity) {
                        StudentActivityMember::create([
                            'id' => $value['id_anggota'],
                            'student_id' => $findStudent->id,
                            'role_type' => $value['jenis_peran'],
                            'student_activity_id' => $findActivity->id,
                            'feeder_id' => $value['id_anggota'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync aktivitas anggota', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Student Activity Supervisor 
    public function getStudentActivitySupervisor($request)
    {
        try {
            set_time_limit(0);
            $dataSupervisorOne = new FeederService('GetListBimbingMahasiswa');
            $dataSupervisorTwo = new FeederService('GetListUjiMahasiswa');
            $resSupervisorOne = $dataSupervisorOne->runWS();
            $resSupervisorTwo = $dataSupervisorTwo->runWS();
            foreach ($resSupervisorOne['data'] as $key => $valueOne) {
                $checkEmployeeOne = Employee::where('feeder_id', $valueOne['id_dosen'])->first();
                $checkOne = StudentActivityMember::where('feeder_id', $valueOne['id_bimbing_mahasiswa'])->first();
                $findActivityOne = StudentActivity::where('feeder_id', $valueOne['id_aktivitas'])->first();
                if ($checkEmployeeOne && $findActivityOne) {
                    if (!$checkOne) {
                        StudentActivitySupervisor::create([
                            'id' => Uuid::uuid4(),
                            'employee_id' => $checkEmployeeOne->id,
                            'role_type' => '0',
                            'number' => $valueOne['pembimbing_ke'],
                            'student_activity_id' => $findActivityOne->id,
                            'activity_category_id' => $valueOne['id_kategori_kegiatan'],
                            'feeder_id' => $valueOne['id_bimbing_mahasiswa'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            foreach ($resSupervisorTwo['data'] as $key => $valueTwo) {
                $checkTwo = StudentActivityMember::where('feeder_id', $valueTwo['id_uji'])->first();
                $checkEmployeeTwo = Employee::where('feeder_id', $valueTwo['id_dosen'])->first();
                $findActivityTwo = StudentActivity::where('feeder_id', $valueTwo['id_aktivitas'])->first();
                if ($checkEmployeeOne) {
                    if (!$checkTwo) {
                        StudentActivitySupervisor::create([
                            'id' => Uuid::uuid4(),
                            'employee_id' => $checkEmployeeTwo->id,
                            'role_type' => '1',
                            'number' => $valueTwo['penguji_ke'],
                            'student_activity_id' => $findActivityTwo->id,
                            'activity_category_id' => $valueTwo['id_kategori_kegiatan'],
                            'feeder_id' => $valueTwo['id_uji'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync aktivitas bimbing uji');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    // Student Graduation
    public function getStudentGraduation($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => $request->limit,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetListMahasiswaLulusDO', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $findStudent = Student::where(['feeder_id' => $value['id_mahasiswa']])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if ($findStudent && $findStudyProgram) {
                    $check = Graduation::where('student_id', $findStudent->id)->first();
                    if (!$check) {
                        Graduation::create([
                            'id' => Uuid::uuid4(),
                            'academic_period_id' => $value['id_periode_keluar'],
                            'student_id' => $findStudent->id,
                            'student_status_id' => $value['id_jns_keluar'],
                            'study_program_id' => $findStudyProgram->id,
                            'graduation_predicate_id' => null,
                            'name' => $value['nama_mahasiswa'],
                            'graduation_date' => $value['tgl_keluar'],
                            'judiciary_number' => $value['sk_yudisium'],
                            'judiciary_date' => $value['tgl_sk_yudisium'],
                            'grade' => $value['ipk'] == null ? 0 : $value['ipk'],
                            'certificate_number' => $value['no_seri_ijazah'],
                            'year' => $value['tgl_keluar'] == null ? null : Carbon::parse($value['tgl_keluar'])->format('Y'),
                            'description' => $value['keterangan'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync kelulusan', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Transcript
    public function getTranscript($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetTranskripMahasiswa');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $checkStudent = Student::where('reg_id', $value['id_registrasi_mahasiswa'])->first();
                $checkCourse = Course::where('feeder_id', $value['id_matkul'])->first();
                $checkCollegeClass = CollegeClass::where('feeder_id', $value['id_kelas_kuliah'])->first();
                if ($checkStudent && $checkCourse && $checkCollegeClass) {
                    $check = Transcript::where(['student_id' => $checkStudent->id, 'course_id' => $checkCourse->id, 'college_class_id' => $checkCollegeClass->id])->first();
                    if (!$check) {
                        Transcript::create([
                            'id' => Uuid::uuid4(),
                            'student_id' => $checkStudent->id,
                            'course_id' => $checkCourse->id,
                            'college_class_id' => $checkCollegeClass->id,
                            'score_transfer_id' => $value['id_nilai_transfer'],
                            'activity_score_conversion_id' => $value['id_konversi_aktivitas'],
                            'credit' => $value['sks_mata_kuliah'],
                            'semester' => $value['smt_diambil'],
                            'score' => $value['nilai_angka'] == null ? 0 : $value['nilai_angka'],
                            'grade' => $value['nilai_huruf'],
                            'index_score' => $value['nilai_indeks'],
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync transkrip', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    //student
    public function uploadStudent($request){
        try{
            if(!isset($request->students)){
                $data = Student::where('feeder_id', null);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->where('study_program_id', $request->study_program_id);
    
                }
                if(
                    isset($request->student_status_id) &&
                    $request->student_status_id != NULL &&
                    $request->student_status_id != "" &&
                    $request->student_status_id != "all"
                ){
                    $data->where('student_status_id', $request->student_status_id);
    
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->whereHas('academicPeriod', function($q)use($request){
                        $q->where('academic_year_id', $request->academic_year_id);
                    });
                }
                $datas = $data->get();

                foreach($datas as $g){

                    $list = [
                        'record' => [
                            "nama_mahasiswa" =>  $g->name,
                            "jenis_kelamin" => $g->gender,
                            "tempat_lahir" => $g->birthplace,
                            "tanggal_lahir" => $g->birthdate,
                            "id_agama" => $g->religion_id,
                            "nik" => $g->nik,
                            "nisn" => $g->nisn,
                            "kewarganegaraan" => $g->country_id,
                            "jalan" => $g->street,
                            "dusun" => $g->village_lev_2,
                            "rt" => $g->neighbourhood,
                            "rw" => $g->hamlet,
                            "kelurahan" => $g->village_lev_1,
                            "kode_pos" => $g->postal_code,
                            "id_wilayah" => strval($g->region_id.' '),
                            "id_jenis_tinggal" => $g->type_of_stay_id,
                            "id_alat_transportasi" => $g->transportation_id,
                            "telepon" => $g->house_phone_number,
                            "handphone" => $g->phone_number,
                            "email" => $g->email,
                            "penerima_kps" => ($g->is_kps == 0 ? "Tidak" : 'Iya'),
                            "nomor_kps" => $g->kps_number,
                            "nik_ayah" => $g->father_nik,
                            "nama_ayah" => $g->father_name,
                            "tanggal_lahir_ayah" => $g->father_birthdate,
                            "id_pendidikan_ayah" => $g->father_education_id,
                            "id_pekerjaan_ayah" => $g->father_profession_id,
                            "id_penghasilan_ayah" => $g->father_income_id,
                            "nik_ibu" => $g->mother_nik,
                            "nama_ibu_kandung" => $g->mother_name,
                            "tanggal_lahir_ibu" => $g->mother_birthdate,
                            "id_pendidikan_ibu" => $g->mother_education_id,
                            "id_pekerjaan_ibu" => $g->mother_profession_id,
                            "id_penghasilan_ibu" => $g->mother_income_id,
                            "npwp" => $g->tax_number,
                            "nama_wali" => $g->guardian_name,
                            "tanggal_lahir_wali" => $g->guardian_birthdate,
                            "id_pendidikan_wali" => $g->guardian_education_date,
                            "id_pekerjaan_wali" => $g->guardian_profession_date,
                            "id_penghasilan_wali" => $g->guardian_income_date,
                            "id_kebutuhan_khusus_mahasiswa" => 0,
                            "id_kebutuhan_khusus_ayah" => 0,
                            "id_kebutuhan_khusus_ibu" => 0
                        ],
                    ];

                    $data = new FeederService('InsertBiodataMahasiswa', $list);
                    $res = $data->runWS();
        
                    $id_mhs_feeder = isset($res['data']['id_mahasiswa']) ? $res['data']['id_mahasiswa'] : null;

                    Student::where('id', $g->id)->update(
                        [
                            'feeder_id' => $id_mhs_feeder,
                            'feeder_status' => $id_mhs_feeder == NULL ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );

                }
            }else{
                for ($numArray=0; $numArray < count($request->students); $numArray++) {
                    $g = Student::where('id', $request->students[$numArray])->where('feeder_id', null)->first();

                    $list = [
                        'record' => [
                            "nama_mahasiswa" =>  $g->name,
                            "jenis_kelamin" => $g->gender,
                            "tempat_lahir" => $g->birthplace,
                            "tanggal_lahir" => $g->birthdate,
                            "id_agama" => $g->religion_id,
                            "nik" => $g->nik,
                            "nisn" => $g->nisn,
                            "kewarganegaraan" => $g->country_id,
                            "jalan" => $g->street,
                            "dusun" => $g->village_lev_2,
                            "rt" => $g->neighbourhood,
                            "rw" => $g->hamlet,
                            "kelurahan" => $g->village_lev_1,
                            "kode_pos" => $g->postal_code,
                            "id_wilayah" => strval($g->region_id.' '),
                            "id_jenis_tinggal" => $g->type_of_stay_id,
                            "id_alat_transportasi" => $g->transportation_id,
                            "telepon" => $g->house_phone_number,
                            "handphone" => $g->phone_number,
                            "email" => $g->email,
                            "penerima_kps" => ($g->is_kps == 0 ? "Tidak" : 'Iya'),
                            "nomor_kps" => $g->kps_number,
                            "nik_ayah" => $g->father_nik,
                            "nama_ayah" => $g->father_name,
                            "tanggal_lahir_ayah" => $g->father_birthdate,
                            "id_pendidikan_ayah" => $g->father_education_id,
                            "id_pekerjaan_ayah" => $g->father_profession_id,
                            "id_penghasilan_ayah" => $g->father_income_id,
                            "nik_ibu" => $g->mother_nik,
                            "nama_ibu_kandung" => $g->mother_name,
                            "tanggal_lahir_ibu" => $g->mother_birthdate,
                            "id_pendidikan_ibu" => $g->mother_education_id,
                            "id_pekerjaan_ibu" => $g->mother_profession_id,
                            "id_penghasilan_ibu" => $g->mother_income_id,
                            "npwp" => $g->tax_number,
                            "nama_wali" => $g->guardian_name,
                            "tanggal_lahir_wali" => $g->guardian_birthdate,
                            "id_pendidikan_wali" => $g->guardian_education_date,
                            "id_pekerjaan_wali" => $g->guardian_profession_date,
                            "id_penghasilan_wali" => $g->guardian_income_date,
                            "id_kebutuhan_khusus_mahasiswa" => 0,
                            "id_kebutuhan_khusus_ayah" => 0,
                            "id_kebutuhan_khusus_ibu" => 0
                        ],
                    ];

                    $data = new FeederService('InsertBiodataMahasiswa', $list);
                    $res = $data->runWS();
        
                    $id_mhs_feeder = isset($res['data']['id_mahasiswa']) ? $res['data']['id_mahasiswa'] : null;

                    Student::where('id', $g->id)->update(
                        [
                            'feeder_id' => $id_mhs_feeder,
                            'feeder_status' => $id_mhs_feeder == NULL ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
                }
            }

            return $this->successResponse('Berhasil upload data feeder');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function updateStudent($request)
    {
        try{
            for ($numArray=0; $numArray < count($request->students); $numArray++) {
                $g = Student::where('id', $request->students[$numArray])->first();
                $list = [
                    'key' => [
                        'id_mahasiswa' => $g->feeder_id,
                    ],
                    'record' => [
                        "nama_mahasiswa" =>  $g->name,
                        "jenis_kelamin" => $g->gender,
                        "tempat_lahir" => $g->birthplace,
                        "tanggal_lahir" => $g->birthdate,
                        "id_agama" => $g->religion_id,
                        "nik" => $g->nik,
                        "nisn" => $g->nisn,
                        "kewarganegaraan" => $g->country_id,
                        "jalan" => $g->street,
                        "dusun" => $g->village_lev_2,
                        "rt" => $g->neighbourhood,
                        "rw" => $g->hamlet,
                        "kelurahan" => $g->village_lev_1,
                        "kode_pos" => $g->postal_code,
                        "id_wilayah" => strval($g->region_id),
                        "id_jenis_tinggal" => $g->type_of_stay_id,
                        "id_alat_transportasi" => $g->transportation_id,
                        "telepon" => $g->house_phone_number,
                        "handphone" => $g->phone_number,
                        "email" => $g->email,
                        "penerima_kps" => ($g->is_kps == 0 ? "Tidak" : 'Iya'),
                        "nomor_kps" => $g->kps_number,
                        "nik_ayah" => $g->father_nik,
                        "nama_ayah" => $g->father_name,
                        "tanggal_lahir_ayah" => $g->father_birthdate,
                        "id_pendidikan_ayah" => $g->father_education_id,
                        "id_pekerjaan_ayah" => $g->father_profession_id,
                        "id_penghasilan_ayah" => $g->father_income_id,
                        "nik_ibu" => $g->mother_nik,
                        "nama_ibu_kandung" => $g->mother_name,
                        "tanggal_lahir_ibu" => $g->mother_birthdate,
                        "id_pendidikan_ibu" => $g->mother_education_id,
                        "id_pekerjaan_ibu" => $g->mother_profession_id,
                        "id_penghasilan_ibu" => $g->mother_income_id,
                        "npwp" => $g->tax_number,
                        "nama_wali" => $g->guardian_name,
                        "tanggal_lahir_wali" => $g->guardian_birthdate,
                        "id_pendidikan_wali" => $g->guardian_education_date,
                        "id_pekerjaan_wali" => $g->guardian_profession_date,
                        "id_penghasilan_wali" => $g->guardian_income_date,
                        "id_kebutuhan_khusus_mahasiswa" => 0,
                        "id_kebutuhan_khusus_ayah" => 0,
                        "id_kebutuhan_khusus_ibu" => 0
                    ],
                ];

                // return response()->json([
                //     $list
                // ],500);

                $data = new FeederService('UpdateBiodataMahasiswa', $list);
                $res = $data->runWS();

                $id_mhs_feeder = $res['error_code'] == '0' ? 1 : 0;

                    Student::where('id', $g->id)->update(
                        [
                            'feeder_id' => $id_mhs_feeder == 1 ? $res['data']['id_mahasiswa'] : $g->feeder_id,
                            'feeder_status' => $id_mhs_feeder == 1 ? 'SUKSES' : 'GAGAL',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
            }

            return $this->successResponse('Berhasil edit feeder mahasiswa');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function deleteStudent($request)
    {
        try{

            for ($numArray=0; $numArray < count($request->students); $numArray++) {
                $g = Student::where('id', $request->students[$numArray])->first();

                $list= [
                    'key' => [
                        'id_mahasiswa' => $g->feeder_id
                    ],
                ];

                $data = new FeederService('DeleteBiodataMahasiswa', $list);
                $res = $data->runWS();
                $success = $res['error_code'] == '0' ? 1 : 0;

                Student::where('id', $g->id)->update(
                    [
                        'feeder_id' => $success == 1 ? null : $g->feeder_id,
                        'feeder_status' => $success == 1 ? 'SUKSES' : 'GAGAL',
                        'feeder_description' => $res['error_desc'],
                    ]
                );

            }

            return $this->successResponse('Berhasil hapus feeder mahasiswa');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //student activity
    public function uploadStudentActivity($request)
    {
        set_time_limit(0);
        try{

            if(!isset($request->studentActivity)){
                $data = StudentActivity::with(['studyProgram', 'academicPeriod'])->where('feeder_id', null);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->where('study_program_id', $request->study_program_id);
    
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->where('academic_period_id', $request->academic_period_id);
    
                }
                if(
                    isset($request->student_activity_category_id) &&
                    $request->student_activity_category_id != NULL &&
                    $request->student_activity_category_id != "" &&
                    $request->student_activity_category_id != "all"
                ){
                    $data->where('student_activity_category_id', $request->student_activity_category_id);
    
                }

                $datas = $data->get();
                foreach($datas as $d){

                    $list = [
                        'record' => [
                            "jenis_anggota" => $d->type,
                            "id_jenis_aktivitas" => $d->student_activity_category_id,
                            "id_prodi" => $d->study_program_id,
                            "id_semester" => $d->academic_period_id,
                            "judul" => $d->name,
                            "keterangan" => $d->description,
                            "lokasi" => $d->location,
                            "sk_tugas" => $d->decree_number,
                            "tanggal_sk_tugas" => $d->decree_date
                        ],
                    ];

                    $data = new FeederService('InsertAktivitasMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    StudentActivity::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? $res['data']['id_aktivitas'] : null,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );

                }

            }else{
                for ($i=0; $i < count($request->studentActivity); $i++) {

                    $d = StudentActivity::with(['studyProgram', 'academicPeriod'])->where('feeder_id', null)->where('id', $request->studentActivity[$i])->first();
                    $list = [
                        'record' => [
                            "jenis_anggota" => $d->type,
                            "id_jenis_aktivitas" => $d->student_activity_category_id,
                            "id_prodi" => $d->study_program_id,
                            "id_semester" => $d->academic_period_id,
                            "judul" => $d->name,
                            "keterangan" => $d->description,
                            "lokasi" => $d->location,
                            "sk_tugas" => $d->decree_number,
                            "tanggal_sk_tugas" => $d->decree_date
                        ],
                    ];

                    // return response()->json([$list], 500);
                    $data = new FeederService('InsertAktivitasMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    StudentActivity::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? $res['data']['id_aktivitas'] : null,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );


                }
            }

            return $this->successResponse('Berhasil upload feeder Aktivitas Mahasiswa');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function updateStudentActivity($request)
    {
        set_time_limit(0);
        try{
            for ($i=0; $i < count($request->studentActivity); $i++) {

                $d = StudentActivity::with(['studyProgram', 'academicPeriod'])->where('id', $request->studentActivity[$i])->first();
                $list = [
                    'record' => [
                        "jenis_anggota" => $d->type,
                        "id_jenis_aktivitas" => $d->student_activity_category_id,
                        "id_prodi" => $d->study_program_id,
                        "id_semester" => $d->academic_period_id,
                        "judul" => $d->name,
                        "keterangan" => $d->description,
                        "lokasi" => $d->location,
                        "sk_tugas" => $d->decree_number,
                        "tanggal_sk_tugas" => $d->decree_date
                    ],
                    'key' => [
                        'id_aktivitas' => $d->feeder_id
                    ],
                ];

                // return response()->json([$list], 500);
                $data = new FeederService('UpdateAktivitasMahasiswa', $list);
                $res = $data->runWS();
    
                $success = $res['error_code'] == '0' ? 1 : 0;

                StudentActivity::where('id', $d->id)->update(
                    [
                        'feeder_id' => $success == 1 ? $res['data']['id_aktivitas'] : $d->feeder_id,
                        'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]
                );


            }


            return $this->successResponse('Berhasil ubah feeder Aktivitas Mahasiswa');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
    public function deleteStudentActivity($request)
    {
        set_time_limit(0);
        try{
            for ($i=0; $i < count($request->studentActivity); $i++) {

                $d = StudentActivity::with(['studyProgram', 'academicPeriod'])->where('id', $request->studentActivity[$i])->first();
                $list = [
                    'key' => [
                        'id_aktivitas' => $d->feeder_id
                    ],
                ];

                // return response()->json([$list], 500);
                $data = new FeederService('DeleteAktivitasMahasiswa', $list);
                $res = $data->runWS();
    
                $success = $res['error_code'] == '0' ? 1 : 0;

                StudentActivity::where('id', $d->id)->update(
                    [
                        'feeder_id' => $success == 1 ? null : $d->feeder_id,
                        'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]
                );
            }
            return $this->successResponse('Berhasil hapus feeder Aktivitas Mahasiswa');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //student activity member
    public function uploadStudentActivityMember($request)
    {
        set_time_limit(0);
        try{
            if(!isset($request->studentActivityMember)){
                $data = StudentActivityMember::with(['studentActivity', 'student'])->where('feeder_id', null);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->whereHas('studentActivity', function($q)use($request){
                        $q->where('study_program_id', $request->study_program_id);
                    });
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->whereHas('studentActivity', function($q)use($request){
                        $q->where('academic_period_id', $request->academic_period_id);
                    });
                }
                if(
                    isset($request->student_activity_category_id) &&
                    $request->student_activity_category_id != NULL &&
                    $request->student_activity_category_id != "" &&
                    $request->student_activity_category_id != "all"
                ){
                    $data->whereHas('studentActivity', function($q)use($request){
                        $q->where('student_activity_category_id', $request->student_activity_category_id);
                    });
                }
                if(
                    isset($request->role_type) &&
                    $request->role_type != NULL &&
                    $request->role_type != "" &&
                    $request->role_type != "all"
                ){
                    $data->where('role_type', $request->role_type);
                }

                $datas = $data->get();
                foreach($datas as $d){

                    $list = [
                        'record' => [
                            "id_aktivitas"=> $d->studentActivity->feeder_id,
                            "id_registrasi_mahasiswa"=> $d->student->reg_id,
                            "jenis_peran"=> $d->role_type
                        ],
                    ];

                    $data = new FeederService('InsertAnggotaAktivitasMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    StudentActivityMember::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? $res['data']['id_anggota'] : null,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );

                }

            }else{
                for ($i=0; $i < count($request->studentActivityMember); $i++) {

                    $d = StudentActivityMember::with(['studentActivity', 'student'])->where('id', $request->studentActivityMember[$i])->where('feeder_id', null)->first();
                    $list = [
                        'record' => [
                            "id_aktivitas"=> $d->studentActivity->feeder_id,
                            "id_registrasi_mahasiswa"=> $d->student->reg_id,
                            "jenis_peran"=> $d->role_type
                        ],
                    ];

                    $data = new FeederService('InsertAnggotaAktivitasMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    StudentActivityMember::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? $res['data']['id_anggota'] : null,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );


                }
            }

            return $this->successResponse('Berhasil upload feeder Anggota Aktivitas Mahasiswa');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function deleteStudentActivityMember($request)
    {
        set_time_limit(0);
        try{
            for ($i=0; $i < count($request->studentActivityMember); $i++) {

                $d = StudentActivityMember::with(['studentActivity', 'student'])->where('id', $request->studentActivityMember[$i])->first();
                $list = [
                    'key' => [
                        "id_anggota"=> $d->feeder_id
                    ],
                ];

                $data = new FeederService('DeleteAnggotaAktivitasMahasiswa', $list);
                $res = $data->runWS();
    
                $success = $res['error_code'] == '0' ? 1 : 0;

                StudentActivityMember::where('id', $d->id)->update(
                    [
                        'feeder_id' => $success == 1 ? NULL : $d->feeder_id,
                        'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]
                );
            }

            return $this->successResponse('Berhasil Hapus Feeder Anggota Aktivitas Mahasiswa');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //student activity supervisor
    public function uploadStudentActivitySupervisor($request)
    {
        try{

            if(!isset($request->studentActivitySupervisor)){
                $data = StudentActivitySupervisor::with(['employee', 'studentActivity'])->where('feeder_id', null);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->whereHas('studentActivity', function($q)use($request){
                        $q->where('study_program_id', $request->study_program_id);
                    });
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->whereHas('studentActivity', function($q)use($request){
                        $q->where('academic_period_id', $request->academic_period_id);
                    });
                }
                if(
                    isset($request->student_activity_category_id) &&
                    $request->student_activity_category_id != NULL &&
                    $request->student_activity_category_id != "" &&
                    $request->student_activity_category_id != "all"
                ){
                    $data->whereHas('studentActivity', function($q)use($request){
                        $q->where('student_activity_category_id', $request->student_activity_category_id);
                    });
                }
                if(
                    isset($request->role_type) &&
                    $request->role_type != NULL &&
                    $request->role_type != "" &&
                    $request->role_type != "all"
                ){
                    $data->where('role_type', $request->role_type);
                }
                $datas = $data->get();

                foreach($datas as $d){
                    if($d->role_type == '0'){
                        //bimbing
                        $list = [
                            'record' => [
                                "id_aktivitas" => $d->studentActivity->feeder_id,
                                "id_kategori_kegiatan" => $d->activity_category_id,
                                "id_dosen" => $d->employee->feeder_id,
                                "pembimbing_ke" => $d->number,
                            ],
                        ];
                        $data = new FeederService('InsertBimbingMahasiswa', $list);
                        $res = $data->runWS();
            
                        $success = $res['error_code'] == '0' ? 1 : 0;
    
                        StudentActivitySupervisor::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_bimbing_mahasiswa'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                    }elseif($d->role_type == '1'){
                        //Uji
                        $list = [
                            'record' => [
                                "id_aktivitas" => $d->studentActivity->feeder_id,
                                "id_kategori_kegiatan" => $d->activity_category_id,
                                "id_dosen" => $d->employee->feeder_id,
                                "penguji_ke" => $d->number,
                            ],
                        ];
                        $data = new FeederService('InsertUjiMahasiswa', $list);
                        $res = $data->runWS();
                        $success = $res['error_code'] == '0' ? 1 : 0;
                        StudentActivitySupervisor::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_uji'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                    }
                }
            }else{
                for ($i=0; $i < count($request->studentActivitySupervisor); $i++) {
                    $d = StudentActivitySupervisor::with(['employee', 'studentActivity'])->where('feeder_id', null)->where('id', $request->studentActivitySupervisor[$i])->first();
                    if($d->role_type == '0'){
                        //bimbing
                        $list = [
                            'record' => [
                                "id_aktivitas" => $d->studentActivity->feeder_id,
                                "id_kategori_kegiatan" => $d->activity_category_id,
                                "id_dosen" => $d->employee->feeder_id,
                                "pembimbing_ke" => $d->number,
                            ],
                        ];
                        $data = new FeederService('InsertBimbingMahasiswa', $list);
                        $res = $data->runWS();
            
                        $success = $res['error_code'] == '0' ? 1 : 0;
    
                        StudentActivitySupervisor::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_bimbing_mahasiswa'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                    }elseif($d->role_type == '1'){
                        //Uji
                        $list = [
                            'record' => [
                                "id_aktivitas" => $d->studentActivity->feeder_id,
                                "id_kategori_kegiatan" => $d->activity_category_id,
                                "id_dosen" => $d->employee->feeder_id,
                                "penguji_ke" => $d->number,
                            ],
                        ];
                        $data = new FeederService('InsertUjiMahasiswa', $list);
                        $res = $data->runWS();
                        $success = $res['error_code'] == '0' ? 1 : 0;
                        StudentActivitySupervisor::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_uji'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                    }
                }
            }
            return $this->successResponse('Berhasil upload feeder AKT Bimbing Uji');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function deleteStudentActivitySupervisor($request)
    {
        set_time_limit(0);
        try{
            for ($i=0; $i < count($request->studentActivitySupervisor); $i++) {
                $d = StudentActivitySupervisor::with(['employee', 'studentActivity'])->where('id', $request->studentActivitySupervisor[$i])->first();
                if($d->role_type == '0'){
                    //bimbing
                    $list = [
                        'key' => [
                            "id_bimbing_mahasiswa" => $d->feeder_id,
                        ],
                    ];
                    $data = new FeederService('DeleteBimbingMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    StudentActivitySupervisor::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? null : $d->feeder_id,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
                }elseif($d->role_type == '1'){
                    //Uji
                    $list = [
                        'key' => [
                            "id_uji" => $d->feeder_id,
                        ],
                    ];
                    $data = new FeederService('DeleteUjiMahasiswa', $list);
                    $res = $data->runWS();
                    $success = $res['error_code'] == '0' ? 1 : 0;
                    StudentActivitySupervisor::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? null : $d->feeder_id,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
                }
            }
            return $this->successResponse('Berhasil hapus feeder AKT Bimbing Uji');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
