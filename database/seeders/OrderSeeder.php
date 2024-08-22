<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order =    DB::table('order')
            ->insertGetId([
                'users_id' => 5,
                'total_price' =>  107000
            ]);

        $order_detail = DB::table('order_detail')
            ->insert([
                [
                    'order_id' => $order,
                    'product_id' => 14,
                    'qty' => 1
                ],
                [
                    'order_id' => $order,
                    'product_id' => 22,
                    'qty' => 1
                ]
            ]);


        $kode = Carbon::now()->format('d-m-Y').'-'.time().'-'.rand(1, 100);


        $invoice = DB::table('invoice')
            ->insert([
                [
                    'kode' => $kode,
                    'tgl_transaksi' => Carbon::now(),
                    'users_id' => 3,
                    'order_id' => $order,
                    'status' => 'pending'
                ],
            ]);
    }
}
