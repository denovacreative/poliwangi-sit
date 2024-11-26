<?php

namespace App\Exports;

use App\Models\Thesis;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ThesesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.thesesExport', [
            'thesis' => Thesis::limit(5)->get()
        ]);
    }
}
