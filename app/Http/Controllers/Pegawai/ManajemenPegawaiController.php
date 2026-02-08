<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ManajemenPegawaiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar Unit Kerja Unik untuk dropdown filter
        // Menggunakan distinct() agar tidak ada nama unit kerja yang sama muncul 2x
        $listUnitKerja = Pegawai::select('unit_kerja')
            ->whereNotNull('unit_kerja')
            ->distinct()
            ->orderBy('unit_kerja', 'asc')
            ->pluck('unit_kerja');

        // 2. Mulai Query Builder
        $query = Pegawai::query();

        // 3. Logika Pencarian (NIP, NIK, atau Nama)
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        // 4. Filter Status
        if ($request->filled('status')) {
            $query->where('jenis_pegawai', $request->input('status'));
        }

        // 5. Filter Unit Kerja (Baru)
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', $request->input('unit_kerja'));
        }

        // Ambil data dengan pagination
        $pegawai = $query->latest()->paginate(10); 

        return view('pegawai.index', compact('pegawai', 'listUnitKerja'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        // Validasi Data Lengkap
        $data = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:pegawai,nik',
            'nip' => 'nullable|string|max:20|unique:pegawai,nip',
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

        // Upload Foto
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $prefix = $request->nip ? $request->nip : $request->nik;
            $fileName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
            $data['foto_profil'] = $file->storeAs('foto_pegawai', $fileName, 'public');
        }

        Pegawai::create($data);

        return redirect()->route('manajemen-pegawai')->with('success', 'Data pegawai berhasil disimpan.');
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
            // Hapus foto lama
            if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }
            // Upload baru
            $file = $request->file('foto_profil');
            $prefix = $request->nip ? $request->nip : $request->nik;
            $fileName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
            $data['foto_profil'] = $file->storeAs('foto_pegawai', $fileName, 'public');
        } else {
            unset($data['foto_profil']);
        }

        $pegawai->update($data);

        return redirect()->route('manajemen-pegawai')->with('success', 'Data pegawai berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
            Storage::disk('public')->delete($pegawai->foto_profil);
        }

        $pegawai->delete();

        return redirect()->route('manajemen-pegawai')->with('success', 'Data pegawai berhasil dihapus');
    }

    public function exportPdf()
    {
        // 1. Naikkan limit waktu & memori (Penting untuk bulk download)
        ini_set('max_execution_time', 600); 
        ini_set('memory_limit', '512M');

        // 2. Ambil Data
        $pegawai = Pegawai::orderBy('nama', 'asc')->get();

        // 3. Load View PDF Profil Baru
        $pdf = Pdf::loadView('pegawai.pdf_profil', compact('pegawai'));
        
        // 4. Set Kertas PORTRAIT (Tegak) agar pas 1 halaman per orang
        $pdf->setPaper('a4', 'portrait');

        // 5. Download
        return $pdf->download('Data_Profil_Pegawai_'.date('Y-m-d').'.pdf');
    }
}