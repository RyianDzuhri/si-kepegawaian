<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Biodata Pegawai</title>

<style>
    .bg-pensiun { background-color: #dc3545; color: white; } /* Merah */
    .bg-persiapan { background-color: #ffc107; color: black; } /* Kuning */
    .bg-aktif { background-color: #198754; color: white; } /* Hijau */
    .bg-honorer { background-color: #6c757d; color: white; } /* Abu-abu */

    body { font-family: Arial, sans-serif; font-size: 11pt; color: #333; }
    .container { width: 100%; padding: 20px; }
    
    .header-center { text-align: center; margin-bottom: 20px; }
    
    .profile-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #0d6efd; padding: 3px; margin-bottom: 10px; }
    .no-pic { width: 150px; height: 150px; line-height: 150px; border-radius: 50%; background-color: #eee; color: #888; font-weight: bold; margin: 0 auto 10px auto; border: 5px solid #0d6efd; }
    
    h2 { font-size: 18pt; margin: 5px 0; color: #2c3e50; }
    .nip-badge { display: inline-block; background-color: #0d6efd; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; margin-top: 5px; font-size: 10pt; }
    
    .section-title { font-size: 12pt; font-weight: bold; color: #555; text-transform: uppercase; border-bottom: 2px solid #eee; padding-bottom: 5px; margin: 25px 0 15px 0; }
    
    .badge { padding: 3px 8px; border-radius: 4px; color: white; font-size: 9pt; font-weight: bold; }
    
    .detail-table { width: 100%; border-collapse: collapse; }
    .detail-table td { padding: 6px 0; vertical-align: top; }
    .label { width: 180px; color: #666; }
    .separator { width: 20px; text-align: center; }
    .value { font-weight: bold; }

    .tmt-box { margin-top: 30px; border: 1px solid #cfe2ff; background-color: #f8f9fa; border-radius: 8px; padding: 15px; }
    .tmt-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .tmt-label { font-size: 9pt; color: #666; display: block; margin-bottom: 2px; }
    .tmt-val { font-size: 11pt; font-weight: bold; color: #2c3e50; }
    
    .text-next { color: #0d6efd; }
    .icon-info { display: inline-block; width: 20px; height: 20px; background-color: #0d6efd; color: white; text-align: center; border-radius: 50%; font-weight: bold; margin-right: 10px; font-size: 10pt; line-height: 20px;}
    .page-break { page-break-after: always; }
</style>
</head>

<body>

@foreach($pegawai as $p)

    @php
        // 1. Setup Tanggal
        $tglLahir = \Carbon\Carbon::parse($p->tanggal_lahir);
        $hariIni = \Carbon\Carbon::now();
        $usia = $tglLahir->age;

        // 2. Logika Batas Pensiun (HANYA JIKA BUKAN HONORER)
        $tglPensiun = null;
        $batasPensiun = '-';
        $sudahPensiun = false;
        $masaPersiapan = false;

        // Cek apakah ini Pegawai Tetap (PNS/PPPK) atau Paruh Waktu yang dianggap punya pensiun
        // Sesuaikan jika PPPK Paruh Waktu tidak dapat pensiun, masukkan ke kondisi exclude.
        if ($p->jenis_pegawai != 'Honorer' && $p->jenis_pegawai != 'PPPK Paruh Waktu') {
            
            // --- LOGIKA HITUNG BATAS USIA PENSIUN (BUP) ---
            $batasPensiun = 58; // Default (PNS Gol I-III, PPPK Gol I-XII)

            // A. Cek PNS Golongan IV (Pembina) -> 60 Tahun
            if ($p->jenis_pegawai === 'PNS' && strpos($p->golongan, 'IV') === 0) {
                $batasPensiun = 60;
            }

            // B. Cek PPPK Golongan 13 ke Atas (Ahli Madya/Utama) -> 60 Tahun
            // Daftar Golongan PPPK Tinggi: XIII, XIV, XV, XVI, XVII
            $pppkHighGrades = ['XIII', 'XIV', 'XV', 'XVI', 'XVII'];
            
            if ($p->jenis_pegawai === 'PPPK' && in_array($p->golongan, $pppkHighGrades)) {
                $batasPensiun = 60;
            }
            // ----------------------------------------------
            
            $tglPensiun = $tglLahir->copy()->addYears($batasPensiun);
            $sudahPensiun = $hariIni->greaterThanOrEqualTo($tglPensiun);
            $masaPersiapan = $hariIni->diffInMonths($tglPensiun) <= 12 && !$sudahPensiun;
        }

        // 3. Logika Jadwal Berikutnya
        $nextPangkat = '-';
        if ($p->jenis_pegawai === 'PNS' && $p->tmt_pangkat_terakhir) {
            $nextPangkat = \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)
                ->addYears(4)->translatedFormat('d F Y');
        }

        $nextKGB = '-';
        // PPPK dan PNS dapat KGB 2 tahun sekali
        if (($p->jenis_pegawai === 'PNS' || $p->jenis_pegawai === 'PPPK') && $p->tmt_gaji_berkala_terakhir) {
            $nextKGB = \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)
                ->addYears(2)->translatedFormat('d F Y');
        }
    @endphp

<div class="container">

    <div class="header-center">
        @if($p->foto_profil && file_exists(public_path('storage/' . $p->foto_profil)))
            <img src="{{ public_path('storage/' . $p->foto_profil) }}" class="profile-img">
        @else
            <div class="no-pic">No Pic</div>
        @endif

        <h2>{{ $p->nama }}</h2>

        @if($p->nip)
            <div class="nip-badge">{{ $p->nip }}</div>
        @endif
    </div>

    <div class="section-title">Informasi Pribadi & Jabatan</div>

    <table class="detail-table">
        <tr>
            <td class="label">Tempat, Tgl Lahir</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->tempat_lahir }}, {{ $tglLahir->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">Status Kepegawaian</td>
            <td class="separator">:</td>
            <td class="value"><strong>{{ $p->jenis_pegawai }}</strong></td>
        </tr>
        <tr>
            <td class="label">Unit Kerja</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->unit_kerja }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->jabatan }}</td>
        </tr>
        
        {{-- GOLONGAN: Sembunyikan jika Honorer atau PPPK Paruh Waktu --}}
        @if($p->jenis_pegawai != 'Honorer' && $p->jenis_pegawai != 'PPPK Paruh Waktu')
        <tr>
            <td class="label">Golongan / Ruang</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->golongan ?? '-' }}</td>
        </tr>
        @endif

        <tr>
            <td class="label">Pendidikan Terakhir</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->pendidikan_terakhir ?? '-' }}</td>
        </tr>

        {{-- INFO USIA --}}
        <tr>
            <td class="label" style="color: #000;">Usia Saat Ini</td>
            <td class="separator">:</td>
            <td class="value">{{ $usia }} Tahun</td>
        </tr>

        {{-- BATAS PENSIUN: HILANG JIKA HONORER / PARUH WAKTU --}}
        @if($p->jenis_pegawai != 'Honorer' && $p->jenis_pegawai != 'PPPK Paruh Waktu')
        <tr>
            <td class="label" style="color: #000;">Batas Pensiun</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $batasPensiun }} Tahun 
                <span style="font-weight: normal; color: #666; font-size: 10pt;">
                    (Pensiun: {{ $tglPensiun ? $tglPensiun->translatedFormat('d F Y') : '-' }})
                </span>
            </td>
        </tr>
        @endif

        <tr>
            <td class="label" style="color: #000;">Status</td>
            <td class="separator">:</td>
            <td class="value">
                @if($p->jenis_pegawai == 'Honorer' || $p->jenis_pegawai == 'PPPK Paruh Waktu')
                    <span class="badge bg-honorer">AKTIF</span>
                @else
                    @if($sudahPensiun)
                        <span class="badge bg-pensiun">SUDAH PENSIUN</span>
                    @elseif($masaPersiapan)
                        <span class="badge bg-persiapan">PERSIAPAN PENSIUN (MPP)</span>
                    @else
                        <span class="badge bg-aktif">AKTIF</span>
                    @endif
                @endif
            </td>
        </tr>
    </table>

    {{-- KOTAK TMT: HILANG JIKA HONORER / PARUH WAKTU --}}
    @if($p->jenis_pegawai != 'Honorer' && $p->jenis_pegawai != 'PPPK Paruh Waktu')
    <div class="tmt-box">
        <div style="font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
            <span class="icon-info">i</span> Periode Kenaikan Pangkat & Gaji
        </div>
        
        <table class="tmt-table">
            <tr>
                <td width="50%">
                    <span class="tmt-label">TMT Pangkat Terakhir</span>
                    <span class="tmt-val">
                        {{ $p->tmt_pangkat_terakhir ? \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->translatedFormat('d F Y') : '-' }}
                    </span>
                </td>
                <td width="50%">
                    <span class="tmt-label">TMT Gaji Berkala Terakhir</span>
                    <span class="tmt-val">
                        {{ $p->tmt_gaji_berkala_terakhir ? \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->translatedFormat('d F Y') : '-' }}
                    </span>
                </td>
            </tr>

            {{-- JADWAL BERIKUTNYA --}}
            <tr>
                <td>
                    <span class="tmt-label" style="color: #0d6efd;">Jadwal Naik Pangkat Berikutnya (+4 Thn)</span>
                    <span class="tmt-val text-next">
                        {{ $nextPangkat }}
                    </span>
                </td>
                <td>
                    <span class="tmt-label" style="color: #0d6efd;">Jadwal Gaji Berkala Berikutnya (+2 Thn)</span>
                    <span class="tmt-val text-next">
                        {{ $nextKGB }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    @endif

</div>

@if(!$loop->last)
<div class="page-break"></div>
@endif

@endforeach

</body>
</html>