<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan;


class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::count();
        $totalSk = SuratKeputusan::count();

        return view('dashboard', compact(
            'totalPegawai',
            'totalSk'
        ));
    }
}