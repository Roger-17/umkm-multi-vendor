<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoaController extends Controller
{
    public function index()
    {
        return view('pages.coa.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Coa::all();
            return datatables()->of($data)
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sm fa-edit"></i> Aksi
                    </button>';

                    $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="' . route('coa.edit', $data->id) . '">
                             Edit</a>
                        <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                             Delete</a>
                    </div></div>';

                    return $button;
                })
                ->addIndexColumn()
                ->rawColumns(['aksi'])
                ->toJson();
        }
    }


    public function create(){
        return view('pages.coa.create');
    }

    public function simpan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $coa = new Coa();
        $coa->kode = $request->kode;
        $coa->nama = $request->nama;
        $coa->save();


        if ($coa) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        }
    }

    public function edit($id){
        $coa = Coa::find($id);

        return view('pages.coa.edit', [
            'coa' => $coa
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->fails(), 422);
        }


        $coa = Coa::find($request->id);
        $coa->kode = $request->kode;
        $coa->nama = $request->nama;
        $coa->save();


        if ($coa) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        }
    }


    public function hapus($id)
    {
        $coa = Coa::find($id);

        $coa->delete();

        if ($coa) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data dihapus',
                'title' => 'Berhasil'
            ]);
        }
    }
}
