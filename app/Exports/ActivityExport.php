<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ActivityExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        // Lembaran pertama
        $sheets[] = new StudentActivityExport();
        $sheets[] = new StudyProgramExport();
        
        // Lembaran kedua
        $sheets[] = new AcademicPeriodExport();

        $sheets[] = new StudentActivityCategoryExport();

        return $sheets;
    }
}

