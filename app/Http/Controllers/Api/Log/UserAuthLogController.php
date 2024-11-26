<?php

namespace App\Http\Controllers\Api\Log;

use App\Http\Controllers\Controller;
use App\Models\UserAuthLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class UserAuthLogController extends Controller
{
    public function index()
    {
        return DataTables::of(UserAuthLog::query())
            ->editColumn('signin_at', function($data) {
                return Carbon::parse($data->signin_at)->format('d-M-Y H:i:s');
            })
            ->make();
    }
}
