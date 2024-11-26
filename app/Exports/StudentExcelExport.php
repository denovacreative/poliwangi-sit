<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentExcelExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new StudentExport();
        $sheets[] = new StudyProgramExport();
        $sheets[] = new ClassGroupExport();
        $sheets[] = new EthnicExport();
        $sheets[] = new ReligionExport();

        return $sheets;
    }
}
