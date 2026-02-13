<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class ManajemenPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $listUnitKerja = Pegawai::select('unit_kerja')->whereNotNull('unit_kerja')->distinct()->orderBy('unit_kerja', 'asc')->pluck('unit_kerja');
        $query = Pegawai::query();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('jenis_pegawai', $request->input('status'));
        }

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
            
            // --- FIELD TANGGAL ---
            'tmt_pengangkatan' => 'required|date', // <--- BARU: WAJIB DIISI
            'tmt_pangkat_terakhir' => 'nullable|date',
            'tmt_gaji_berkala_terakhir' => 'nullable|date',
            
            'foto_cropped' => 'nullable|string', 
        ]);

        // Auto Rapikan Jabatan (Huruf Besar Awal Kata)
        $data['jabatan'] = ucwords(strtolower($request->jabatan));

        if ($request->filled('foto_cropped')) {
            $image = Image::make($request->foto_cropped);
            $fileName = time() . '_' . uniqid() . '.png';
            $path = 'foto_pegawai/' . $fileName;
            Storage::disk('public')->put($path, (string) $image->encode('png'));
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
            
            // --- FIELD TANGGAL ---
            'tmt_pengangkatan' => 'required|date',
            'tmt_pangkat_terakhir' => 'nullable|date',
            'tmt_gaji_berkala_terakhir' => 'nullable|date',
            
            'foto_cropped' => 'nullable|string',
        ]);

        // Auto Rapikan Jabatan
        $data['jabatan'] = ucwords(strtolower($request->jabatan));

        if ($request->filled('foto_cropped')) {
            if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }
            $image = Image::make($request->foto_cropped);
            $fileName = time() . '_' . uniqid() . '.png';
            $path = 'foto_pegawai/' . $fileName;
            Storage::disk('public')->put($path, (string) $image->encode('png'));
            $data['foto_profil'] = $path;
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

    // --- FITUR EXPORT PDF ---
    public function exportPdf()
    {
        ini_set('max_execution_time', 600); 
        ini_set('memory_limit', '512M');
        $pegawai = Pegawai::orderBy('nama', 'asc')->get();
        $pdf = Pdf::loadView('pegawai.pdf_profil', compact('pegawai'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Data_Profil_Pegawai_'.date('Y-m-d').'.pdf');
    }

    // --- FITUR EXPORT EXCEL (UPDATE TMT PENGANGKATAN) ---
    public function exportExcel()
    {
        $pegawai = Pegawai::orderBy('jenis_pegawai', 'desc')->orderBy('nama', 'asc')->get();

        $data = $pegawai->map(function ($p) {
            $isEligiblePangkat = $p->jenis_pegawai === 'PNS'; 
            $isEligibleGaji    = in_array($p->jenis_pegawai, ['PNS', 'PPPK']);
            $isEligiblePensiun = in_array($p->jenis_pegawai, ['PNS', 'PPPK']);

            // 1. LOGIKA PENSIUN
            $tglPensiun = null; $isPensiun = false;
            if ($isEligiblePensiun && $p->tanggal_lahir) {
                $batasPensiun = 58; 
                if (stripos($p->jabatan, 'Kepala Dinas') !== false) {
                    $batasPensiun = 60;
                }
                $tglPensiun = Carbon::parse($p->tanggal_lahir)->addYears($batasPensiun);
                $isPensiun = Carbon::now()->addYear()->greaterThanOrEqualTo($tglPensiun);
            }

            // 2. LOGIKA NAIK PANGKAT
            $nextPangkat = null; $isNaikPangkat = false;
            if ($isEligiblePangkat && $p->tmt_pangkat_terakhir) {
                $nextPangkat = Carbon::parse($p->tmt_pangkat_terakhir)->addYears(4);
                $isNaikPangkat = $nextPangkat->isPast() || $nextPangkat->isToday();
            }

            // 3. LOGIKA GAJI BERKALA
            $nextGaji = null; $isNaikGaji = false;
            if ($isEligibleGaji && $p->tmt_gaji_berkala_terakhir) {
                $nextGaji = Carbon::parse($p->tmt_gaji_berkala_terakhir)->addYears(2);
                $isNaikGaji = $nextGaji->isPast() || $nextGaji->isToday();
            }
            
            // 4. MASA KERJA (Hitung dari TMT Pengangkatan sampai Sekarang)
            $masaKerja = '-';
            if ($p->tmt_pengangkatan) {
                $awal = Carbon::parse($p->tmt_pengangkatan);
                $skrg = Carbon::now();
                $diff = $awal->diff($skrg);
                $masaKerja = $diff->y . " Thn " . $diff->m . " Bln";
            }

            return (object) [
                'nama' => $p->nama,
                'nip'  => $p->nip,
                'jenis' => $p->jenis_pegawai,
                'golongan' => $p->golongan,
                'jabatan' => $p->jabatan,
                
                // Tambahan Data Baru
                'tmt_pengangkatan' => $p->tmt_pengangkatan ? Carbon::parse($p->tmt_pengangkatan) : null,
                'masa_kerja' => $masaKerja,

                'tgl_naik_pangkat' => $nextPangkat,
                'status_pangkat' => $isNaikPangkat,
                
                'tgl_naik_gaji' => $nextGaji,
                'status_gaji' => $isNaikGaji,

                'tgl_pensiun' => $tglPensiun,
                'status_pensiun' => $isPensiun
            ];
        });

        return view('pegawai.export-excel', compact('data'));
    }
}