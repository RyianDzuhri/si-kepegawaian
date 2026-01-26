<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManajemenPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::query();

        // Pencarian (NIP, NIK, atau Nama)
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%"); // Tambah cari NIK
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('jenis_pegawai', $request->input('status'));
        }

        $pegawai = $query->latest()->paginate(10); 

        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        // Validasi Data Lengkap
        $data = $request->validate(
            [
                // Identitas Utama
                'nik' => 'required|numeric|digits:16|unique:pegawai,nik', // Wajib 16 digit & unik
                'nip' => 'nullable|string|max:20|unique:pegawai,nip', // Boleh kosong (Honorer)
                'nama' => 'required|string|max:100',
                
                // Biodata & Kontak
                'tempat_lahir' => 'required|string|max:50',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'nullable|string|max:20',
                'status_pernikahan' => 'nullable|string|max:20',
                'no_hp' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:100',
                'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

                // Data Kepegawaian
                'unit_kerja' => 'required|string|max:100', // Dinas/Bagian
                'jabatan' => 'required|string|max:100',
                'jenis_pegawai' => 'required|string|max:50',
                'golongan' => 'nullable|string|max:20',
                'pendidikan_terakhir' => 'required|string|max:100',
                
                // Notifikasi TMT
                'tmt_pangkat_terakhir' => 'required|date',
                'tmt_gaji_berkala_terakhir' => 'required|date',
            ],
            [
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus 16 digit.',
                'nik.unique' => 'NIK sudah terdaftar.',
                'nip.unique' => 'NIP sudah terdaftar.',
                'foto_profil.max' => 'Ukuran foto maksimal 2 MB.',
                'foto_profil.image' => 'File harus berupa gambar.',
            ]
        );

        // Upload Foto
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            // Gunakan NIK atau NIP sebagai nama file biar unik
            $prefix = $request->nip ? $request->nip : $request->nik;
            $fileName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();

            $data['foto_profil'] = $file->storeAs(
                'foto_pegawai',
                $fileName,
                'public'
            );
        }

        Pegawai::create($data);

        return redirect()
            ->route('manajemen-pegawai')
            ->with('success', 'Data pegawai berhasil disimpan.');
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.show', compact('pegawai'));
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $data = $request->validate([
            // Validasi Update (Ignore ID sendiri biar gak error saat update diri sendiri)
            'nik' => 'required|numeric|digits:16|unique:pegawai,nik,' . $pegawai->id,
            'nip' => 'nullable|string|max:20|unique:pegawai,nip,' . $pegawai->id,
            'nama' => 'required|string|max:100',
            
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'status_pernikahan' => 'nullable|string|max:20',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            
            'unit_kerja' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'jenis_pegawai' => 'required|string|max:50',
            'golongan' => 'nullable|string|max:20',
            'pendidikan_terakhir' => 'required|string|max:100',
            
            'tmt_pangkat_terakhir' => 'required|date',
            'tmt_gaji_berkala_terakhir' => 'required|date',
            
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }

            $file = $request->file('foto_profil');
            $prefix = $request->nip ? $request->nip : $request->nik;
            $fileName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();

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

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
            Storage::disk('public')->delete($pegawai->foto_profil);
        }

        $pegawai->delete();

        return redirect()
            ->route('manajemen-pegawai')
            ->with('success', 'Data pegawai berhasil dihapus');
    }
}