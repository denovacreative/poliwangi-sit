<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ContactPersonRequest;
use App\Models\ContactPerson;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class ContactPersonController extends Controller
{
    public function index()
    {
        return DataTables::of(ContactPerson::with(['religion', 'agency']))->make();
    }

    public function store(ContactPersonRequest $request)
    {
        try {
            ContactPerson::create([
                'name' => $request->name,
                'front_title' => $request->front_title,
                'back_title' => $request->back_title,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'agency_id' => $request->agency,
                'religion_id' => Hashids::decode($request->religion)[0],
            ]);
            return $this->successResponse('Berhasil membuat data kontak person baru');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(ContactPerson $contactPerson)
    {
        $contactPerson->religion = $contactPerson->religion;
        return $this->successResponse(null, compact('contactPerson'));
    }

    public function destroy(ContactPerson $contactPerson)
    {
        try {
            $contactPerson->delete();
            return $this->successResponse('Berhasil menghapus data kontak person');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update(ContactPerson $contactPerson, ContactPersonRequest $request)
    {
        try {
            $contactPerson->update([
                'name' => $request->name,
                'front_title' => $request->front_title,
                'back_title' => $request->back_title,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'agency_id' => $request->agency,
                'religion_id' => Hashids::decode($request->religion)[0],
            ]);
            return $this->successResponse('Berhasil mengupdate data kontak person');
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
