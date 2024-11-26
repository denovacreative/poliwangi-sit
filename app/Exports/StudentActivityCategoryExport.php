<?php

namespace App\Exports;

use App\Models\StudentActivityCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentActivityCategoryExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studentActivityCategory');
        return view('exports.studentActivityCategory', [
            'activitiesCategory' => StudentActivityCategory::all()
        ]);
    }
}
