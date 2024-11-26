<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studyProgram');
        return view('exports.studentExport', [
            'students' => Student::limit(5)->get()
        ]);
    }
}
