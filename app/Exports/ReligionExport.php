<?php

namespace App\Exports;

use App\Models\Ethnic;
use App\Models\Religion;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReligionExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studyProgram');
        return view('exports.religion', [
            'religions' => Religion::all()
        ]);
    }
}
