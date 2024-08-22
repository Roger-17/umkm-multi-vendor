<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return view('pages.brand.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::all();

            return datatables()->of($data)
                ->editColumn('image', function ($data) {
                    if ($data->image == null || empty($data->image)) {
                        return '-';
                    } else {
                        return '<img src="' . Storage::url($data->image) . '" width="100" class="img-thumbnail"/>';
                    }
                })
                ->addColumn('aksi', function ($data) {
                    $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sm fa-edit"></i> Aksi
                    </button>';

                    $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <a class="dropdown-item" href="' . route('brand.edit', $data->id) . '">
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
        return view('pages.brand.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error validation',
                'erorr' =>  $validator->errors()
            ]);
        }


        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('asset/image-brand', 'public');

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->image = $image;
            $brand->save();

            if ($brand) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Brand created'
                ]);
            }
        } else {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->save();

            if ($brand) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Brand created'
                ]);
            }
        }
    }

    public function edit($id)
    {
        $brand = Brand::find($id);

        return view('pages.brand.edit', [
            'brand' => $brand
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'sometimes|nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ]);
        }

        $brand = Brand::find($request->id);

        if ($request->hasFile('image')) {
            $image = $request->file('image')
                ->store('assets/brand-image', 'public');

            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->image = $image;
            $brand->save();
        } else {
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            $brand->image = $brand->image;
            $brand->save();
        }

        if ($brand) {
            return response()->json([
                'status' => 'success',
                'message' => 'Brand updated'
            ]);
        }
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);

        $brand->delete();

        if ($brand) {
            return response()->json([
                'status' => 'success',
                'message' => 'Brand deleted'
            ]);
        }
    }
}
