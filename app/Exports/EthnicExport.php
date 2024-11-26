<?php

namespace App\Exports;

use App\Models\Ethnic;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EthnicExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studyProgram');
        return view('exports.ethnics', [
            'ethnics' => Ethnic::all()
        ]);
    }
}
