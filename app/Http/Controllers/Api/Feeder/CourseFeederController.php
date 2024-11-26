<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\Curriculum;
use App\Models\StudyProgram;
use App\Services\FeederService;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class CourseFeederController extends Controller
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
    // Course
    public function getCourse($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetListMataKuliah');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Course::where('feeder_id', $value['id_matkul'])->first();
                $findStudyProgram = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if ($findStudyProgram) {
                    if (!$check) {
                        Course::create([
                            'id' => $value['id_matkul'],
                            'code' => $value['kode_mata_kuliah'],
                            'name' => $value['nama_mata_kuliah'],
                            'name_en' => null,
                            'alias' => null,
                            'credit_total' => $value['sks_mata_kuliah'] == null ? 0 : $value['sks_mata_kuliah'],
                            'credit_meeting' => $value['sks_tatap_muka'] == null ? 0 : $value['sks_tatap_muka'],
                            'credit_practicum' => $value['sks_praktek'] == null ? 0 : $value['sks_praktek'],
                            'credit_practice' => $value['sks_praktek_lapangan'] == null ? 0 : $value['sks_praktek_lapangan'],
                            'credit_simulation' => $value['sks_simulasi'] == null ? 0 : $value['sks_simulasi'],
                            'is_mku' => null,
                            'is_sap' => $value['ada_sap'],
                            'is_silabus' => $value['ada_silabus'],
                            'is_bahan_ajar' => $value['ada_bahan_ajar'],
                            'is_diktat' => $value['ada_diktat'],
                            'study_program_id' => $value['id_prodi'],
                            'course_type_id' => $value['id_jenis_mata_kuliah'] == "" ? null : $value['id_jenis_mata_kuliah'],
                            // 'course_group_id' => $value['id_kelompok_mata_kuliah'],
                            'scientific_field_id' => null,
                            'rps_employee_id' => null,
                            'feeder_id' => $value['id_matkul'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    } else {
                        $check->update([
                            'code' => $value['kode_mata_kuliah'],
                            'name' => $value['nama_mata_kuliah'],
                            'name_en' => null,
                            'alias' => null,
                            'credit_total' => $value['sks_mata_kuliah'] == null ? 0 : $value['sks_mata_kuliah'],
                            'credit_meeting' => $value['sks_tatap_muka'] == null ? 0 : $value['sks_tatap_muka'],
                            'credit_practicum' => $value['sks_praktek'] == null ? 0 : $value['sks_praktek'],
                            'credit_practice' => $value['sks_praktek_lapangan'] == null ? 0 : $value['sks_praktek_lapangan'],
                            'credit_simulation' => $value['sks_simulasi'] == null ? 0 : $value['sks_simulasi'],
                            'is_mku' => null,
                            'is_sap' => $value['ada_sap'],
                            'is_silabus' => $value['ada_silabus'],
                            'is_bahan_ajar' => $value['ada_bahan_ajar'],
                            'is_diktat' => $value['ada_diktat'],
                            'study_program_id' => $value['id_prodi'],
                            'course_type_id' => $value['id_jenis_mata_kuliah'] == "" ? null : $value['id_jenis_mata_kuliah'],
                            // 'course_group_id' => $value['id_kelompok_mata_kuliah'],
                            'scientific_field_id' => null,
                            'rps_employee_id' => null,
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync matakuliah', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Course Curriculum
    public function getCourseCurriculum($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetMatkulKurikulum');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $findCourse = Course::where('feeder_id', $value['id_matkul'])->first();
                $findCurriculum = Curriculum::where('feeder_id', $value['id_kurikulum'])->first();
                if ($findCourse && $findCurriculum) {
                    $check = CourseCurriculum::where(['course_id' => $findCourse->id, 'curriculum_id' => $findCurriculum->id])->first();
                    if (!$check) {
                        CourseCurriculum::create([
                            'id' => Uuid::uuid4(),
                            'course_id' => $value['id_matkul'],
                            'curriculum_id' => $value['id_kurikulum'],
                            'semester' => $value['semester'],
                            'credit_total' => $value['sks_mata_kuliah'] == null ? 0 : $value['sks_mata_kuliah'],
                            'credit_meeting' => $value['sks_tatap_muka'] == null ? 0 : $value['sks_tatap_muka'],
                            'credit_practicum' => $value['sks_praktek'] == null ? 0 : $value['sks_praktek'],
                            'credit_practice' => $value['sks_praktek_lapangan'] == null ? 0 : $value['sks_praktek_lapangan'],
                            'credit_simulation' => $value['sks_simulasi'] == null ? 0 : $value['sks_simulasi'],
                            'is_mandatory' => $value['apakah_wajib'],
                            'feeder_id' => $value['id_kurikulum'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    } else {
                        $check->update([
                            'course_id' => $value['id_matkul'],
                            'curriculum_id' => $value['id_kurikulum'],
                            'semester' => $value['semester'],
                            'credit_total' => $value['sks_mata_kuliah'] == null ? 0 : $value['sks_mata_kuliah'],
                            'credit_meeting' => $value['sks_tatap_muka'] == null ? 0 : $value['sks_tatap_muka'],
                            'credit_practicum' => $value['sks_praktek'] == null ? 0 : $value['sks_praktek'],
                            'credit_practice' => $value['sks_praktek_lapangan'] == null ? 0 : $value['sks_praktek_lapangan'],
                            'credit_simulation' => $value['sks_simulasi'] == null ? 0 : $value['sks_simulasi'],
                            'is_mandatory' => $value['apakah_wajib'],
                            'feeder_id' => $value['id_kurikulum'],
                            'feeder_status' => 'SUKSES',
                        ]);
                    }
                }
            }
            return $this->successResponse('Berhasil sync matakuliah kurikulum', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    //upload Course
    public function uploadCourse($request)
    {
        try{

            set_time_limit(0);

            if($request->course == null){

                $get = Course::with(['studyProgram'])->where('feeder_id', '=', null);
                if(
                    isset($request->study_program_id) &&
                     $request->study_program_id != null &&
                     $request->study_program_id != "" &&
                     $request->study_program != 'all'
                    ){

                    $get->where('study_program_id', $request->study_program_id);
                    
                }

                if(
                    isset($request->course_type_id) &&
                     $request->course_type_id != null &&
                     $request->course_type_id != "" &&
                     $request->course_type_id != 'all'
                    ){

                    $get->where('course_type_id', $request->course_type_id);

                }
                $gets = $get->get();

                foreach($gets as $course){
                    $listUpload = [
                        "record" => [
                            'kode_mata_kuliah' => $course->code,
                            'nama_mata_kuliah' => $course->name,
                            'id_prodi' => $course->studyProgram->feeder_id,
                            'id_jenis_mata_kuliah' => $course->course_type_id,
                            'id_kelompok_mata_kuliah' => $course->course_group_id,
                            'sks_mata_kuliah' => $course->credit_total,
                            'sks_tatap_muka' => $course->meeting,
                            'sks_praktek' => $course->practicum,
                            'sks_praktek_lapangan' => $course->practice,
                            'sks_simulasi' => $course->simulation,
                            // 'metode_kuliah' => '',
                            'ada_sap' => $course->sap,
                            'ada_silabus' => $course->is_silabus,
                            'ada_bahan_ajar' => $course->is_bahan_ajar,
                            // 'ada_acara_praktek' => '',
                            'ada_diktat' => $course->is_diktat,
                        ]
                    ];

                    $data = new FeederService('InsertMataKuliah', $listUpload);
                    $res = $data->runWS();

                    $id_course_feeder = isset($res['data']['id_matkul']) ? $res['data']['id_matkul'] : null;
    
                    Course::where('id', $course->id)->update([
                        'feeder_id' => $id_course_feeder,
                        'feeder_status' => ($id_course_feeder != null ? 'SUKSES' : 'GAGAL'),
                        'feeder_description' => $res['error_desc']
                    ]);
                }

            }else if($request->course != null){

                for ($numArray=0; $numArray < count($request->course); $numArray++) { 
                    # code...
                    $course = Course::with(['studyProgram'])->where('id', $request->course[$numArray])->where('feeder_id', null)->first();

                    $listUpload = [
                        "record" => [
                            'kode_mata_kuliah' => $course->code,
                            'nama_mata_kuliah' => $course->name,
                            'id_prodi' => $course->studyProgram->feeder_id,
                            'id_jenis_mata_kuliah' => $course->course_type_id,
                            'id_kelompok_mata_kuliah' => $course->course_group_id,
                            'sks_mata_kuliah' => $course->credit_total,
                            'sks_tatap_muka' => $course->meeting,
                            'sks_praktek' => $course->practicum,
                            'sks_praktek_lapangan' => $course->practice,
                            'sks_simulasi' => $course->simulation,
                            // 'metode_kuliah' => '',
                            'ada_sap' => $course->sap,
                            'ada_silabus' => $course->is_silabus,
                            'ada_bahan_ajar' => $course->is_bahan_ajar,
                            // 'ada_acara_praktek' => '',
                            'ada_diktat' => $course->is_diktat,
                        ]
                    ];

                    $data = new FeederService('InsertMataKuliah', $listUpload);
                    $res = $data->runWS();

                    $id_course_feeder = isset($res['data']['id_matkul']) ? $res['data']['id_matkul'] : null;
    
                    Course::where('id', $request->course[$numArray])->update([
                        'feeder_id' => $id_course_feeder,
                        'feeder_status' => ($id_course_feeder != null ? 'SUKSES' : 'GAGAL'),
                        'feeder_description' => $res['error_desc']
                    ]);
                }
                
            }

            return $this->successResponse('Berhasil Upload Mata Kuliah');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //Upload Course Curriculum
    public function uploadCourseCurriculum($request)
    {
        try{

            set_time_limit(0);
            
            if($request->courseCurriculum == NULL && count($request->courseCurriculum) < 1){
                $get = CourseCurriculum::with(['course', 'curriculum'])->where('feeder_id', '=', NULL);

                if($request->sync == 1){
                    $get->where('feeder_status', NULL);
                }

                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $get->whereHas('course', function($q)use($request){
                        $q->where('study_program_id', $request->study_program_id);
                    });
                }
                if(
                    isset($request->academic_period_id) &&
                    $request->academic_period_id != NULL &&
                    $request->academic_period_id != "" &&
                    $request->academic_period_id != "all"
                ){
                    $get->whereHas('curriculum', function($q)use($request){
                        $q->where('academic_period_id', $request->academic_period_id);
                    });
                }

                $gets = $get->get();

                foreach($gets as $i){

                    $list = [
                        "record" => [
                            "id_kurikulum" => $i->curriculum->feeder_id,
                            "id_matkul" => $i->course->feeder_id,
                            "semester" => $i->semester,
                            "sks_mata_kuliah" => $i->credit_total,
                            "sks_tatap_muka" => $i->credit_meeting,
                            "sks_praktek" => $i->credit_practicum,
                            "sks_praktek_lapangan" => $i->credit_practice,
                            "sks_simulasi" => $i->simulation,
                            "apakah_wajib" => $i->is_mandatory
                        ]
                    ];
    
                    $data = new FeederService('InsertMatkulKurikulum', $list);
                    $res = $data->runWS();

                    $id_course_feeder = isset($res['data']['id_matkul']) ? $res['data']['id_matkul'] : null;
    
                    CourseCurriculum::where('id', $i->id)->update([
                        'feeder_id' => $id_course_feeder,
                        'feeder_status' => ($id_course_feeder != null ? 'SUKSES' : 'GAGAL'),
                        'feeder_description' => $res['error_desc']
                    ]);
                }

            }else{
                for ($numArray=0; $numArray < count($request->courseCurriculum); $numArray++) {
                    $get = CourseCurriculum::with(['course', 'curriculum'])->where('id', $request->courseCurriculum[$numArray])->where('feeder_id', '=', NULL)->first();

                    $list = [
                        "record" => [
                            "id_kurikulum" => $get->curriculum->feeder_id,
                            "id_matkul" => $get->course->feeder_id,
                            "semester" => strval($get->semester),
                            "sks_mata_kuliah" => $get->credit_total,
                            "sks_tatap_muka" => $get->credit_meeting,
                            "sks_praktek" => $get->credit_practicum,
                            "sks_praktek_lapangan" => $get->credit_practice,
                            "sks_simulasi" => $get->credit_simulation,
                            "apakah_wajib" => strval($get->is_mandatory),
                        ]
                    ];
                    // return response()->json([$list], 500);
                    
                    $data = new FeederService('InsertMatkulKurikulum', $list);
                    $res = $data->runWS();
                    
                    $id_course_feeder = isset($res['data']['id_kurikulum']) ? $res['data']['id_kurikulum'] : null;
                    
                    $get = CourseCurriculum::where('id', $request->courseCurriculum[$numArray])->update([
                        'feeder_id' => $id_course_feeder,
                        'feeder_status' => ($id_course_feeder != null ? 'SUKSES' : 'GAGAL'),
                        'feeder_description' => $res['error_desc']
                    ]);
                }
            }

            return $this->successResponse('Berhasil Upload Mata Kuliah Kurikulum');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //Update Course
    public function updateCourse($request)
    {
        try{
            set_time_limit(0);
            for ($numArray=0; $numArray < count($request->course); $numArray++) { 
                # code...
                $idCourse = $request->course[$numArray];
                $course = Course::with(['studyProgram'])->where('id', $idCourse)->where('feeder_id', '!=', NULL)->first();

                $listUpdate = [
                    'record' => [
                                'kode_mata_kuliah' => $course->code,
                                'nama_mata_kuliah' => $course->name,
                                'id_prodi' => $course->studyProgram->feeder_id,
                                'id_jenis_mata_kuliah' => $course->course_type_id,
                                'id_kelompok_mata_kuliah' => $course->course_group_id,
                                'sks_mata_kuliah' => $course->credit_total,
                                'sks_tatap_muka' => $course->meeting,
                                'sks_praktek' => $course->practicum,
                                'sks_praktek_lapangan' => $course->practice,
                                'sks_simulasi' => $course->simulation,
                                // 'metode_kuliah' => '',
                                'ada_sap' => $course->sap,
                                'ada_silabus' => $course->is_silabus,
                                'ada_bahan_ajar' => $course->is_bahan_ajar,
                                // 'ada_acara_praktek' => '',
                                'ada_diktat' => $course->is_diktat,
                            ],
                    'key' => [
                                'id_matkul' => $course->feeder_id,
                            ],
                ];

                $data = new FeederService('UpdateMataKuliah', $listUpdate);
                $res = $data->runWS();

                $id_course_feeder = isset($res['data']['id_matkul']) ? 1 : 0;
    
                Course::where('id', $request->course[$numArray])->update([
                    'feeder_id' => isset($res['data']['id_matkul']) ? $res['data']['id_matkul'] : $course->feeder_id,
                    'feeder_status' => ($id_course_feeder != 0 ? 'SUKSES' : 'GAGAL'),
                    'feeder_description' => $res['error_desc']
                ]);
            }

            return $this->successResponse('Berhasil Update Mata Kuliah');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //Update Course Curriculum
    public function updateCourseCurriculum($request){
        try{

            for($numArray=0; $numArray < count($request->courseCurriculum); $numArray++) {
                $get = CourseCurriculum::with(['course', 'curriculum'])->where('id', $request->courseCurriculum[$numArray])->first();
    
                $list = [
                    "record" => [
                        "semester"=>$get->semester,
                        "apakah_wajib"=>$get->is_mandatory
                    ],
                    'key' => [
                        "id_kurikulum" => $get->feeder_id,
                        'id_matkul' => $get->course->feeder_id,
                    ],
                ];
                // return response()->json([$list], 500);
                
                $data = new FeederService('UpdateMatkulKurikulum', $list);
                $res = $data->runWS();

                $id_course_feeder = isset($res['data']['id_kurikulum']) ? 1 : 0;

                CourseCurriculum::where('id', $request->courseCurriculum[$numArray])->update([
                    'feeder_id' => isset($res['data']['id_kurikulum']) ? $res['data']['id_kurikulum'] : $get->feeder_id,
                    'feeder_status' => ($id_course_feeder != 0 ? 'SUKSES' : 'GAGAL'),
                    'feeder_description' => $res['error_desc']
                ]);
            }

            return $this->successResponse("Berhasil Update Mata Kuliah Kurikulum");

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    //delete course curriculum
    public function deleteCourseCurriculum($request){
        try{

            for ($numArray=0; $numArray < count($request->courseCurriculum); $numArray++) {
                $get = CourseCurriculum::with(['course', 'curriculum'])->where('id', $request->courseCurriculum[$numArray])->first();
    
                $list = [
                    'key' => [
                        "id_kurikulum" => $get->feeder_id,
                        'id_matkul' => $get->course->feeder_id,
                    ],
                ];
                
                $data = new FeederService('DeleteMatkulKurikulum', $list);
                $res = $data->runWS();

                $dat = $res['error_code'] == '0' ? 1 : 0;

                CourseCurriculum::where('id', $request->courseCurriculum[$numArray])->update([
                    'feeder_id' => $res['error_code'] == '0' ? null : $get->feeder_id,
                    'feeder_status' => ($dat != 0 ? 'SUKSES' : 'GAGAL'),
                    'feeder_description' => $res['error_desc']
                ]);
            }

            return $this->successResponse('Berhasil Hapus Mata Kuliah Kurikulum');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
