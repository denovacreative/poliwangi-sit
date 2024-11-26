<?php

namespace App\Imports;

use App\Models\StudentActivity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;
use Exception;
use Illuminate\Support\Facades\DB;

class StudentActivityImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        
        try {
            foreach ($rows as $row) {
                DB::beginTransaction();
                
                $studentActivity = StudentActivity::create([
                    'id' => isset($row['id']) ? $row['id'] : Uuid::uuid4(),
                    'name' => $row['name'],
                    'group' => $row['group'] ?? null,
                    'location' => $row['location'] ?? null,
                    'start_date' => isset($row['start_date']) ? Carbon::createFromFormat('d/m/Y', $row['start_date'])->format('Y-m-d') : null,
                    'end_date' => isset($row['end_date']) ? Carbon::createFromFormat('d/m/Y', $row['end_date'])->toDateString() : null,
                    'type' => $row["type"] ?? 0,
                    'description' => $row['description'] ?? null,
                    'decree_number' => $row['decree_number'] ?? null,
                    'decree_date' => isset($row['decree_date']) ? Carbon::createFromFormat('d/m/Y', $row['decree_date'])->format('Y-m-d') : null,
                    'is_mbkm' => $row['is_mbkm'] ?? false,
                    'study_program_id' => $row['study_program_id'],
                    'academic_period_id' => $row['academic_period_id'],
                    'student_activity_category_id' => $row['student_activity_category_id'],
                ]);

                if (!$studentActivity) {
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
