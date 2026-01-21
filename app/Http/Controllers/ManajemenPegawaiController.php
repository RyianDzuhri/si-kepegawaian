<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManajemenPegawaiController extends Controller
{
    public function index()
    {
        return view('pegawai.manage');
    }
}
