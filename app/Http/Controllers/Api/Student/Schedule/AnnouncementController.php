<?php

namespace App\Http\Controllers\Api\Student\Schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $last_page = $request->last_page;
            $limit = 10;
            $begin_page = ($last_page > 1) ? ($last_page * $limit) - $limit : 0;
            
            $result = Announcement::whereIsActive(true)->orWhere('user_id', getInfoLogin()->id)->orWhere('role_id', getInfoLogin()->roles[0]->id)->orderBy('is_priority', 'desc')->orderBy('created_at', 'desc')->skip($begin_page)->take($limit)->get();
            $result = $result->map(function($data) {
                $data->message = Str::limit($data->message, 100, '...');
                $data->date_custom = date('M d, Y', strtotime($data->created_at));
                $data->time_custom = date('H:i', strtotime($data->created_at));
    
                return $data;
            });
            $total_page = ceil(Announcement::all()->count() / $limit);
            $result_total = Announcement::all()->count();
    
            return $this->successResponse(null, compact('last_page', 'total_page', 'result', 'result_total'));
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function detail(Announcement $announcement)
    {
        try {
            $new_announcement = Announcement::whereIsActive(true)->orWhere('user_id', getInfoLogin()->id)->orWhere('role_id', getInfoLogin()->roles[0]->id)->orderBy('is_priority', 'desc')->orderBy('created_at', 'desc')->limit(3)->get();
            $new_announcement = $new_announcement->map(function($data) {
                $data->message = Str::limit($data->message, 100, '...');
                $data->date_custom = date('M d, Y', strtotime($data->created_at));
                $data->time_custom = date('H:i', strtotime($data->created_at));
    
                return $data;
            });
            $announcement->date_custom = date('M d, Y', strtotime($announcement->created_at));
            $announcement->time_custom = date('H:i', strtotime($announcement->created_at));
    
            return $this->successResponse(null, compact('announcement', 'new_announcement'));
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

}
