<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ThesisExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        // Lembaran pertama
        $sheets[] = new ThesesExport();

        // Lembaran kedua
        $sheets[] = new AcademicPeriodExport();



        return $sheets;
    }
}
