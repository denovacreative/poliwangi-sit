<?php

namespace App\Imports;

use App\Models\Thesis;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpParser\Node\Stmt\Return_;
use Ramsey\Uuid\Uuid;

class ThesisImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                DB::beginTransaction();

                $thesis = Thesis::create([
                    'id' =>  Uuid::uuid4(),
                    'academic_period_id' => isset( $row['academic_period_id']) ?  $row['academic_period_id'] : null,
                    'student_id' => isset($row['student_id']) ? $row['student_id'] : null,
                    'filing_date' => isset($row['filing_date']) ?   date('Y-m-d', strtotime($row['filing_date'])) : null,
                    'start_date' => isset($row['start_date']) ? date('Y-m-d', strtotime($row['start_date'])): null,
                    'finish_date' => isset( $row['finish_date']) ? date('Y-m-d', strtotime($row['finish_date'])) : null,
                    'topic' => $row['topic'],
                    'topic_en' => $row['topic_en'],
                    'title' => $row['title'],
                    'title_en' => $row['title_en'],
                    'abstract' => $row['abstract'],
                    'decree_number' => $row['decree_number'],
                    'decree_date' => isset( $row['decree_date']) ? date('Y-m-d', strtotime($row['finish_date'])) : null,
                    'thesis_type' => $row['thesis_type'],
                    'is_active' => $row['is_active'],
                    'is_acc' => $row['is_acc'],
                ]);

                if (!$thesis) {
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
