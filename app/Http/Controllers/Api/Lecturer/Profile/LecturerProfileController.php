<?php

namespace App\Http\Controllers\Api\Lecturer\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecturer\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerProfileController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->userable;
        $lecturer2 = Auth::user();

        $data = [
            'lecturer' => $lecturer,
            'user'  => $lecturer2, 
        ];

        return $this->successResponse(null, $data);
    }

    public function update(UpdateProfileRequest $request)
    {
        Auth::user()->userable->update($request->only(['personal_email', 'name', 'campus_email']));

        return $this->successResponse('Berhasil mengupdate profile dosen');
    }
}
