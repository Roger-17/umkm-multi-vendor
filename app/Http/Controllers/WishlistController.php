<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = DB::table('wishlists')
            ->select(
                'wishlists.*',
                'products.name as product',
                'products.stock as stok',
                'products.price as harga_satuan'
            )
            ->join('products', 'products.id', '=', 'wishlists.product_id')
            ->where('customer_id', Auth::user()->id)->get();

        return view('pages.wishlist', [
            'wishlist' => $wishlist
        ]);
    }

    public function simpan(Request $request)
    {
        $data = DB::table('wishlists')
            ->insert([
                'customer_id' => Auth::user()->id,
                'product_id' => $request->produk_id,
            ]);

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk ditambahkan ke wishlist',
                'title' => 'Berhasil'
            ]);
        }
    }


    public function hapus(Request $request)
    {
        $data = DB::table('wishlists')->where('id', $request->id)->delete();


        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk dihapus dari wishlist',
                'title' => 'Berhasil'
            ]);
        }
    }


    public function wishlistToKeranjang(Request $request)
    {
        $data = DB::table('wishlists')
            ->select('*')
            ->where('customer_id', Auth::user()->id)
            ->get();

        DB::beginTransaction();

        try {
            foreach ($data as $d) {
                DB::table('carts')->insert([
                    'customer_id' => Auth::user()->id,
                    'product_id' => $d->product_id,
                    'qty' => 0
                ]);

                DB::table('wishlists')->whereIn('id', [$d->id])->delete();
            }

            DB::commit();


            return response()->json([
                'status' => 'success',
                'message' => 'Wislisht telah di masukan keranjang',
                'title' => 'Berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
