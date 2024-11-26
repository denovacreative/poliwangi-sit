<?php

namespace App\Http\Controllers\Api\Lecture\CollegeClass;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollegeClass;
use App\Models\CollegeContract;
use Exception;

class CollegeContractController extends Controller
{

    public function index(CollegeClass $collegeClass)
    {
        try {
            $collegeContract = $collegeClass->collegeContract;
            return $this->successResponse(null, compact('collegeContract', 'collegeClass'));
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function createOrUpdate(CollegeClass $collegeClass, Request $request)
    {
        $request->validate([
            'content' => 'required',
            'file' => 'required|mimes:pdf,docx,doc'
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'College_Contracts_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/documents/college-contracts'), $fileName);
            }

            $request->merge(['attachment' => $fileName, 'college_class_id' => $collegeClass->id]);
            if (is_null($collegeClass->collegeContract)) {
                CollegeContract::create($request->only(['college_class_id', 'content', 'attachment']));
            } else {
                if (file_exists(public_path('storage/documents/college-contracts/' . $collegeClass->collegeContract->attachment))) {
                    File::delete(public_path('storage/documents/college-contracts/' . $collegeClass->collegeContract->attachment));
                }

                CollegeContract::whereCollegeClassId($collegeClass->id)->update($request->only(['content', 'attachment']));
            }

            return $this->successResponse('Berhasil memperbarui data');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
