<?php

namespace App\Exports;

use App\Models\ClassGroup;
use App\Models\Religion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClassGroupExport implements FromView
{
    public function view(): View
    {
        // return view('exports.studyProgram');
        return view('exports.classGroup', [
            'class' => ClassGroup::all()
        ]);
    }
}
