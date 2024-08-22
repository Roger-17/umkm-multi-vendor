<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KuponController extends Controller
{
    public function index()
    {
        return view('pages.kupon.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->hasRole('super admin')) {
                $data = DB::table('kupon')
                    ->select('kupon.*', 'brands.name as brand')
                    ->join('brands', 'brands.id', '=', 'kupon.brand_id')
                    ->get();
            } else {
                $data = DB::table('kupon')
                    ->select('kupon.*', 'brands.name as brand')
                    ->join('brands', 'brands.id', '=', 'kupon.brand_id')
                    ->join('users', 'users.id', '=', 'brands.users_id')
                    ->where('users.id', Auth::user()->id)
                    ->get();
            }

            return datatables()->of($data)
                ->editColumn('nominal_diskon', function ($data) {
                    return number_format($data->nominal_diskon, 0, '.', '.');
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sm fa-edit"></i> Aksi
                    </button>';

                    $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="' . route('kupon.edit', $data->id) . '">
                             Edit</a>
                        <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                             Delete</a>
                    </div></div>';

                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'nominal_diskon'])
                ->toJson();
        }
    }

    public function tambah()
    {
        return view('pages.kupon.tambah');
    }

    public function simpan(Request $request)
    {
        // dd($request->all());

        $user = Auth::user()->id;
        $brand = $this->getBrand($user);

        $data = DB::table('kupon')
            ->insert([
                'brand_id' => $brand->id,
                'kode' => $request->kode,
                'nominal_diskon' => $request->nominal_diskon
            ]);

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function edit($id)
    {
        $data = DB::table('kupon')->where('id', $id)->first();

        return view('pages.kupon.edit', [
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user()->id;
        $brand = $this->getBrand($user);

        $data = DB::table('kupon')
            ->where('id', $request->id)
            ->update([
                'brand_id' => $brand->id,
                'kode' => $request->kode,
                'nominal_diskon' => $request->nominal_diskon
            ]);
        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data diubah',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function hapus(Request $request)
    {
        $kupon = DB::table('kupon')->where('id', $request->id)->delete();

        if ($kupon) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }


    private function getBrand($user)
    {
        $data = DB::table('brands')
            ->select('brands.*')
            ->join('users', 'users.id', '=', 'brands.users_id')
            ->where('users.id', $user)
            ->first();

        return $data;
    }
}
