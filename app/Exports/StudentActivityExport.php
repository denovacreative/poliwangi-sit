<?php

namespace App\Exports;

use App\Models\StudentActivity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentActivityExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studentActivity');
        return view('exports.studentActivity', [
            'activities' => StudentActivity::with(['studyProgram'])->limit(5)->get()
        ]);
    }
}
