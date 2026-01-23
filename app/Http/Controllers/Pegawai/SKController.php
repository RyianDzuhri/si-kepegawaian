<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan;
use Illuminate\Http\Request;

class SKController extends Controller
{
    public function index()
    {
        $suratKeputusan = SuratKeputusan::paginate(10);
        return view('sk.index', compact('suratKeputusan'));
    }

    public function create()
    {
        $pegawaiList = Pegawai::all();
        return view('sk.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'jenis_sk' => 'required|string|max:255',
            'nomor_sk' => 'required|string|max:255',
            'tanggal_sk' => 'required|date',
            'tmt_sk' => 'required|date',
            'file_sk' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('file_sk')) {
            $file = $request->file('file_sk');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('sk_files', $fileName, 'public');

            $validatedData['file_sk'] = $filePath;
        }

        SuratKeputusan::create($validatedData);

        return redirect()->route('arsip-sk')->with('success', 'Surat Keputusan berhasil ditambahkan.');
    }
}
