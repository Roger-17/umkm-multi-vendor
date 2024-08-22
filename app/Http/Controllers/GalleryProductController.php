<?php

namespace App\Http\Controllers;

use App\Models\GalleriProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryProductController extends Controller
{
    public function index()
    {
        return view('pages.galeri-product.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->hasRole('brand')) {
                $data = DB::table('galleri_products')
                    ->select('galleri_products.*', 'products.name as product')
                    ->join('products', 'products.id', '=', 'galleri_products.product_id')
                    ->join('brands', 'brands.id', '=', 'products.brand_id')
                    ->join('users', 'users.id', '=', 'brands.users_id')
                    ->where('users.id', Auth::user()->id)
                    ->get();
            }

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
 <a class="dropdown-item" href="' . route('galeri-product.edit', $data->id) . '">
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
        return view('pages.galeri-product.create');
    }

    public function produkList(Request $request)
    {
        if ($request->has('q')) {
            $result = [];

            $data = DB::table('products')
                ->select('products.*')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('products.nama', 'LIKE', '%' . request()->q . '%')
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
            $result = [];

            $data = DB::table('products')
                ->select('products.*')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
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

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product' => 'required',
            'image' => 'sometimes|nullable|image'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $gallery = new GalleriProduct();
        $gallery->product_id = $request->product;
        $gallery->image = $request->file('image')->store('assets/image-product', 'public');
        $gallery->save();

        if ($gallery) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data diproses'
            ]);
        }
    }


    public function edit($id)
    {
        $gallery = GalleriProduct::find($id);

        return view('pages.galeri-product.edit', [
            'gallery' => $gallery
        ]);
    }


    public function update(Request $request)
    {
        $gallery = GalleriProduct::find($request->id);

        if ($request->has('image')) {
            $image =  $request->file('image')->store('assets/image-product', 'public');

            $gallery->product_id = $request->product;
            $gallery->image = $image;
            $gallery->save();

            if ($gallery) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Gallery product updated'
                ]);
            }
        } else {
            $gallery->product_id = $request->product;
            $gallery->save();

            if ($gallery) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Gallery product updated'
                ]);
            }
        }
    }


    public function productByGallery(Request $request)
    {
        $data = DB::table('galleri_products')
            ->select('products.name as product_name', 'products.id as product_id')
            ->join('products', 'products.id', '=', 'galleri_products.product_id')
            ->where('galleri_products.id', $request->galeri_id)->get();
        return response()->json($data);
    }


    public function destroy($id)
    {
        $gallery = GalleriProduct::find($id);

        $gallery->delete();

        if ($gallery) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data diproses'
            ]);
        }
    }
}
