<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\ClassParticipant;
use App\Models\CollegeClass;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\StudyProgram;
use App\Services\FeederService;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Nonstandard\Uuid;

class CollegeClassFeederController extends Controller
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
    // College Class
    public function getCollegeClass($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => $request->limit,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetDetailKelasKuliah', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = CollegeClass::where('feeder_id', $value['id_kelas_kuliah'])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if (!$check) {
                    $findCourse = Course::where(['feeder_id' => $value['id_matkul']])->first();
                    if ($findCourse && $findStudyProgram) {
                        CollegeClass::create([
                            'id' => $value['id_kelas_kuliah'],
                            'academic_period_id' => $value['id_semester'],
                            'study_program_id' => $findStudyProgram->id,
                            'course_id' => $findCourse->id,
                            'lecture_system_id' => null,
                            'name' => $value['nama_kelas_kuliah'],
                            'capacity' => $value['kapasitas'] == null ? 0 : $value['kapasitas'],
                            'date_start' => $value['tanggal_mulai_efektif'],
                            'date_end' => $value['tanggal_akhir_efektif'],
                            'number_of_meeting' => 16,
                            'credit_total' => $findCourse->credit_total,
                            'credit_meeting' => $findCourse->credit_meeting,
                            'credit_practicum' => $findCourse->credit_practicum,
                            'credit_practice' => $findCourse->credit_practice,
                            'credit_simulation' => $findCourse->credit_simulation,
                            'case_discussion' => $value['bahasan'],
                            'is_lock_score' => true,
                            'feeder_id' => $value['id_kelas_kuliah'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync kelas kuliah', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Student College Activity
    public function getStudentCollegeActivity($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                "limit" => $request->limit,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetListPerkuliahanMahasiswa', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $findStudent = Student::where(['nim' => $value['nim']])->first();
                if ($findStudent) {
                    $check = StudentCollegeActivity::where(['student_id' => $findStudent->id, 'academic_period_id' => $value['id_semester']])->first();
                    if (!$check) {
                        StudentCollegeActivity::create([
                            'id' => Uuid::uuid4(),
                            'academic_period_id' => $value['id_semester'],
                            'student_id' => $findStudent->id,
                            'student_status_id' => $value['id_status_mahasiswa'],
                            'grade_semester' => $value['ips'] == null ? 0 : $value['ips'],
                            'grade' => $value['ipk'] == null ? 0 : $value['ipk'],
                            'credit_semester' => $value['sks_semester'] == null ? 0 : $value['sks_semester'],
                            'credit_total' => $value['sks_total'] == null ? 0 : $value['sks_total'],
                            'tuition_fee' => $value['biaya_kuliah_smt'],
                            'finance_id' => $value['id_pembiayaan'],
                            'is_valid' => true,
                            'feeder_status' => 'SUKSES'
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync akm', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Class Participant
    public function getClassParticipant($request)
    {
        try {
            set_time_limit(0);
            $opt = [
                'limit' => $request->limit,
                'offset' => $request->offset,
            ];
            $data = new FeederService('GetPesertaKelasKuliah', $opt);
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $findStudent = Student::where(['feeder_id' => $value['id_mahasiswa']])->first();
                $findCollegeClass = CollegeClass::where(['feeder_id' => $value['id_kelas_kuliah']])->first();
                if ($findStudent && $findCollegeClass) {
                    $check = ClassParticipant::where(['student_id' => $findStudent->id, 'college_class_id' => $findCollegeClass->id])->first();
                    if (!$check) {
                        ClassParticipant::create([
                            'id' => Uuid::uuid4(),
                            'student_id' => $findStudent->id,
                            'college_class_id' => $findCollegeClass->id,
                            'is_class_coordinator' => false,
                            'feeder_id' => $value['id_kelas_kuliah'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync peserta kelas kuliah', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    //College Class
    public function uploadCollegeClass($request)
    {
        try{

            if(!isset($request->collageClass)){
                $data = CollegeClass::with(['studyProgram', 'academicPeriod', 'course'])->where('feeder_id', null);

                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->whereHas('studyProgram', function($q)use($request){
                        $q->where('id', $request->study_program_id);
                    });
                }

                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->whereHas('academicPeriod', function($q)use($request){
                        $q->where('id', $request->academic_period_id);
                    });
                }

                $datas = $data->get();

                foreach($datas as $g){
                    $list = [
                        "record" => [
                            "id_prodi" => $g->studyProgram->feeder_id,
                            "id_semester" => $g->academic_period_id,
                            "nama_kelas_kuliah" => $g->name,
                            "sks_mk" => $g->credit_total,
                            "sks_tm" => $g->credit_meeting,
                            "sks_prak" => $g->credit_practice,
                            "sks_prak_lap" => $g->credit_practicum,
                            "sks_sim" => $g->credit_simulation,
                            "bahasan" => $g->case_discussion,
                            "a_selenggara_pditt" => 1,
                            "apa_untuk_pditt" => 0,
                            "kapasitas" => $g->capacity,
                            "tanggal_mulai_efektif" => $g->date_start,
                            "tanggal_akhir_efektif" => $g->date_end,
                            "id_mou" => null,
                            "id_matkul" => $g->course->feeder_id,
                            "lingkup" => 1,
                            "mode" => "O",
                        ],
                    ];

                    $data = new FeederService('InsertKelasKuliah', $list);
                    $res = $data->runWS();

                    $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                    CollegeClass::where('id', $g->id)->update([
                        'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_kelas'] : $g->feeder_id,
                        'feeder_status' => $val,
                        'feeder_description' => $res['error_desc'],
                    ]);
                }
            }else{
                for ($numArray=0; $numArray < count($request->collageClass); $numArray++) {
                    $g = CollegeClass::with(['studyProgram', 'academicPeriod', 'course'])->where('id', $request->collageClass[$numArray])->where('feeder_id', null)->first();

                    $list = [
                        "record" => [
                            "id_prodi" => $g->studyProgram->feeder_id,
                            "id_semester" => $g->academic_period_id,
                            "nama_kelas_kuliah" => $g->name,
                            "sks_mk" => $g->credit_total,
                            "sks_tm" => $g->credit_meeting,
                            "sks_prak" => $g->credit_practice,
                            "sks_prak_lap" => $g->credit_practicum,
                            "sks_sim" => $g->credit_simulation,
                            "bahasan" => $g->case_discussion,
                            "a_selenggara_pditt" => 1,
                            "apa_untuk_pditt" => 0,
                            "kapasitas" => $g->capacity,
                            "tanggal_mulai_efektif" => $g->date_start,
                            "tanggal_akhir_efektif" => $g->date_end,
                            "id_mou" => null,
                            "id_matkul" => $g->course->feeder_id,
                            "lingkup" => 1,
                            "mode" => "O",
                        ],
                    ];

                    $data = new FeederService('InsertKelasKuliah', $list);
                    $res = $data->runWS();

                    $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                    CollegeClass::where('id', $g->id)->update([
                        'feeder_id' => $res['error_code'] == '0' ? $res['data']['id_kelas'] : $g->feeder_id,
                        'feeder_status' => $val,
                        'feeder_description' => $res['error_desc'],
                    ]);
                }
            }

            return $this->successResponse('Berhasil upload feeder Kelas Kuliah');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //edit collage class
    public function updateCollegeClass($request)
    {
        try{
            for ($numArray=0; $numArray < count($request->collegeClass); $numArray++) {
                $g = CollegeClass::where('id', $request->collegeClass[$numArray])->first();

                $list= [
                    'key' => [
                        'id_kelas_kuliah' => $g->feeder_id
                    ],
                    "record" => [
                        "id_prodi" => $g->study_program_id,
                        "id_semester" => $g->academic_period_id,
                        "nama_kelas_kuliah" => $g->name,
                        "sks_mk" => $g->credit_total,
                        "sks_tm" => $g->credit_meeting,
                        "sks_prak" => $g->credit_practice,
                        "sks_prak_lap" => $g->credit_practicum,
                        "sks_sim" => $g->credit_simulation,
                        "bahasan" => $g->case_discussion,
                        "a_selenggara_pditt" => 1,
                        "apa_untuk_pditt" => 0,
                        "kapasitas" => $g->capacity,
                        "tanggal_mulai_efektif" => $g->date_start,
                        "tanggal_akhir_efektif" => $g->date_end,
                        "id_mou" => null,
                        "id_matkul" => $g->course_id,
                        "lingkup" => 1,
                        "mode" => "O",
                    ],
                ];

                $data = new FeederService('UpdateKelasKuliah', $list);
                $res = $data->runWS();
                $success = $res['error_code'] == '0' ? 1 : 0;

                CollegeClass::where('id', $g->id)->update(
                    [
                        'feeder_id' => $success == 1 ? $res['data']['id_kelas_kuliah'] : $g->feeder_id,
                        'feeder_status' => $success == 1 ? 'SUKSES' : 'GAGAL',
                        'feeder_description' => $res['error_desc'],
                    ]
                );

            }

            return $this->successResponse('Berhasil ubah feeder kelas kuliah');


        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //delete collage class
    public function deleteCollegeClass($request)
    {
        try{

            for ($numArray=0; $numArray < count($request->collageClass); $numArray++) {
                $g = CollegeClass::where('id', $request->collageClass[$numArray])->first();

                $list= [
                    'key' => [
                        'id_kelas_kuliah' => $g->feeder_id
                    ],
                ];

                $data = new FeederService('DeleteKelasKuliah', $list);
                $res = $data->runWS();
                $success = $res['error_code'] == '0' ? 1 : 0;

                CollegeClass::where('id', $g->id)->update(
                    [
                        'feeder_id' => $success == 1 ? null : $g->feeder_id,
                        'feeder_status' => $success == 1 ? null : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]
                );

            }

            return $this->successResponse('Berhasil hapus feeder kelas kuliah');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //upload class participant
    public function uploadClassParticipant($request)
    {
        set_time_limit(0);
        try{
            if(!isset($request->classParticipant)){
                $data = ClassParticipant::with(['student', 'collegeClass'])->where('feeder_id', null);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->whereHas('collegeClass', function($q)use($request){
                        $q->where('study_program_id', $request->study_program_id);
                    });
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->whereHas('collegeClass', function($q)use($request){
                        $q->where('academic_period_id', $request->academic_period_id);
                    });
                }
                $datas = $data->get();

                foreach($datas as $d){
                        //bimbing
                        $list = [
                            'record' => [
                                "id_kelas_kuliah" => $d->college_class_id,
                                "id_registrasi_mahasiswa" => $d->student->reg_id
                            ],
                        ];
                        $data = new FeederService('InsertPesertaKelasKuliah', $list);
                        $res = $data->runWS();
            
                        $success = $res['error_code'] == '0' ? 1 : 0;
    
                        ClassParticipant::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_kelas_kuliah'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                }
            }else{
                for ($i=0; $i < count($request->classParticipant); $i++) { 
                    $d = ClassParticipant::with(['student', 'collegeClass'])->where('feeder_id', null)->where('id', $request->classParticipant[$i])->first();
                    $list = [
                        'record' => [
                            "id_kelas_kuliah" => $d->college_class_id,
                            "id_registrasi_mahasiswa" => $d->student->reg_id,
                        ],
                    ];
                    $data = new FeederService('InsertPesertaKelasKuliah', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    ClassParticipant::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? $res['data']['id_kelas_kuliah'] : null,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
                }
            }
            return $this->successResponse('Berhasil upload feeder peserta kelas kuliah');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //delete class participant
    public function deleteClassParticipant($request)
    {
        set_time_limit(0);
        try{
            for ($i=0; $i < count($request->classParticipant); $i++) { 
                $d = ClassParticipant::with(['student', 'collegeClass'])->where('id', $request->classParticipant[$i])->first();
                $list = [
                    'key' => [
                        "id_kelas_kuliah" => $d->college_class_id,
                        "id_registrasi_mahasiswa" => $d->student->reg_id,
                    ],
                ];
                $data = new FeederService('DeletePesertaKelasKuliah', $list);
                $res = $data->runWS();
    
                $success = $res['error_code'] == '0' ? 1 : 0;

                ClassParticipant::where('id', $d->id)->update(
                    [
                        'feeder_id' => $success == 1 ? null : $d->feeder_id,
                        'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                        'feeder_description' => $res['error_desc'],
                    ]
                );
            }
            return $this->successResponse('Berhasil hapus feeder peserta kelas kuliah');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //student college activity
    public function uploadStudentCollegeActivity($request)
    {
        try{
            if(!isset($request->lecturer)){
                $data = StudentCollegeActivity::with(['academicPeriod', 'student', 'studentStatus'])->where('feeder_id', null);
                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->whereHas('student', function($q)use($request){
                        $q->where('study_program_id', $request->study_program_id);
                    });
                }
                if(
                    isset($request->student_status_id) &&
                    $request->student_status_id != NULL &&
                    $request->student_status_id != "" &&
                    $request->student_status_id != "all"
                ){
                    $data->whereHas('student', function($q)use($request){
                        $q->where('student_status_id', $request->student_status_id);
                    });
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $data->where('academic_period_id', $request->academic_period_id);
                }

                $datas = $data->get();

                foreach($datas as $d){
                    $list = [
                        'record' => [
                            "id_registrasi_mahasiswa" => $d->student->reg_id,
                            "id_semester" => $d->academic_period_id,
                            "id_status_mahasiswa" => $d->student_status_id,
                            "ips" => $d->grade_semester,
                            "ipk" => $d->grade,
                            "sks_semester" => $d->credit_semester,
                            "total_sks" => $d->credit_total,
                            "biaya_kuliah_smt" => $d->tuition_fee
                        ],
                    ];

                    $data = new FeederService('InsertPerkuliahanMahasiswa', $list);
                        $res = $data->runWS();
            
                        $success = $res['error_code'] == '0' ? 1 : 0;
    
                        ClassParticipant::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_registrasi_mahasiswa'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                }
            }else{
                for ($i=0; $i < count($request->lecturer); $i++) {
                    $d = StudentCollegeActivity::with(['academicPeriod', 'student', 'studentStatus'])->where('feeder_id', null)->where('id', $request->lecturer[$i])->first();
                    $list = [
                        'record' => [
                            "id_registrasi_mahasiswa" => $d->student->reg_id,
                            "id_semester" => $d->academic_period_id,
                            "id_status_mahasiswa" => $d->student_status_id,
                            "ips" => $d->grade_semester,
                            "ipk" => $d->grade,
                            "sks_semester" => $d->credit_semester,
                            "total_sks" => $d->credit_total,
                            "biaya_kuliah_smt" => $d->tuition_fee
                        ],
                    ];
                    $data = new FeederService('InsertPerkuliahanMahasiswa', $list);
                        $res = $data->runWS();
            
                        $success = $res['error_code'] == '0' ? 1 : 0;
    
                        ClassParticipant::where('id', $d->id)->update(
                            [
                                'feeder_id' => $success == 1 ? $res['data']['id_registrasi_mahasiswa'] : null,
                                'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                                'feeder_description' => $res['error_desc'],
                            ]
                        );
                }
            }

            return $this->successResponse('Berhasil upload feeder AKM');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function updateStudentCollegeActivity($request)
    {
        try{
            for ($i=0; $i < count($request->lecturer); $i++) {
                $d = StudentCollegeActivity::with(['academicPeriod', 'student', 'studentStatus'])->where('id', $request->lecturer[$i])->first();
                $list = [
                    'key' => [
                        'id_registrasi_mahasiswa' => $d->student->reg_id,
                        "id_semester" => $d->academic_period_id,
                    ],
                    'record' => [
                        "id_registrasi_mahasiswa" => $d->student->reg_id,
                        "id_semester" => $d->academic_period_id,
                        "id_status_mahasiswa" => $d->student_status_id,
                        "ips" => $d->grade_semester,
                        "ipk" => $d->grade,
                        "sks_semester" => $d->credit_semester,
                        "total_sks" => $d->credit_total,
                        "biaya_kuliah_smt" => $d->tuition_fee
                    ],
                ];
                $data = new FeederService('UpdatePerkuliahanMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    ClassParticipant::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? $res['data']['id_registrasi_mahasiswa'] : $d->feeder_id,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
            }
            return $this->successResponse('Berhasil update feeder');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function deleteStudentCollegeActivity($request)
    {
        try{
            for ($i=0; $i < count($request->lecturer); $i++) {
                $d = StudentCollegeActivity::with(['academicPeriod', 'student', 'studentStatus'])->where('id', $request->lecturer[$i])->first();
                $list = [
                    'key' => [
                        'id_registrasi_mahasiswa' => $d->student->reg_id,
                        "id_semester" => $d->academic_period_id,
                    ],
                ];
                $data = new FeederService('DeletePerkuliahanMahasiswa', $list);
                    $res = $data->runWS();
        
                    $success = $res['error_code'] == '0' ? 1 : 0;

                    ClassParticipant::where('id', $d->id)->update(
                        [
                            'feeder_id' => $success == 1 ? null : $d->feeder_id,
                            'feeder_status' => $success == 0 ? 'GAGAL' : 'SUKSES',
                            'feeder_description' => $res['error_desc'],
                        ]
                    );
            }
            return $this->successResponse('Berhasil hapus feeder');
        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}

    
