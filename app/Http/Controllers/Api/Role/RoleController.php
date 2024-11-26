<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Models\Role;
use DataTables;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    
    public function getData()
    {
        return DataTables::of(Role::whereNotIn('name', ['Developer', 'Default']))->addColumn('hashid', function ($data) {
            return Hashids::encode($data->id);
        })->make();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'group' => 'required'
        ]);

        try {
            Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
                'group' => $request->group,
            ]);

            return $this->successResponse('Data berhasil ditambahkan');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function show(Role $role) {
        return $this->successResponse('success', ['role' => $role]);
    }

    public function update(Role $role, Request $request) {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($role)
            ]
        ]);

        try {
            $role->update([
                'name' => $request->name
            ]);

            return $this->successResponse('Data berhasil diperbarui');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function destroy(Role $role) {
        try {
            $role->delete();

            return $this->successResponse('Data berhasil dihapus');
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getPermissions(Role $role)
    {
        try {
            $remappedPermission = [];
            if($role->group == 'mahasiswa' || $role->group == 'dosen') {
                $permissions = Permission::where('group', $role->group)->get();
            } else {
                $permissions = Permission::whereNull('group')->get();
            }
    
    
            foreach ($permissions as $permission) {
                $explodePermissions = \explode('-', $permission->name);
                $slicePermissions = array_slice($explodePermissions, 1);
                $implodePermissions = \implode('-', $slicePermissions);
                $permission['is_checked'] = $role->hasPermissionTo($permission->name);
                $remappedPermission[$implodePermissions][] = $permission;
            }

            $data = [
                'role' => $role,
                'permissions' => $remappedPermission,
            ];
    
            return $this->successResponse('Success', $data);
        } catch(Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function changePermissions(Request $request, Role $role)
    {
        try {
            $role->syncPermissions($request->permission);
            return $this->successResponse('Permission telah diperbarui');
        } catch (QueryException $e) {
            return $this->exceptionResponse($e);
        }
    }

}
