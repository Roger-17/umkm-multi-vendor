<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $brand = Brand::all();
        $category = category::all();
        $produk = Product::latest()->paginate(12);
        return view('pages.shop', [
            'brand' => $brand,
            'categories' => $category,
            'produk' => $produk
        ]);
    }


    public function shopByBrand($id)
    {
        $brand = Brand::all();
        $category = category::all();
        $produk = Product::latest()->paginate(12);

        $list_produk = DB::table('products')
            ->select('products.*')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where('brands.id', intval($id))
            ->get();

        return view('pages.shop-filter-brand', [
            'brand' => $brand,
            'categories' => $category,
            'produk' => $produk,
            'list_produk' => $list_produk
        ]);
    }


    public function shopByCategory($id)
    {
        $brand = Brand::all();
        $category = category::all();
        $produk = Product::latest()->paginate(12);
    }
}
