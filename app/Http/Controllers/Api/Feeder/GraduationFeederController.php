<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\Graduation;
use App\Models\GraduationPredicate;
use App\Models\JudicialParticipant;
use App\Models\Student;
use App\Models\StudentCollegeActivity;
use App\Models\Thesis;
use App\Services\FeederService;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class GraduationFeederController extends Controller
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

    public function getGraduation($request){
        try{

            set_time_limit(0);
            $opt = [
                "limit" => 1,
                "offset" => $request->offset,
                "filter" => $request->filter
            ];
            $data = new FeederService('GetListMahasiswaLulusDO', $opt);
            $res = $data->runWS();

            

            foreach($res['data'] as $key => $value){

                if($value['id_jns_keluar'] == 1){
                    $student = Student::find($value['id_mahasiswa']);
                    // $judicium = JudicialParticipant::where('student_id', $student->id)->first();
                    $lastAkm = StudentCollegeActivity::where('academic_period_id', $value['id_periode_keluar'])->where('student_id', $student->id)->first();
                    if ($lastAkm) {
                        // if ($judicium) {
                            Graduation::create([
                                'id' => Uuid::uuid4(),
                                'student_id' => $student->id,
                                'student_status_id' => '1',
                                'graduation_date' => $value['tgl_keluar'],
                                'year' => date('Y', strtotime($value['tgl_keluar'])),
                                'description' => $value['keterangan'],
                                'certificate_number' => $value['no_seri_ijazah'],
                                'academic_period_id' => $value['id_periode_keluar'],
                                'study_program_id' => $value['id_prodi'],
                                'name' => $student->name,
                                'graduation_predicate_id' => GraduationPredicate::where('min_score', '<', $lastAkm->grade)->where('max_score', '>', $lastAkm->grade)->where('academic_year_id', getActiveAcademicPeriod(true)->academicYear->id)->first()->id,
                                'judiciary_number' => $value['sk_yudisium'],
                                'grade' => $lastAkm->grade,
                                'feeder_status' => 'SUKSES',
                            ]);
                        // }
                    }
                }else{
                    $student = Student::find($value['id_mahasiswa']);
                    $lastAkm = StudentCollegeActivity::where('academic_period_id', $value['id_periode_keluar'])->where('student_id', $student->id)->first();
                    Graduation::create([
                        'id' => Uuid::uuid4(),
                        'academic_period_id' => $value['id_periode_keluar'],
                        'student_id' => $student->id,
                        'student_status_id' => $value['id_jns_keluar'],
                        'study_program_id' => $student->study_program_id,
                        'graduation_predicate_id' => NULL,
                        'name' => $student->name,
                        'graduation_date' => $value['tgl_keluar'],
                        'judiciary_number' => $value['sk_yudisium'],
                        'judiciary_date' => $value['tgl_sk_yudisium'],
                        'grade' => $lastAkm->grade,
                        'certificate_number' => $value['no_seri_ijazah'],
                        'year' => date('Y', strtotime($value['tgl_keluar'])),
                        'description' => $value['keterangan'],
                        'feeder_status' => 'SUKSES',
                    ]);
                }


            }

            return $this->successResponse('data berhasil di download');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function uploadGraduation($request)
    {
        try{
            if(!isset($request->graduation)){
                $data = Graduation::with(['student', 'studentStatus', 'studyProgram', 'academicPeriod'])->whereNot('feeder_status', 'SUKSES');

                if(
                    isset($request->study_program_id) &&
                    $request->study_program_id != NULL &&
                    $request->study_program_id != "" &&
                    $request->study_program_id != "all"
                ){
                    $data->where('study_program_id', $request->study_program_id);
                }

                if(
                    isset($request->year) &&
                    $request->year != NULL &&
                    $request->year != "" &&
                    $request->year != "all"
                ){
                    $data->where('year', $request->year);
    
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
                    if($g->student_status_id == 1){
                        $thesis = Thesis::where('student_id', $g->student_id)->first();
                        $list = [
                            "record" => [
                                'id_registrasi_mahasiswa' => $g->student->reg_id,
                                'id_jenis_keluar' => $g->student_status_id,
                                'tanggal_keluar' => $g->graduation_date,
                                'id_periode_keluar' => $g->academic_period_id,
                                'keterangan' => $g->description,
                                'nomor_sk_yudisium' => $g->judiciary_number,
                                'tanggal_sk_yudisium' => $g->judiciary_date,
                                'ipk' => $g->grade,
                                'nomor_ijazah' => $g->certificate_number,
                                'jalur_skripsi' => 1,
                                'judul_skripsi' => $thesis->title,
                                'bulan_awal_bimbingan' => null,
                                'bulan_akhir_bimbingan' => null,
                            ],
                        ];
                    }else{
                        $list = [
                            "record" => [
                                "id_registrasi_mahasiswa" => $g->student->reg_id,
                                "id_jenis_keluar" => $g->student_status_id,
                                "tanggal_keluar" => $g->graduation_date,
                                "id_periode_keluar" => $g->academic_period_id,
                                "nomor_sk_yudisium" => $g->judiciary_number,
                                "tanggal_sk_yudisium" => $g->judiciary_date,
                                'keterangan' => $g->description,
                                'ipk' => null,
                                'nomor_ijazah' => null,
                                'jalur_skripsi' => null,
                                'judul_skripsi' => null,
                                'bulan_awal_bimbingan' => null,
                                'bulan_akhir_bimbingan' => null,
                            ],
                        ];
                    }
                    $data = new FeederService('InsertMahasiswaLulusDO', $list);
                    $res = $data->runWS();

                    $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                    Graduation::where('id', $g->id)->update([
                        'feeder_status' => $val,
                        'feeder_description' => $res['error_desc'],
                    ]);
                }
            }else{
                for ($numArray=0; $numArray < count($request->graduation); $numArray++) {
                    $g = Graduation::with(['student', 'studentStatus', 'studyProgram', 'academicPeriod'])->where('id', $request->graduation[$numArray])->first();
                    $thesis = Thesis::where('student_id', $g->student_id)->first();

                    if($g->student_status_id == 1){
                        $list = [
                            "record" => [
                                'id_registrasi_mahasiswa' => $g->student->reg_id,
                                'id_jenis_keluar' => $g->student_status_id,
                                'tanggal_keluar' => $g->graduation_date,
                                'id_periode_keluar' => $g->academic_period_id,
                                'keterangan' => $g->description,
                                'nomor_sk_yudisium' => $g->judiciary_number,
                                'tanggal_sk_yudisium' => $g->judiciary_date,
                                'ipk' => $g->grade,
                                'nomor_ijazah' => $g->certificate_number,
                                'jalur_skripsi' => 1,
                                'judul_skripsi' => $thesis->title,
                                'bulan_awal_bimbingan' => null,
                                'bulan_akhir_bimbingan' => null,
                            ],
                        ];
                    }else{
                        $list = [
                            "record" => [
                                "id_registrasi_mahasiswa" => $g->student->reg_id,
                                "id_jenis_keluar" => $g->student_status_id,
                                "tanggal_keluar" => $g->graduation_date,
                                "id_periode_keluar" => $g->academic_period_id,
                                "nomor_sk_yudisium" => $g->judiciary_number,
                                "tanggal_sk_yudisium" => $g->judiciary_date,
                                'keterangan' => $g->description,
                                'ipk' => null,
                                'nomor_ijazah' => null,
                                'jalur_skripsi' => null,
                                'judul_skripsi' => null,
                                'bulan_awal_bimbingan' => null,
                                'bulan_akhir_bimbingan' => null,
                            ],
                        ];
                    }

                    $data = new FeederService('InsertMahasiswaLulusDO', $list);
                    $res = $data->runWS();

                    $val = $res['error_code'] == '0' ? "SUKSES" : 'GAGAL';

                    Graduation::where('id', $g->id)->update([
                        'feeder_status' => $val,
                        'feeder_description' => $res['error_desc'],
                    ]);
                }
            }

            return $this->successResponse('Berhasil upload feeder Kelulusan/DO');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}