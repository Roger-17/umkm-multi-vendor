<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardCustomerController extends Controller
{
    public function index()
    {
        $brand = Brand::all();
        $category = category::all();
        $produk = Product::latest()->paginate(12);

        $order = DB::table('order')
            ->select('order.*')
            ->join('users', 'users.id', '=', 'order.users_id')
            ->where('users.id', Auth::user()->id)
            ->get();

        return view('pages.dashboard-customer', [
            'brand' => $brand,
            'categories' => $category,
            'produk' => $produk,
            'order' => $order
        ]);
    }
}
