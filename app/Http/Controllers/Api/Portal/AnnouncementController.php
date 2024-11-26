<?php

namespace App\Http\Controllers\Api\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\AnnouncementRequest;
use App\Models\Announcement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function index()
    {
        return DataTables::of(Announcement::with(['major', 'studyProgram.educationLevel', 'user', 'role'])->orderBy('id', 'desc'))
            ->addColumn('intended_for', function ($data) {
                $query = null;
                if (!empty($data->major)) {
                    $query = 'Jurusan' . ' - ' . $data->major->name;
                } else if (!empty($data->studyProgram)) {
                    $query = 'Prodi' . ' - ' . '(' . $data->studyProgram->educationLevel->code . ') ' . ' - ' . $data->studyProgram->name;
                } else if (!empty($data->user)) {
                    $query = 'User' . ' - ' . $data->user->name;
                } else if (!empty($data->role)) {
                    $query = 'Role' . ' - ' . $data->role->name;
                } else {
                    $query = null;
                }
                return $query;
            })
            ->editColumn('thumbnail', function ($data) {
                return asset('storage/images/announcements/' . $data->thumbnail);
            })
            ->editColumn('attachment', function ($data) {
                return !is_null($data->attachment) ? asset('storage/documents/announcements/' . $data->attachment) : null;
            })
            ->make();
    }

    public function show(Announcement $announcement)
    {
        return $this->successResponse(null, compact('announcement'));
    }

    public function store(AnnouncementRequest $request)
    {
        try {
            if ($request->has('image')) {
                $file = $request->file('image');
                $fileName = randomFileName('announcement', $file);
                $file->move(public_path('storage/images/announcements'), $fileName);
            } else {
                $fileName = 'default.png';
            }
            $request->merge([
                'thumbnail' => $fileName
            ]);

            if ($request->has('document')) {
                $file = $request->file('document');
                $fileName = randomFileName('announcement', $file);
                $file->move(public_path('storage/documents/announcements'), $fileName);
                $request->merge([
                    'attachment' => $fileName
                ]);
            }

            if (!empty($request->type_intended_for)) {
                if ($request->type_intended_for == 'major') {
                    $request->merge([
                        'major_id' => $request->intended_for
                    ]);
                } else if ($request->type_intended_for == 'study_program') {
                    $request->merge([
                        'study_program_id' => $request->intended_for
                    ]);
                } else if ($request->type_intended_for == 'user') {
                    $request->merge([
                        'user_id' => Hashids::decode($request->intended_for)[0]
                    ]);
                } else {
                    $request->merge([
                        'role_id' => Hashids::decode($request->intended_for)[0]
                    ]);
                }
            }

            Announcement::create([
                'title' => $request->title,
                'message' => $request->message,
                'thumbnail' => $request->thumbnail,
                'attachment' => $request->attachment ?? null,
                'is_priority' => $request->is_priority,
                'is_active' => $request->is_active,
                'major_id' => $request->major_id ?? null,
                'study_program_id' => $request->study_program_id ?? null,
                'user_id' => $request->user_id ?? null,
                'role_id' => $request->role_id ?? null
            ]);

            return $this->successResponse('Berhasil membuat data pengumuman');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(Announcement $announcement, AnnouncementRequest $request)
    {
        try {
            if ($request->has('image')) {
                if (file_exists(public_path('storage/images/announcements/' . $announcement->thumbnail)) && $announcement->thumbnail != 'default.png') {
                    File::delete(public_path('storage/images/announcements/' . $announcement->thumbnail));
                }

                $file = $request->file('image');
                $fileName = randomFileName('announcement', $file);
                $file->move(public_path('storage/images/announcements'), $fileName);
            } else {
                if (file_exists(public_path('storage/images/announcements/' . $announcement->thumbnail)) && $announcement->thumbnail != 'default.png') {
                    $fileName = $announcement->thumbnail;
                } else {
                    $fileName = 'default.png';
                }
            }
            $request->merge([
                'thumbnail' => $fileName
            ]);

            if ($request->has('document')) {
                if (file_exists(public_path('storage/documents/announcements/' . $announcement->attachment))) {
                    File::delete(public_path('storage/documents/announcements/' . $announcement->attachment));
                }

                $file = $request->file('document');
                $fileName = randomFileName('announcement', $file);
                $file->move(public_path('storage/documents/announcements'), $fileName);
            } else {
                if (file_exists(public_path('storage/documents/announcements/' . $announcement->attachment))) {
                    $fileName = $announcement->attachment;
                }
            }
            $request->merge([
                'attachment' => $fileName
            ]);

            if (!empty($request->type_intended_for)) {
                if ($request->type_intended_for == 'major') {
                    $request->merge([
                        'major_id' => $request->intended_for
                    ]);
                } else if ($request->type_intended_for == 'study_program') {
                    $request->merge([
                        'study_program_id' => $request->intended_for
                    ]);
                } else if ($request->type_intended_for == 'user') {
                    $request->merge([
                        'user_id' => Hashids::decode($request->intended_for)[0]
                    ]);
                } else {
                    $request->merge([
                        'role_id' => Hashids::decode($request->intended_for)[0]
                    ]);
                }
            }

            $announcement->update([
                'title' => $request->title,
                'message' => $request->message,
                'thumbnail' => $request->thumbnail,
                'attachment' => $request->attachment ?? null,
                'is_priority' => $request->is_priority,
                'is_active' => $request->is_active,
                'major_id' => $request->major_id ?? null,
                'study_program_id' => $request->study_program_id ?? null,
                'user_id' => $request->user_id ?? null,
                'role_id' => $request->role_id ?? null
            ]);

            return $this->successResponse('Berhasil mengupdate data pengumuman');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Announcement $announcement)
    {
        try {
            if (file_exists(public_path('storage/images/announcements/' . $announcement->thumbnail)) and $announcement->thumbnail != 'default.png') {
                File::delete(public_path('storage/images/announcements/' . $announcement->thumbnail));
            }

            if (file_exists(public_path('storage/documents/announcements/' . $announcement->attachment))) {
                File::delete(public_path('storage/documents/announcements/' . $announcement->attachment));
            }

            $announcement->delete();

            return $this->successResponse('Berhasil menghapus data pengumuman');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
