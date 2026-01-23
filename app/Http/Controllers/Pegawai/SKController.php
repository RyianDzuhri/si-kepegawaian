<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan;
use Illuminate\Http\Request;

class SKController extends Controller
{
  public function index(Request $request)
    {
        // 1. Mulai Query Builder & Eager Load relasi 'pegawai' biar lebih ringan
        $query = SuratKeputusan::with('pegawai');

        // 2. Filter Kata Kunci (NIP, Nama, atau Nomor SK)
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                // Cari di kolom tabel SK sendiri
                $q->where('nomor_sk', 'like', "%{$search}%")
                  // ATAU cari di tabel Pegawai (Relasi)
                  ->orWhereHas('pegawai', function ($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%")
                           ->orWhere('nip', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Filter Kategori SK
        if ($request->filled('jenis_sk')) {
            $query->where('jenis_sk', $request->input('jenis_sk'));
        }

        // 4. Filter Tahun SK
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_sk', $request->input('tahun'));
        }

        // 5. Ambil data (terbaru di atas)
        $suratKeputusan = $query->latest('tanggal_sk')->paginate(10);

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
