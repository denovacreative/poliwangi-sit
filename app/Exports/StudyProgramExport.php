<?php

namespace App\Exports;

use App\Models\StudyProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudyProgramExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studyProgram');
        return view('exports.studyProgram', [
            'studyProgram' => StudyProgram::all()
        ]);
    }
}
