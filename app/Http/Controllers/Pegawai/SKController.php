<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use App\Models\Pegawai\SuratKeputusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // WAJIB ADA UNTUK TRANSACTION

class SKController extends Controller
{
    public function index(Request $request)
    {
        // ... (Kode index sama seperti sebelumnya, tidak berubah)
        $query = SuratKeputusan::with('pegawai');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sk', 'like', "%{$search}%")
                  ->orWhereHas('pegawai', function ($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%")
                           ->orWhere('nip', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('jenis_sk')) {
            $query->where('jenis_sk', $request->input('jenis_sk'));
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_sk', $request->input('tahun'));
        }

        $suratKeputusan = $query->latest('tanggal_sk')->paginate(10);
        return view('sk.index', compact('suratKeputusan'));
    }

    public function create(Request $request)
    {
        // 1. Ambil Data Pegawai
        $pegawaiList = Pegawai::orderBy('nama', 'asc')->get();

        // 2. Cek apakah ada 'titipan' ID dari URL (Auto-Select)
        $selectedPegawai = null;
        if ($request->has('pegawai_id')) {
            $selectedPegawai = Pegawai::find($request->pegawai_id);
        }

        return view('sk.create', compact('pegawaiList', 'selectedPegawai'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $validatedData = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'jenis_sk'   => 'required|string',
            'nomor_sk'   => 'required|string|max:100',
            'tanggal_sk' => 'required|date',
            'tmt_sk'     => 'required|date',
            'file_sk'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
            // Validasi inputan dinamis (boleh nullable jika tidak muncul)
            'golongan_baru' => 'nullable|string',
            'jabatan_baru'  => 'nullable|string',
            'unit_kerja_baru' => 'nullable|string',
        ]);

        // Gunakan Transaction agar aman (Semua sukses atau semua gagal)
        DB::beginTransaction();

        try {
            // 2. Handle Upload File
            if ($request->hasFile('file_sk')) {
                $file = $request->file('file_sk');
                // Nama file rapi: TIPE_NIP_TIMESTAMP.pdf
                $pegawai = Pegawai::findOrFail($request->pegawai_id);
                $ext = $file->getClientOriginalExtension();
                $cleanName = str_replace(' ', '_', $request->jenis_sk) . '_' . ($pegawai->nip ?? $pegawai->nik) . '_' . time() . '.' . $ext;
                
                $filePath = $file->storeAs('sk_files', $cleanName, 'public');
                $validatedData['file_sk'] = $filePath;
            }

            // 3. Simpan Data ke Tabel SK
            SuratKeputusan::create($validatedData);

            // 4. LOGIKA UPDATE OTOMATIS (CORE FEATURE)
            // Jika checkbox dicentang, update data master pegawai
            if ($request->has('update_otomatis')) {
                $pegawai = Pegawai::findOrFail($request->pegawai_id);
                $updated = false;

                // Skenario 1: Kenaikan Pangkat -> Update Golongan & TMT Pangkat
                if ($request->jenis_sk == 'SK Kenaikan Pangkat') {
                    if ($request->filled('golongan_baru')) {
                        $pegawai->golongan = $request->golongan_baru;
                    }
                    $pegawai->tmt_pangkat_terakhir = $request->tmt_sk;
                    $updated = true;
                }
                
                // Skenario 2: Gaji Berkala -> Update TMT KGB Saja
                elseif ($request->jenis_sk == 'SK Gaji Berkala') {
                    $pegawai->tmt_gaji_berkala_terakhir = $request->tmt_sk;
                    $updated = true;
                }

                // Skenario 3: Jabatan / Mutasi -> Update Jabatan & Unit Kerja
                elseif ($request->jenis_sk == 'SK Jabatan' || $request->jenis_sk == 'SK Mutasi') {
                    if ($request->filled('jabatan_baru')) {
                        $pegawai->jabatan = $request->jabatan_baru;
                    }
                    if ($request->filled('unit_kerja_baru')) {
                        $pegawai->unit_kerja = $request->unit_kerja_baru;
                    }
                    // Biasanya jabatan baru mereset TMT Pangkat juga (tergantung aturan), 
                    // tapi amannya update TMT Pangkat juga ke TMT SK ini
                    // $pegawai->tmt_pangkat_terakhir = $request->tmt_sk; 
                    $updated = true;
                }

                // Skenario 4: CPNS ke PNS
                elseif ($request->jenis_sk == 'SK PNS') {
                    $pegawai->jenis_pegawai = 'PNS';
                    $pegawai->tmt_pangkat_terakhir = $request->tmt_sk;
                    $updated = true;
                }

                if ($updated) {
                    $pegawai->save();
                }
            }

            DB::commit(); // Simpan permanen jika tidak ada error
            
            return redirect()->route('arsip-sk')
                ->with('success', 'SK berhasil diupload dan data pegawai telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}