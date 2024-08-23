<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.order.index');
    }


    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('order')
                ->select(
                    'order.*',
                    'pembeli.name as pembeli',
                    'order.foto as bukti_pembayaran' // Tambahkan kolom foto
                )
                ->join('order_detail', 'order_detail.order_id', '=', 'order.id')
                ->join('products', 'products.id', '=', 'order_detail.product_id')
                ->join('brands', 'brands.id', '=', 'products.brand_id')
                ->join('users as pembeli', 'pembeli.id', '=', 'order.users_id')
                ->where('brands.users_id', Auth::user()->id)
                ->distinct('order.id')
                ->get();

            return datatables()->of($data)
                ->editColumn('created_at', function ($data) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
                })
                ->editColumn('total_price', function ($data) {
                    return number_format($data->total_price, 0, '.', '.');
                })
                ->addColumn('bukti_pembayaran', function ($data) {
                    if ($data->bukti_pembayaran) {
                        return '<img src="' . asset('storage/' . $data->bukti_pembayaran) . '" alt="Bukti Pembayaran" width="100">';
                    } else {
                        return 'Belum upload bukti';
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if ($data->status == 'sudah dikonfirmasi') {
                        return '-';
                    } else {
                        $button = ' <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-outline-success" href="javascript:void(0)" data-id="' . $data->id . '" id="konfirmasi">
                            <i class="fas fa-sm fa-check"></i> Konfirmasi
                        </a>
                                           <a class="btn btn-sm mx-1 btn-outline-warning" href="javascript:void(0)">
                            <i class="fas fa-sm fa-eye"></i> Detail Order
                        </a>
                        </div></div>';

                        return $button;
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['aksi', 'bukti_pembayaran']) // Tambahkan kolom bukti_pembayaran
                ->toJson();
        }
    }



    public function uploadBuktiPembayaran(Request $request)
    {
        // Validasi file
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id' => 'required|integer|exists:order,id',
        ]);

        // Ambil file dari request
        $file = $request->file('foto');

        // Simpan file dan ambil nama file
        $path = $file->store('assets/foto-bukti-pembayaran-order', 'public');

        // Update database
        $updated = DB::table('order')
            ->where('id', $request->id)
            ->update([
                'foto' => $path,
                'status' => 'pending'
            ]);

        // Cek hasil update
        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data disimpan',
                'title' => 'Berhasil'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'title' => 'Gagal'
            ]);
        }
    }



    public function detailOrder(Request $request)
    {
        $data = DB::table('order')
            ->select('order.*', 'products.name as produk', 'brands.name as brand', 'order_detail.qty as qty_beli', 'products.price as harga_satuan')
            ->join('users', 'users.id', '=', 'order.users_id')
            ->join('order_detail', 'order_detail.order_id', '=', 'order.id')
            ->join('products', 'products.id', '=', 'order_detail.product_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where('users.id', Auth::user()->id)
            ->get();

        // dd($data);



        $html = '<div>';
        $html .= '<a class="btn btn-sm btn-danger my-3" id="tutupDetail">Tutup</a>';

        $html .= '<div class="card shadow">';

        $html .= '<div class="card-body">';
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-bordered">';
        $html .= '<tr>';

        $html .= '<th >Nama Produk</th>';
        $html .= '<th >Brand</th>';
        $html .= '<th> Jumlah Beli</th>';
        $html .= '<th >Harga</th>';


        foreach ($data as $d) {
            $html .= '<tr>';


            $html .= '<td>' . $d->produk . '</td>';
            $html .= '<td>' . $d->brand . '</td>';
            $html .= '<td>' . $d->qty_beli . '</td>';
            $html .= '<td>' . number_format($d->qty_beli * $d->harga_satuan, 0, '.', '.') . '</td>';
            $html .= '</tr>';
        }

        return response()->json($html);
    }


    public function konfirmasi(Request $request)
    {
        // dd($request->all());
        $cart = DB::table('order')
            ->join('order_detail', 'order_detail.order_id', '=', 'order.id')
            ->where('order.id', $request->id)->get();

        // dd($cart);


        $bahan_jurnal_umum = DB::table('order')
            ->select(
                'brands.id as brand_id',
                'brands.name as brand_name',
                DB::raw('SUM(products.price * order_detail.qty) as total_uang')
            )
            ->join('order_detail', 'order_detail.order_id', '=', 'order.id')
            ->join('products', 'products.id', '=', 'order_detail.product_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->where('order.id', $request->id)
            ->groupBy('brands.id', 'brands.name')
            ->get();

        // dd($bahan_jurnal_umum);
        $coa = DB::table('coa')->select('*')->get();
        DB::beginTransaction();
        try {

            foreach ($cart as $d) {
                $qty = $d->qty;

                DB::table('products')->where('id', $d->product_id)->update([
                    'stock' => DB::raw('stock -' . $qty)
                ]);
            }



            $jurnal_umum = [];
            foreach ($bahan_jurnal_umum as $v) {

                foreach ($coa as $c) {
                    if ($c->nama === 'Kas' || $c->nama === 'kas') {
                        $jurnal_umum[] = [
                            'brand_id' => $v->brand_id,
                            'coa_id' => $c->id,
                            'periode' => Carbon::now(),
                            'tahun' => (int) Carbon::now()->format('Y'),
                            'bulan' => (int) Carbon::now()->format('m'),
                            'kredit' => 0,
                            'debit' => $v->total_uang
                        ];
                    } elseif ($c->nama === 'Penjualan' || $c->nama === 'penjualan') {
                        $jurnal_umum[] = [
                            'brand_id' => $v->brand_id,
                            'coa_id' => $c->id,
                            'periode' => Carbon::now(),
                            'tahun' => (int) Carbon::now()->format('Y'),
                            'bulan' => (int) Carbon::now()->format('m'),
                            'kredit' => $v->total_uang,
                            'debit' => 0,
                        ];
                    }
                }
            }
            DB::table('jurnal_umum')->insert($jurnal_umum);

            DB::table('order')->where('id', $request->id)->update([
                'status' => 'sudah dikonfirmasi'
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Konfirmmasi telah dilakukan',
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
