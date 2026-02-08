<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image; // WAJIB: Import Library Image

class ManajemenPegawaiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar Unit Kerja Unik untuk dropdown filter
        $listUnitKerja = Pegawai::select('unit_kerja')
            ->whereNotNull('unit_kerja')
            ->distinct()
            ->orderBy('unit_kerja', 'asc')
            ->pluck('unit_kerja');

        // 2. Mulai Query Builder
        $query = Pegawai::query();

        // 3. Logika Pencarian
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

        // 5. Filter Unit Kerja
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja', $request->input('unit_kerja'));
        }

        $pegawai = $query->latest()->paginate(10); 

        return view('pegawai.index', compact('pegawai', 'listUnitKerja'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        // Validasi
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
            'tmt_pangkat_terakhir' => 'nullable|date',
            'tmt_gaji_berkala_terakhir' => 'nullable|date',
            // Kita hapus validasi 'image' karena yang dikirim string base64
            'foto_cropped' => 'nullable|string', 
        ]);

        // LOGIKA SIMPAN FOTO HASIL CROP (BASE64)
        if ($request->filled('foto_cropped')) {
            // 1. Decode string Base64 menjadi Image
            $image = Image::make($request->foto_cropped);
            
            // 2. Buat nama file unik
            $fileName = time() . '_' . uniqid() . '.png';
            
            // 3. Tentukan path penyimpanan (folder foto_pegawai di public disk)
            $path = 'foto_pegawai/' . $fileName;

            // 4. Simpan file ke storage (Encode ke PNG)
            Storage::disk('public')->put($path, (string) $image->encode('png'));

            // 5. Masukkan path ke array data untuk disimpan ke DB
            $data['foto_profil'] = $path;
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
            'tmt_pangkat_terakhir' => 'nullable|date',
            'tmt_gaji_berkala_terakhir' => 'nullable|date',
            'foto_cropped' => 'nullable|string',
        ]);

        // LOGIKA UPDATE FOTO HASIL CROP
        if ($request->filled('foto_cropped')) {
            // 1. Hapus foto lama jika ada
            if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }

            // 2. Decode & Simpan Foto Baru
            $image = Image::make($request->foto_cropped);
            $fileName = time() . '_' . uniqid() . '.png';
            $path = 'foto_pegawai/' . $fileName;

            Storage::disk('public')->put($path, (string) $image->encode('png'));

            $data['foto_profil'] = $path;
        } else {
            // Jika tidak ada foto baru, jangan update field foto_profil (pakai yang lama)
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
        ini_set('max_execution_time', 600); 
        ini_set('memory_limit', '512M');

        $pegawai = Pegawai::orderBy('nama', 'asc')->get();

        $pdf = Pdf::loadView('pegawai.pdf_profil', compact('pegawai'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Data_Profil_Pegawai_'.date('Y-m-d').'.pdf');
    }
}