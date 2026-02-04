<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;

class PegawaiController extends Controller
{
    // GET: ambil semua pegawai
    public function index()
    {
        $pegawai = Pegawai::all();

        return response()->json([
            'status' => true,
            'message' => 'Data pegawai',
            'data' => $pegawai
        ]);
    }

    // GET: detail pegawai by id
    public function show($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'status' => false,
                'message' => 'Pegawai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $pegawai
        ]);
    }
}
