<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.user.index');
    }

    public function data(Request $request)
    {

        if ($request->ajax()) {
            $user = User::with(['roles'])->get();

            return datatables()->of($user)
                ->addColumn('role', function ($user) {
                    foreach ($user->roles as $role) {
                        return $role->name;
                    }
                })
                ->addColumn('aksi', function ($user) {

                    foreach ($user->roles as $role) {
                        if ($role->name == 'admin' || $role->name == 'super admin') {
                            return '-';
                        } else {
                            $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-sm fa-edit"></i> Aksi
                            </button>';

                            $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
  <a class="dropdown-item" href="'.route('users.edit', $user->id).'">
                                     Edit</a>
                                <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $user->id . '">
                                     Hapus</a>
                            </div></div>';

                            return $button;
                        }
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'role'])
                ->toJson();
        }
    }

    public function create()
    {
        return view('pages.user.create');
    }


    public function listBrand(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;

            $result = [];

            $data = Brand::select('*')
                ->where('name', 'LIKE', '%' . $search . '%')
                ->where('users_id', null)
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

            $data = Brand::select('*')
                ->where('users_id', null)
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

    public function brandByUser(User $user)
    {
        $user = User::with(['brand'])->where('id', $user)->first();

        return response()->json($user);
    }

    public function roleList(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;

            $result = [];

            $data = Role::select('*')
                ->where('name', 'LIKE', '%' . $search . '%')
                ->get();

            foreach ($data as $d) {
                $result[] = [
                    'id' => $d->id,
                    'name' => $d->name
                ];
            }

            return response()->json($result);
        } else {

            $result = [];
            $role = Role::select('*')
                ->where('name', '!=', 'admin')
                ->get();

            foreach ($role as $r) {
                $result[] = [
                    'id' => $r->id,
                    'text' => $r->name
                ];
            }

            return response()->json($result);
        }
    }


    public function store(Request $request)
    {

        // dd($request->all());

        DB::beginTransaction();

        try {


            // Create the user
            $userCreate = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Find the newly created user
            $users = User::find($userCreate->id);

            // Find the role based on the provided ID
            $role = Role::find($request->role);
            $users->assignRole($role);


            // Find the brand based on the provided ID
            $brand = Brand::find($request->brand);
            $brand->users_id = $users->id;
            $brand->save();




            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User created'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'something wrong',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function edit($id)
    {
        $user = User::find($id);

        return view('pages.user.edit', [
            'user' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted'
            ]);
        }
    }
}
