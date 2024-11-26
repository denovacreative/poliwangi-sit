<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeActiveStatus;
use DataTables;

class EmployeeActiveStatusesController extends Controller
{
    
    public function index() 
    {
        return DataTables::of(EmployeeActiveStatus::query())->make();
    }

}
