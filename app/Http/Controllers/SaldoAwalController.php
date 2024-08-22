<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaldoAwalController extends Controller
{

    public function index()
    {
        return view('pages.saldo-awal-brand.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->hasRole('super admin')) {
                $data = DB::table('saldo_awal_brand')
                    ->select('saldo_awal_brand.*', 'brands.name as brand')
                    ->join('brands', 'brands.id', '=', 'saldo_awal_brand.brand_id')
                    ->get();
            } else {
                $data = DB::table('saldo_awal_brand')
                    ->select('saldo_awal_brand.*', 'brands.name as brand')
                    ->join('brands', 'brands.id', '=', 'saldo_awal_brand.brand_id')
                    ->join('users', 'users.id', '=', 'brands.users_id')
                    ->where('users.id', Auth::user()->id)
                    ->get();
            }

            return datatables()->of($data)
                ->editColumn('bulan', function ($data) {
                    $carbonDate = Carbon::createFromFormat('!m', $data->bulan);

                    Carbon::setLocale('id');
                    $monthName = $carbonDate->translatedFormat('F');
                    return $monthName;
                })
                ->editColumn('nominal', function ($data) {
                    return number_format($data->nominal, 0, '.', '.');
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sm fa-edit"></i> Aksi
                    </button>';

                    $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="' . route('category.edit', $data->id) . '">
                             Edit</a>
                        <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                             Delete</a>
                    </div></div>';

                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'bulan'])
                ->toJson();
        }
    }

    public function tambah()
    {
        return view('pages.saldo-awal-brand.tambah');
    }

    public function update(Request $request)
    {
        $user = Auth::user()->id;
        $brand = $this->getBrand($user);

        $saldo_awal = DB::table('simpan_saldo_awal')
            ->where('id', $request->id)
            ->update([
                'brand_id' => $brand,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'nominal' => $request->nominal
            ]);

        if ($saldo_awal) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function simpan(Request $request)
    {

        // dd($request->all());

        $user = Auth::user()->id;
        $brand = $this->getBrand($user);

        $saldo_awal = DB::table('saldo_awal_brand')
            ->insert([
                'brand_id' => $brand->id,
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
                'nominal' => $request->nominal
            ]);

        if ($saldo_awal) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function listBrand(Request $request)
    {
        if ($request->has('q')) {
            $result = [];

            $data = DB::table('brands')
                ->select('brands.*')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brands.name', 'LIKE', '%' . request()->q . '%')
                ->where('users.id', Auth::user()->id)
                ->get();

            foreach ($data as $d) {
                $result[] = [
                    'id' => $d->id,
                    'text' => $d->name
                ];
            }

            return response()->json($result);
        } else {
            $data = DB::table('brands')
                ->select('brands.*')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brands.name', 'LIKE', '%' . request()->q . '%')
                ->where('users.id', Auth::user()->id)
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

    private function getBrand($user)
    {
        $data = DB::table('brands')
            ->select('brands.*')
            ->join('users', 'users.id', '=', 'brands.users_id')
            ->where('users.id', $user)
            ->first();

        return $data;
    }

    public function hapus(Request $request)
    {
        $saldo_awal = DB::table('saldo_awal_brand')->where('id', $request->id)->delete();

        if ($saldo_awal) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        }
    }
}
