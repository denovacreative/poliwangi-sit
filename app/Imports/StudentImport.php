<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\StudentActivity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;
use Exception;
use GuzzleHttp\RetryMiddleware;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class StudentImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {

        try {
            DB::beginTransaction();
            foreach ($rows as $row) {

               $student = Student::create( [
                'id' => $row['id'] ?? Uuid::uuid4(),
                'nim' => $row['nim'],
                'class_group_id' => $row['class_group_id'],
                'name' => $row['name_student'],
                'study_program_id' => $row['study_program_id'],
                'academic_period_id' => $row['academic_period_id'],
                'gender' => $row['gender'],
                'birthplace' => $row['birthplace'],
                'birthdate' => isset($row['birthdate']) ? date('Y-m-d', strtotime($row['birthdate'])) : null,
                'religion_id' => isset($row['religion']) ? $row['religion'] : null,
                'ethnic_id' => isset($row['ethnic_id']) ? $row['ethnic_id'] : null,
                'kk' => $row['no_kk'],
                'nik' => $row['number_id'],
                'email' => $row['personal_email'],
                'address' => $row['address'],
            ]);

                if (!$student) {
                    throw new Exception;
                }
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
