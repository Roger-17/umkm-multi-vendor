<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index()
    {
        return view('pages.jurnal-umum.index');
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

        // Mendapatkan user ID
        $user = Auth::user()->id;

        // Mendapatkan data brand untuk user yang login
        $brand = $this->getBarnd($user);

        // Mengambil nilai periode dari request dan memisahkan bulan dan tahun
        $periode = $request->periode; // format 'mm-yyyy'
        list($month, $year) = explode('-', $periode); // Memisahkan bulan dan tahun

        // Query database untuk mendapatkan data sesuai bulan dan tahun
        $data = DB::table('jurnal_umum')
            ->select('jurnal_umum.*', 'coa.nama as nama_coa', 'coa.kode as kode_coa')
            ->join('coa', 'coa.id', '=', 'jurnal_umum.coa_id')
            ->join('brands', 'brands.id', '=', 'jurnal_umum.brand_id')
            ->join('users', 'users.id', '=', 'brands.users_id')
            ->where('brands.id', $brand->id)
            ->whereMonth('jurnal_umum.periode', $month)
            ->whereYear('jurnal_umum.periode', $year)
            ->get();

        $html = '<div>';

        $html .= '<div class="card shadow">';
        $html .= '<div class="d-flex flex-column justify-content-center text-center my-3">';
        $html .= '<span>' . $brand->name . '</span>';
        $html .= '<span>Jurnal Umum</span>';
        $html .= '<span>Periode ' . Carbon::createFromDate($year, $month, 1)->locale('id')->translatedFormat('F Y') . ' </span>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-bordered">';
        $html .= '<tr>';

        $html .= '<th >Tanggal</th>';
        $html .= '<th >Akun</th>';
        $html .= '<th >Ref</th>';
        $html .= '<th> Debet</th>';
        $html .= '<th >Kredi</th>';


        foreach ($data as $d) {
            $html .= '<tr>';


            $html .= '<td>' . $d->periode . '</td>';
            $html .= '<td>' . $d->nama_coa . '</td>';
            $html .= '<td>' . $d->kode_coa . '</td>';
            $html .= '<td>' .  $d->debit   .  '</td>';
            $html .= '<td>' . $d->kredit . '</td>';
            $html .= '</tr>';
        }

        return response()->json($html);
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
