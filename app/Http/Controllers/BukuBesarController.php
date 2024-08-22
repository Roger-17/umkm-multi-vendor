<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BukuBesarController extends Controller
{

    public function index()
    {
        return view('pages.buku-besar.index');
    }

    public function listCoa(Request $request)
    {
        if ($request->has('q')) {
            $result = [];

            $coa = DB::table('coa')->select('*')
                ->where('nama', 'LIKE', '%' . request()->q . '%')
                ->get();

            foreach ($coa as $c) {
                $result[] = [
                    'id' => $c->id,
                    'text' => $c->nama
                ];
            }

            return response()->json($result);
        } else {

            $coa = DB::table('coa')->select('*')
                ->get();

            foreach ($coa as $c) {
                $result[] = [
                    'id' => $c->id,
                    'text' => $c->nama
                ];
            }

            return response()->json($result);
        }
    }


    public function show(Request $request)
    {
        $bulanIndonesiaKeInggris = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December'
        ];

        $periode = str_replace(array_keys($bulanIndonesiaKeInggris), array_values($bulanIndonesiaKeInggris), $request->periode);

        $date = Carbon::createFromFormat('F Y', $periode);

        $bulan_format = $date->format('m');
        $tahun_format = $date->format('Y');


        $user = Auth::user()->id;
        $brand = $this->getBarnd($user);

        $coa = DB::table('coa')->select('*')->where('id', $request->coa)->first();
        if ($coa->nama == 'Kas' || $coa->nama == 'KAS' || $coa->nama == 'kas') {


            $bahan_jurnal_umum = DB::table('jurnal_umum')
                ->select('jurnal_umum.*', 'coa.nama as nama_coa', 'coa.kode as kode_coa')
                ->join('brands', 'brands.id', '=', 'jurnal_umum.brand_id')
                ->join('coa', 'coa.id', '=', 'jurnal_umum.coa_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brand_id', $brand->id)
                ->where('bulan', (int) $bulan_format)
                ->where('tahun', (int) $tahun_format)
                ->where('debit', '!=', 0)
                ->get();


            $saldo_awal = DB::table('saldo_awal_brand')
                ->select('saldo_awal_brand.*', 'brands.id as brand_id')
                ->join('brands', 'brands.id', '=', 'saldo_awal_brand.brand_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brands.id', $brand->id)
                ->first();

            $debit =  DB::table('jurnal_umum')
                ->select(DB::raw('sum(debit) as total_debit'))
                ->join('brands', 'brands.id', '=', 'jurnal_umum.brand_id')
                ->join('coa', 'coa.id', '=', 'jurnal_umum.coa_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brand_id', $brand->id)
                ->where('bulan', (int) $bulan_format)
                ->where('tahun', (int) $tahun_format)
                ->where('debit', '!=', 0)
                ->groupBy('brand_id')
                ->first();

            $html = '<div>';
            $html .= '<div class="card shadow">';
            $html .= '<div class="d-flex flex-column justify-content-center text-center my-3">';
            $html .= '<span>'.$brand->name.'</span>';
            $html .= '<span>Buku Besar Kas</span>';
            $html .= '<span>Periode ' . Carbon::createFromDate($tahun_format, $bulan_format, 1)->locale('id')->translatedFormat('F Y') . ' </span>';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered">';
            $html .= '<tr>';

            $html .= '<th rowspan="2" class="align-middle text-left">Periode</th>';
            $html .= '<th rowspan="2" class="align-middle text-left">Akun</th>';
            $html .= '<th rowspan="2" class="align-middle text-left">Debet</th>';
            $html .= '<th rowspan="2" class="align-middle text-left">Kredit</th>';

            $html .= '<th colspan="2" class="align-middle text-center">Sakdo</th>';


            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th class="align-middle text-center">Debet</th>';
            $html .=  '<th class="align-middle text-center">Kredit</th>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td>' . Carbon::createFromDate($tahun_format, $bulan_format, 1)->locale('id')->translatedFormat('F Y') . '</td>';

            $html .= '<td>' . 'Saldo Awal' . '</td>';
            $html .= '<td>' . '-' . '</td>';
            $html .= '<td>' . '-' . '</td>';
            $html .= '<td  class="text-center">' . number_format($saldo_awal->nominal, 0, '.', '.') . '</td>';
            $html .= '<td  class="text-center">' . '-' . '</td>';

            $html .= '</tr>';


            foreach ($bahan_jurnal_umum as $b) {
                $html .= '<tr>';
                $html .= '<td>' . Carbon::parse($b->periode)->locale('id')->translatedFormat('j F Y') . '</td>';

                $html .= '<td>' . $b->nama_coa . '</td>';
                $html .= '<td>' . number_format($b->debit, 0, '.', '.   ') . '</td>';
                $html .= '<td>' . '-' . '</td>';
                $html .= '<td  class="text-center">' . number_format($saldo_awal->nominal + $b->debit, 0, '.', '.') . '</td>';
                $html .= '<td  class="text-center">' . '-' . '</td>';

                $html .= '</tr>';
            }
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td>Saldo Akhir</td>';
            $html .= '<td></td>';
            $html .= '<td colspan="3" class="text-center">' . number_format($saldo_awal->nominal + $debit->total_debit, 0, '.', '.') . '</td>';

            $html .= '</tr>';

            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            return response()->json($html);
        }

        if ($coa->nama == 'Penjualan' || $coa->nama == 'PENJUALAN' || $coa->nama == 'penjualan') {

            $bahan_jurnal_umum = DB::table('jurnal_umum')
                ->select('jurnal_umum.*', 'coa.nama as nama_coa', 'coa.kode as kode_coa')
                ->join('brands', 'brands.id', '=', 'jurnal_umum.brand_id')
                ->join('coa', 'coa.id', '=', 'jurnal_umum.coa_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brand_id', $brand->id)
                ->where('bulan', (int) $bulan_format)
                ->where('tahun', (int) $tahun_format)
                ->where('kredit', '!=', 0)
                ->get();


            $saldo_awal = DB::table('saldo_awal_brand')
                ->select('saldo_awal_brand.*', 'brands.id as brand_id')
                ->join('brands', 'brands.id', '=', 'saldo_awal_brand.brand_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brands.id', $brand->id)
                ->first();

            $kredit =  DB::table('jurnal_umum')
                ->select(DB::raw('sum(kredit) as total_kredit'))
                ->join('brands', 'brands.id', '=', 'jurnal_umum.brand_id')
                ->join('coa', 'coa.id', '=', 'jurnal_umum.coa_id')
                ->join('users', 'users.id', '=', 'brands.users_id')
                ->where('brand_id', $brand->id)
                ->where('bulan', (int) $bulan_format)
                ->where('tahun', (int) $tahun_format)
                ->where('kredit', '!=', 0)
                ->groupBy('brand_id')
                ->first();



            $html = '<div>';
            $html .= '<div class="card shadow">';
            $html .= '<div class="d-flex flex-column justify-content-center text-center my-3">';
            $html .= '<span>'.$brand->name.'</span>';
            $html .= '<span>Buku Besar Penjualan</span>';
            $html .= '<span>Periode ' . Carbon::createFromDate($tahun_format, $bulan_format, 1)->locale('id')->translatedFormat('F Y') . ' </span>';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<div class="table-responsive">';
            $html .= '<table class="table table-bordered">';
            $html .= '<tr>';

            $html .= '<th rowspan="2" class="align-middle text-left">Periode</th>';
            $html .= '<th rowspan="2" class="align-middle text-left">Akun</th>';
            $html .= '<th rowspan="2" class="align-middle text-left">Debet</th>';
            $html .= '<th rowspan="2" class="align-middle text-left">Kredit</th>';

            $html .= '<th colspan="2" class="align-middle text-center">Sakdo</th>';


            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<th class="align-middle text-center">Debet</th>';
            $html .=  '<th class="align-middle text-center">Kredit</th>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td>' . Carbon::createFromDate($tahun_format, $bulan_format, 1)->locale('id')->translatedFormat('F Y') . '</td>';

            $html .= '<td>' . 'Saldo Awal' . '</td>';
            $html .= '<td>' . '-' . '</td>';
            $html .= '<td>' . '-' . '</td>';
            $html .= '<td  class="text-center">' . '-' . '</td>';
            $html .= '<td  class="text-center">' . number_format($saldo_awal->nominal, 0, '.', '.') . '</td>';

            $html .= '</tr>';


            foreach ($bahan_jurnal_umum as $b) {
                $html .= '<tr>';
                $html .= '<td>' . Carbon::parse($b->periode)->locale('id')->translatedFormat('j F Y') . '</td>';

                $html .= '<td>' . $b->nama_coa . '</td>';
                $html .= '<td>' . '-' . '</td>';
                $html .= '<td>' . number_format($b->kredit, 0, '.', '.   ') . '</td>';

                $html .= '<td  class="text-center">' . '-' . '</td>';
                $html .= '<td  class="text-center">' . number_format($saldo_awal->nominal + $b->kredit, 0, '.', '.') . '</td>';

                $html .= '</tr>';
            }
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '<td>Saldo Akhir</td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td></td>';
            $html .= '<td class="text-center">' . number_format($saldo_awal->nominal + $kredit->total_kredit, 0, '.', '.') . '</td>';

            $html .= '</tr>';

            $html .= '</table>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            return response()->json($html);
        }
    }



    private function getBarnd($user)
    {
        $data = DB::table('brands')
            ->select('brands.*')
            ->join('users', 'users.id', '=', 'brands.users_id')
            ->where('users.id', $user)
            ->first();

        return $data;
    }
}
