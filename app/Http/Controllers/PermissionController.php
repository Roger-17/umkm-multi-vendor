<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('pages.permission.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::all();

            return datatables()->of($data)

                ->addColumn('aksi', function ($data) {
                    $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sm fa-edit"></i> Aksi
                    </button>';

                    $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="' . route('permission.edit', $data->id) . '">
                             Edit</a>
                        <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                             Delete</a>
                    </div></div>';

                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'image'])
                ->toJson();
        }
    }

    public function create()
    {
        return view('pages.permission.create');
    }

    public function store(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->permission;
        $permission->guard_name = 'web';
        $permission->save();

        if ($permission) {
            return response()->json([
                'status' => 'success',
                'message' => 'Permission created'
            ]);
        }
    }

    public function edit($id){
        $permission = Permission::find($id);

        return view('pages.permission.edit', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request)
    {
        $permission = Permission::find($request->id);
        $permission->name = $request->permission;
        $permission->guard_name = 'web';
        $permission->save();

        if ($permission) {
            return response()->json([
                'status' => 'success',
                'message' => 'Permission updated'
            ]);
        }
    }


    public function destroy($id)
    {
        $permission = Permission::find($id);

        $permission->delete();

        if ($permission) {
            return response()->json([
                'status' => 'success',
                'message' => 'Permission deleted'
            ]);
        }
    }
}
