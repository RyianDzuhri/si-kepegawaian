<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        if (Pegawai::where('nip', $request->nip)->exists()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'NIP sudah terdaftar di sistem.');
        }

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

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');

            $fileName = $request->nip . '_' . time() . '.' . $file->getClientOriginalExtension();

            $data['foto_profil'] = $file->storeAs(
                'foto_pegawai',
                $fileName,
                'public'
            );
        }
        
        Pegawai::create($data);

        return redirect()
            ->route('manajemen-pegawai')
            ->with('success', 'Data pegawai berhasil disimpan');
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.show', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $data = $request->validate([
            'nip' => 'required|string|max:20|unique:pegawai,nip,' . $pegawai->id,
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

        if ($request->hasFile('foto_profil')) {

            if (
                $pegawai->foto_profil &&
                Storage::disk('public')->exists($pegawai->foto_profil)
            ) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }

            $file = $request->file('foto_profil');
            $fileName = $request->nip . '_' . time() . '.' . $file->getClientOriginalExtension();

            $data['foto_profil'] = $file->storeAs(
                'foto_pegawai',
                $fileName,
                'public'
            );
        }

        $pegawai->update($data);

        return redirect()
            ->route('manajemen-pegawai')
            ->with('success', 'Data pegawai berhasil diperbarui');
    }
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // hapus foto jika ada
        if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
            Storage::disk('public')->delete($pegawai->foto_profil);
        }

        // hapus data
        $pegawai->delete();

        return redirect()
            ->route('manajemen-pegawai')
            ->with('success', 'Data pegawai berhasil dihapus');
    }

}