<?php

namespace App\Exports;

use App\Models\AcademicPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AcademicPeriodExport implements FromView
{
    public function view(): View
    {
        // return view('exports.academicPeriod');
        return view('exports.academicPeriod', [
            'academicPeriod' => AcademicPeriod::where('is_active', true)->get()
        ]);
    }
}
