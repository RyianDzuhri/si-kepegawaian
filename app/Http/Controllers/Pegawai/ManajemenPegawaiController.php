<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use Illuminate\Http\Request;

class ManajemenPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        $data = $request->validate([
            'nip' => 'required|string|max:20|unique:pegawai,nip',
            'nama' => 'required|string|max:100',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'jabatan' => 'required|string|max:100',
            'jenis_pegawai' => 'required|string|max:50',
            'golongan' => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'required|string|max:100',
            'tmt_pangkat_terakhir' => 'nullable|date',
            'tmt_gaji_berkala_terakhir' => 'nullable|date',
        ]);

        // 2. HAPUS FILE DARI ARRAY (WAJIB)
        unset($data['foto_profil']);

        // 3. JIKA ADA FOTO → SIMPAN KE STORAGE
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');

            // nama file aman (pakai NIP)
            $fileName = $request->nip . '.' . $file->getClientOriginalExtension();

            // simpan ke storage/app/public/foto_pegawai
            $path = $file->storeAs(
                'foto_pegawai',
                $fileName,
                'public'
            );

            // simpan path ke database
            $data['foto_profil'] = $path;
        }

        // 4. SIMPAN KE DATABASE
        Pegawai::create($data);

        // 5. REDIRECT
        return redirect()
            ->route('manajemen-pegawai')
            ->with('success', 'Data pegawai berhasil disimpan');
    }



}