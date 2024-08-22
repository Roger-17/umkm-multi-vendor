<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $category = category::all();

        $produk = Product::with(['brand'])->limit(12)->get();



        return view('home', [
            'category' => $category,
            'produk' => $produk,
        ]);
    }


    public function simpanWishlist(Request $request)
    {

        $wishlist = new Wishlist();
        $wishlist->customer_id = Auth::user()->id;
        $wishlist->product_id = $request->produk_id;
        $wishlist->save();

        if ($wishlist) {
            return response()->json([
                'status' => 'success',
                'message' => 'Wishlist produk',
                'title' => 'Berhasil'
            ]);
        }
    }
}
