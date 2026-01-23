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
    // 1. Mulai Query Builder
    $query = Pegawai::query();

    // 2. Logika Pencarian (NIP atau Nama)
    // Jika ada input dengan nama 'q' (query)
    if ($request->filled('q')) {
        $search = $request->input('q');
        $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nip', 'like', "%{$search}%");
        });
    }

    // 3. Logika Filter Status (Jenis Pegawai)
    // Jika ada input dengan nama 'status'
    if ($request->filled('status')) {
        $query->where('jenis_pegawai', $request->input('status'));
    }

    // 4. Ambil data (gunakan paginate agar halaman tidak berat)
    // latest() agar data terbaru muncul di atas
    $pegawai = $query->latest()->paginate(10); 

    // Penting: tambahkan withQueryString() agar saat pindah halaman (page 2), filternya tidak hilang
    return view('pegawai.index', compact('pegawai'));
}
    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
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
            ],
            [
                // custom pesan error
                'nip.unique' => 'NIP sudah terdaftar di sistem.',
                'foto_profil.max' => 'Ukuran foto maksimal 2 MB.',
                'foto_profil.image' => 'File yang diupload harus berupa gambar.',
                'foto_profil.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            ]
        );

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
            ->with('success', 'Data pegawai berhasil disimpan.');
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