<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('pages.role.index');
    }

    public function create()
    {
        return view('pages.role.create');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::all();

            return datatables()->of($data)
                ->addColumn('permission', function ($data) {
                    $html = '';
                    foreach ($data->getPermissionNames() as $permission) {
                        $html .= '<button class="btn btn-outline-success  mb-1 mt-1 mr-1">' . $permission . '</button>';
                    }

                    return $html;
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sm fa-edit"></i> Aksi
                    </button>';

                    $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="' . route('role.edit', $data->id) . '">
                             Edit</a>
                        <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                             Delete</a>
                    </div></div>';

                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'permission'])
                ->toJson();
        }
    }

    public function permissionList(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;

            $result = [];

            $data = Permission::select('*')
                ->where('name', 'LIKE', '%' . $search . '%')
                ->get();

            foreach ($data as $d) {
                $result[] = [
                    'id' => $d->id,
                    'text' => $d->name
                ];
            }

            return response()->json($result);
        } else {
            $result = [];

            $data = Permission::select('*')
                ->get();

            foreach ($data as $d) {
                $result[] = [
                    'id' => $d->id,
                    'text' => $d->name
                ];
            }

            return response()->json($result);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error validation',
                'error' => $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            $role = new Role();
            $role->name = $request->role;
            $role->guard_name = 'web';
            $role->save();


            $role->permissions()->attach($request->permission);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Role created'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'something wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $role = Role::find($id);

        return view('pages.role.edit', [
            'role' => $role

        ]);
    }

    public function permissionByRole($id)
    {
        $data = Role::with(['permissions'])->where('id', $id)->get();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $role = Role::find($request->id);
            $role->name = $request->role;
            $role->guard_name = 'web';
            $role->save();


            $role->permissions()->sync($request->permission);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Role updated'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'something wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        $role->delete();

        if ($role) {
            return response()->json([
                'status' => 'success',
                'message' => 'Role deleted'
            ]);
        }
    }
}
