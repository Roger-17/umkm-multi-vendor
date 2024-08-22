<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = DB::table('carts')
            ->select('carts.*', 'products.name as product', 'products.price as harga_produk')
            ->join('products', 'products.id', '=', 'carts.product_id')
            ->get();

        return view('cart', [
            'cart' => $cart
        ]);
    }


    public function ubahQtyCart(Request $request)
    {
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity');

        DB::table('carts')->where('id', $itemId)->update([
            'qty' => $quantity
        ]);

        return redirect()->back();
    }

    public function hapus(Request $request)
    {

        $data = DB::table('carts')->where('id', $request->id)->delete();

        if ($data) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data dihapus dari keranjang',
                'title' => 'Berhasil'
            ]);
        }
    }


    public function checkout(Request $request)
    {


        $cart = DB::table('carts')
            ->where('customer_id', Auth::user()->id)->get();
        $productIds = $cart->pluck('product_id')->toArray();
        $prices = DB::table('products')
            ->whereIn('id', $productIds)
            ->pluck('price', 'id');
        $prices = DB::table('products')
            ->whereIn('id', $productIds)
            ->pluck('price', 'id');

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $orderDetails = [];
            $order =    DB::table('order')->insertGetId([
                'users_id' => Auth::user()->id,
                'total_price' => $totalPrice,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'status' => 'pending',
                'created_at' => Carbon::now()
            ]);
            foreach ($cart as $d) {
                $productId = $d->product_id;
                $qty = $d->qty;

                // DB::table('products')->where('id', $d->product_id)->update([
                //     'stock' => DB::raw('stock -' . $qty)
                // ]);

                if (isset($prices[$productId])) {
                    $price = $prices[$productId] * $qty;
                    $totalPrice += $price;
                }

                $orderDetails[] = [
                    'order_id' => $order,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'price' => $price
                ];
            }


            DB::table('order')->where('id', $order)->update([
                'total_price' => $totalPrice
            ]);
            DB::table('order_detail')->insert($orderDetails);
            DB::table('carts')->where('customer_id', Auth::user()->id)->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order dibuat',
                'title' => 'Berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
