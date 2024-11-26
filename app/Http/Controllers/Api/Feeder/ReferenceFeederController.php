<?php

namespace App\Http\Controllers\Api\Feeder;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\AchievementLevel;
use App\Models\AchievementType;
use App\Models\ActivityCategory;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\Curriculum;
use App\Models\Disability;
use App\Models\EducationLevel;
use App\Models\EmployeeActiveStatus;
use App\Models\EmployeeStatus;
use App\Models\EvaluationType;
use App\Models\Finance;
use App\Models\Income;
use App\Models\Profession;
use App\Models\RegistrationPath;
use App\Models\RegistrationType;
use App\Models\Religion;
use App\Models\ScoreScale;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\StudyProgram;
use App\Models\SubstanceType;
use App\Models\TypeOfStay;
use App\Models\UniversityProfile;
use App\Services\FeederService;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ReferenceFeederController extends Controller
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

    // Religion
    public function getReligion($request)
    {
        try {
            $data = new FeederService('GetAgama');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Religion::where('id', $value['id_agama'])->first();
                if (!$check) {
                    Religion::create([
                        'id' => $value['id_agama'],
                        'name' => $value['nama_agama'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync agama', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Registration Path
    public function getRegistrationPath($request)
    {
        try {
            $data = new FeederService('GetJalurMasuk');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = RegistrationPath::where('id', $value['id_jalur_masuk'])->first();
                if (!$check) {
                    RegistrationPath::create([
                        'id' => $value['id_jalur_masuk'],
                        'name' => $value['nama_jalur_masuk'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jalur masuk', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Registration Type
    public function getRegistrationType($request)
    {
        try {
            $data = new FeederService('GetJenisPendaftaran');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = RegistrationType::where('id', $value['id_jenis_daftar'])->first();
                if (!$check) {
                    RegistrationType::create([
                        'id' => $value['id_jenis_daftar'],
                        'name' => $value['nama_jenis_daftar'],
                        'is_school_register' => $value['untuk_daftar_sekolah']
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jenis pendaftaran', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Evaluation Type
    public function getEvaluationType($request)
    {
        try {
            $data = new FeederService('GetJenisEvaluasi');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = EvaluationType::where('id', $value['id_jenis_evaluasi'])->first();
                if (!$check) {
                    EvaluationType::create([
                        'id' => $value['id_jenis_evaluasi'],
                        'name' => $value['nama_jenis_evaluasi'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jenis evaluasi', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // TypeOfStay
    public function getTypeOfStay($request)
    {
        try {
            $data = new FeederService('GetJenisTinggal');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = TypeOfStay::where('id', $value['id_jenis_tinggal'])->first();
                if (!$check) {
                    TypeOfStay::create([
                        'id' => $value['id_jenis_tinggal'],
                        'name' => $value['nama_jenis_tinggal'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jenis tinggal', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Education Level
    public function getEducationLevel($request)
    {
        try {
            $data = new FeederService('GetJenjangPendidikan');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = EducationLevel::where('id', $value['id_jenjang_didik'])->first();
                if (!$check) {
                    EducationLevel::create([
                        'id' => $value['id_jenjang_didik'],
                        'name' => $value['nama_jenjang_didik'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jenjang pendidikan', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Disability
    public function getDisability($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetKebutuhanKhusus');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Disability::where('id', $value['id_kebutuhan_khusus'])->first();
                if (!$check) {
                    Disability::create([
                        'id' => $value['id_kebutuhan_khusus'],
                        'name' => $value['nama_kebutuhan_khusus'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync kebutuhan khusus', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Academic Year
    public function getAcademicYear($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetTahunAjaran');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = AcademicYear::where('id', $value['id_tahun_ajaran'])->first();
                if (!$check) {
                    AcademicYear::create([
                        'id' => $value['id_tahun_ajaran'],
                        'name' => $value['nama_tahun_ajaran'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync tahun ajaran', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Student Status
    public function getStudentStatus($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetStatusMahasiswa');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = StudentStatus::where('id', $value['id_status_mahasiswa'])->first();
                if (!$check) {
                    StudentStatus::create([
                        'id' => $value['id_status_mahasiswa'],
                        'name' => $value['nama_status_mahasiswa'],
                        'is_college' => true,
                        'is_default' => true
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync status mahasiswa', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Student Graduation Status
    public function getStudentGraduationStatus($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetJenisKeluar');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = StudentStatus::where('id', $value['id_jenis_keluar'])->first();
                if (!$check) {
                    StudentStatus::create([
                        'id' => $value['id_jenis_keluar'],
                        'name' => $value['jenis_keluar'],
                        'is_college' => false,
                        'is_default' => true
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync status keluar mahasiswa', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Employee Status
    public function getEmployeeStatus($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetStatusKepegawaian');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = EmployeeStatus::where('id', $value['id_status_pegawai'])->first();
                if (!$check) {
                    EmployeeStatus::create([
                        'id' => $value['id_status_pegawai'],
                        'name' => $value['nama_status_pegawai'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync status pegawai', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Employee Active Status
    public function getEmployeeActiveStatus($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetStatusKeaktifanPegawai');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = EmployeeActiveStatus::where('id', $value['id_status_aktif'])->first();
                if (!$check) {
                    EmployeeActiveStatus::create([
                        'id' => $value['id_status_aktif'],
                        'name' => $value['nama_status_aktif'],
                        'is_exit' => false
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync status keaktifan pegawai', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Semester
    public function getSemester($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetSemester');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = AcademicPeriod::where('id', $value['id_semester'])->first();
                if (!$check) {
                    AcademicPeriod::create([
                        'id' => $value['id_semester'],
                        'academic_year_id' => $value['id_tahun_ajaran'],
                        'semester' => $value['semester'],
                        'name' => $value['nama_semester'],
                        'college_start_date' => $value['tanggal_mulai'],
                        'college_end_date' => $value['tanggal_selesai'],
                        'is_active' => $value['a_periode_aktif'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync semester', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Income
    public function getIncome($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetPenghasilan');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Income::where('id', $value['id_penghasilan'])->first();
                if (!$check) {
                    Income::create([
                        'id' => $value['id_penghasilan'],
                        'name' => $value['nama_penghasilan'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync penghasilan', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Profession
    public function getProfession($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetPekerjaan');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Profession::where('id', $value['id_pekerjaan'])->first();
                if (!$check) {
                    Profession::create([
                        'id' => $value['id_pekerjaan'],
                        'name' => $value['nama_pekerjaan'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync pekerjaan', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Study Program
    public function getStudyProgram($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetProdi');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = StudyProgram::where('feeder_id', $value['id_prodi'])->first();
                if (!$check) {
                    StudyProgram::create([
                        'id' => $value['id_prodi'],
                        'major_id' => null,
                        'education_level_id' => $value['id_jenjang_pendidikan'],
                        'code' => $value['kode_program_studi'],
                        'name' => $value['nama_program_studi'],
                        'name_en' => null,
                        'alias' => null,
                        'is_active' => true,
                        'status' => $value['status'],
                        'feeder_id' => $value['id_prodi'],
                        'feeder_status' => 'SUKSES'
                    ]);
                } else {
                    $check->update([
                        'major_id' => null,
                        'education_level_id' => $value['id_jenjang_pendidikan'],
                        'code' => $value['kode_program_studi'],
                        'name' => $value['nama_program_studi'],
                        'name_en' => null,
                        'alias' => null,
                        'is_active' => true,
                        'status' => $value['status'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync program studi', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // University Profile
    public function getUniversityProfile($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetProfilPT');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = UniversityProfile::where('id', $value['id_perguruan_tinggi'])->first();
                if (!$check) {
                    UniversityProfile::create([
                        'id' => $value['id_perguruan_tinggi'],
                        'code' => $value['kode_perguruan_tinggi'],
                        'name' => $value['nama_perguruan_tinggi'],
                        'name_en' => 'Banyuwangi State Polytechnic',
                        'alias' => 'Poliwangi',
                        'phone_number' => $value['telepon'],
                        'faximile' => $value['faximile'],
                        'email' => $value['email'],
                        'website' => $value['website'],
                        'street' => $value['jalan'],
                        'neighbourhood' => null,
                        'hamlet' => null,
                        'village_lev_1' => $value['kelurahan'],
                        'village_lev_2' => $value['dusun'],
                        'postal_code' => $value['kode_pos'],
                        'address' => null,
                        'latitude' => null,
                        'longitude' => null,
                        'region_id' => $value['id_wilayah'],
                        'ownership_status' => $value['nama_status_milik'],
                        'status' => $value['status_perguruan_tinggi'],
                        'bank' => $value['bank'],
                        'bank_account_number' => $value['nomor_rekening'],
                        'branch_unit' => $value['unit_cabang'],
                        'land_area_owned' => $value['luas_tanah_milik'],
                        'land_area_not_owned' => $value['luas_tanah_bukan_milik'],
                        'is_mbs' => $value['mbs'],
                        'acreditation' => 'none',
                        'acreditation_number' => null,
                        'acreditation_date' => null,
                        'establishment_number' => $value['sk_pendirian'],
                        'establishment_date' => $value['tanggal_sk_pendirian'],
                        'operating_license_number' => $value['sk_izin_operasional'],
                        'operating_license_date' => $value['tanggal_izin_operasional'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync profil PT', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    // Profession
    public function getActivityCategory($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetKategoriKegiatan');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = ActivityCategory::where('id', $value['id_kategori_kegiatan'])->first();
                if (!$check) {
                    ActivityCategory::create([
                        'id' => $value['id_kategori_kegiatan'],
                        'name' => $value['nama_kategori_kegiatan'],
                        'is_default' => true,
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync kategori kegiatan', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Substance Type
    public function getSubstanceType($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetJenisSubstansi');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = SubstanceType::where('id', $value['id_jenis_substansi'])->first();
                if (!$check) {
                    SubstanceType::create([
                        'id' => $value['id_jenis_substansi'],
                        'name' => $value['nama_jenis_substansi'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jenis substansi', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Finance
    public function getFinance($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetPembiayaan');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = Finance::where('id', $value['id_pembiayaan'])->first();
                if (!$check) {
                    Finance::create([
                        'id' => $value['id_pembiayaan'],
                        'name' => $value['nama_pembiayaan'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync pembiayaan', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Achievement Type
    public function getAchievementType($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetJenisPrestasi');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = AchievementType::where('id', $value['id_jenis_prestasi'])->first();
                if (!$check) {
                    AchievementType::create([
                        'id' => $value['id_jenis_prestasi'],
                        'name' => $value['nama_jenis_prestasi'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync jenis prestasi', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
    // Achievement Level
    public function getAchievementLevel($request)
    {
        try {
            set_time_limit(0);
            $data = new FeederService('GetTingkatPrestasi');
            $res = $data->runWS();
            foreach ($res['data'] as $key => $value) {
                $check = AchievementLevel::where('id', $value['id_tingkat_prestasi'])->first();
                if (!$check) {
                    AchievementLevel::create([
                        'id' => $value['id_tingkat_prestasi'],
                        'name' => $value['nama_tingkat_prestasi'],
                    ]);
                }
            }
            return $this->successResponse('Berhasil sync tingkat prestasi', $res);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
