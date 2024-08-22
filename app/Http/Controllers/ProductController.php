<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->hasRole('super admin')) {
                $data = Product::with(['category', 'brand'])->get();
            } elseif (Auth::user()->hasRole('brand')) {
                $data = Product::whereHas('brand', function ($q) {
                    return $q->where('brands.id', Auth::user()->brand->id);
                })->with(['category'])->get();
            }

            return datatables()->of($data)
                ->editColumn('price', function ($data) {
                    return number_format($data->price, 0, '.', '.');
                })
                ->addColumn('category', function ($data) {
                    return $data->category->name;
                })
                ->addColumn('brand', function ($data) {
                    return $data->brand->name;
                })
                ->addColumn('aksi', function ($data) {
                    if (Auth::user()->hasRole('super admin')) {
                        return '-';
                    } else {
                        $button = '<div class="dropdown d-inline mr-2"><button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-sm fa-edit"></i> Aksi
                        </button>';

                        $button .= ' <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a class="dropdown-item" href="' . route('product.edit', $data->id) . '">
                                 Edit</a>\
                                        <a class="dropdown-item hapus" href="javascript:void(0)" data-id="' . $data->id . '">
                                 Delete</a>

                        </div></div>';

                        return $button;
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'category', 'brand'])
                ->toJson();
        }
    }


    public function create()
    {
        return view('pages.product.create');
    }


    public function categoryList(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;

            $result = [];

            $data = category::select('*')->where('name', 'LIKE', '%' . $search . '%')
                ->get();

            foreach ($data as $d) {
                $result[] = [
                    'id' => $d->id,
                    'text' => $d->name
                ];
            }
        } else {
            $search = $request->q;

            $result = [];

            $data = category::select('*')
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


    public function brandList(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;

            $result = [];

            $data = Brand::select('*')
                ->where('name', 'LIKE', '%' . $search  . '%')
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

        // dd(Auth::user()->getRoleNames());
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super admin')) {
            // dd($request->all());
            $product = new Product();
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = 'active';
            // $product->featured = $request->featured;
            $product->save();


            if ($product) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product created'
                ]);
            }
        }


        if (Auth::user()->hasRole('brand')) {
            $user = Auth::user()->id;

            $brand = Brand::with(['users'])->where('users_id', $user)->first();

            // dd($brand);

            $product = new Product();
            $product->brand_id = $brand->id;
            $product->category_id = $request->category;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->status = 'active';
            $product->save();


            if ($product) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product created'
                ]);
            }
        }
    }

    public function brandByProduct(Request $request)
    {
        $brand = DB::table('brands')
            ->select('brands.*')
            ->join('products', 'products.brand_id', '=', 'brands.id')
            ->get();

        return response()->json($brand);
    }

    public function categoryByProduct(Request $request)
    {
        $category = DB::table('categories')
            ->select('categories.*')
            ->join('products', 'products.category_id', '=', 'categories.id')
            ->get();

        return response()->json($category);
    }

    public function edit($id)
    {
        $product = Product::find($id);

        return view('pages.product.edit', [
            'product' => $product
        ]);
    }


    public function update(Request $request)
    {


        // dd($request->all());

        // cari user login
        $user = Auth::user();

        // cek

        if ($user->hasRole('super admin')) {
            $product = Product::find($request->id);
            $product->category_id = $request->category;
            $product->brand_id = $request->brand;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->description = $request->description;
            $product->save();

            if ($product) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product successfuly updated'
                ]);
            }
        }

        if ($user->hasRole('brand')) {
            $product = Product::find($request->id);
            $product->category_id = $request->category;
            $product->brand_id = $request->brand;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->sku = $request->sku;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->description = $request->description;
            $product->save();

            if ($product) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product successfuly updated'
                ]);
            }
        }
    }

    public function categoryProdukByProduk($id_produk)
    {
        $data = Product::with(['category'])->find($id_produk);

        return response()->json($data);
    }


    public function brandProdukByProduk($id_produk)
    {
        $data = Product::with(['brand'])->find($id_produk);

        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $product = DB::table('products')->where('id', $request->id)->delete();


        if ($product) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted'
            ]);
        }
    }
}
