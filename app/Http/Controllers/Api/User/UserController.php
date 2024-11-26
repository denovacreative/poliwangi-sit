<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserAccess;
use DataTables;
use Exception;
use File;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Mail\MailNotify;
use App\Models\Employee;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function getData()
    {
        return DataTables::of(User::whereHas('roles', function ($q) {
            $q->whereNotIn('name', ['Developer', 'Default']);
        })->whereNotIn('id', [getInfoLogin()->id])->with('roles'))->editColumn('picture', function ($data) {
            return asset('storage/images/users/' . $data->picture);
        })->make(true);
    }

    public function store(UserRequest $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = 'Users_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/users'), $fileName);
            } else {
                $fileName = 'default.png';
            }
            $request->merge([
                'picture' => $fileName,
                'password' => Hash::make($request->password),
            ]);

            User::create($request->only(['name', 'username', 'email', 'password', 'phone_number', 'is_active', 'picture']))->assignRole($request->role);

            return $this->successResponse('Data berhasil ditambahkan');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(User $user)
    {
        try {
            $user->roles = $user->roles;
            return response()->json($user);
        } catch (Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(User $user, UserRequest $request)
    {
        try {
            if ($request->hasFile('file')) {
                if (file_exists(public_path('storage/images/users/' . $user->picture))) {
                    if ($user->picture != 'default.png') {
                        File::delete(public_path('storage/images/users/' . $user->picture));
                    }
                }

                $file = $request->file('file');
                $fileName = 'Users_' . time() . rand(0, 99999999999) . '_' . rand(0, 99999999999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/images/users'), $fileName);
            } else {
                $fileName = 'default.png';
            }
            $request->merge([
                'picture' => $fileName,
            ]);
            $user->update($request->only(['name', 'username', 'email', 'phone_number', 'is_active', 'picture']));
            $user->syncRoles($request->role);

            return response()->json([
                'message' => 'Data berhasil diubah',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if (file_exists(public_path('storage/images/users/' . $user->picture))) {
                if ($user->picture != 'default.png') {
                    File::delete(public_path('storage/images/users/' . $user->picture));
                }
            }
            $user->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRoles()
    {
        try {
            return response()->json([
                'message' => 'success',
                'data' => Role::whereNotIn('name', ['Developer', 'Default'])->get()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'trace' => $e->getTrace(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAccess(User $user)
    {
        $data = [];
        $studyPrograms = StudyProgram::with('educationLevel')->get();
        $userAccesses = UserAccess::where('user_id', $user->id)->get();
        foreach ($studyPrograms as $i => $sp) {
            $data[] = [
                'id' => $sp->id,
                'studyProgram' => $sp->educationLevel->code . ' - ' . $sp->name,
                'isSelect' => false,
            ];
            foreach ($userAccesses as $j => $ua) {
                if ($sp->id == $ua->study_program_id) {
                    $data[$i]['isSelect'] = true;
                }
            }
        }
        return $this->successResponse(null, compact('data'));
    }

    public function storeAccess(User $user, Request $request)
    {
        try {
            $roleAcess = ['prodi', 'jurusan'];
            if (!in_array($user->roles[0]->group, $roleAcess)) {
                return $this->errorResponse(500, 'User ini tidak memerlukan akses prodi!');
            }
            if ($request->has('study_program')) {
                DB::beginTransaction();
                UserAccess::where('user_id', $user->id)->delete();
                $insert = [];
                foreach ($request->study_program as $item) {
                    $insert[] = [
                        'user_id' => $user->id,
                        'study_program_id' => $item
                    ];
                }
                UserAccess::insert($insert);
                DB::commit();
            } else {
                UserAccess::where('user_id', $user->id)->delete();
            }
            return $this->successResponse('Berhasil mengubah akses user');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updatePassword(Request $request, $id){
        try{
            if ($request->old_password == null) {
                return $this->errorResponse(400,'harap masukan password lama!', []);
            }
            if ($request->old_password != null) {
                $userPassword = User::where('id',Hashids::decode($id))->get();
                if (Hash::check($request->old_password, $userPassword[0]->password)) {
                    if ($request->new_password  != $request->confirm_password) {
                        return $this->errorResponse(400,'password tidak sama!', []);
                    }
                    $user = User::where('id',Hashids::decode($id));
                    $user->update(['password' =>  Hash::make($request->new_password)]);
                    return $this->successResponse('berhasil memperbarui password!');
                }else{
                    return $this->errorResponse(400,'password tidak sama!', []);
                }
                }
                if ($request->new_password  != $request->confirm_password) {
                    return $this->errorResponse(400,'password tidak sama!', []);
                }
                $user = User::where('id',Hashids::decode($id));
                $user->update(['password' =>  Hash::make($request->new_password)]);
                return $this->successResponse('berhasil memperbarui password!');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function validationSendEmail($id)
    {
        try{
            $user = Employee::where('id', $id)->first();
            if(!isset($user->id)){
                $user = Student::where('id', $id)->first();
                $email = $user->email;
            }else{
                $email = $user->personal_email;
            }

            $data = [
                'subject' => 'Validasi Email',
                'title' => 'Pemberitahuan Untuk Validasi Email',
                'body' => "Untuk melakukan validasi email anda, silahkan klik link berikut ini : <a href='".route('verification-email-users', $user->id)."'>Link Validasi</a>",
                'email' => $email,
            ];
            Mail::to($email)->send(new MailNotify($data));

            return $this->successResponse('Berhasil kirim email');

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }

    public function verificationEmail($id)
    {
        try{
            
            $user = Employee::where('id', $id)->first();
            if(!isset($user->id)){
                $user = Student::where('id', $id)->first();
            }

            $date = Date('Y-m-d H:i:s');
            User::where('userable_id', $user->id)->update([
                'email_verified_at' => $date,
            ]);
            
            echo "<center><h5>Email anda telah terverifikasi</h5></center>";

        }catch(Exception $e){
            return $this->exceptionResponse($e);
        }
    }
}
