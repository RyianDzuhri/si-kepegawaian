<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Induk_Pegawai_" . date('d-m-Y') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body, table, th, td { font-family: Arial, sans-serif; font-size: 10pt; }
        table { border-collapse: collapse; }
        
        /* Tabel Utama */
        .table-data { width: 100%; }
        .table-data th, .table-data td { border: 1px solid #000000; padding: 5px; vertical-align: middle; }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }

        /* WARNA HEADER TABEL UTAMA */
        .h-dark   { background-color: #404040; color: #FFFFFF; font-weight: bold; text-align: center; } 
        .h-blue   { background-color: #4472C4; color: #FFFFFF; font-weight: bold; text-align: center; } 
        .h-info   { background-color: #70AD47; color: #FFFFFF; font-weight: bold; text-align: center; } 
        
        /* Teks Merah BOLD jika jatuh tempo */
        .due-date { font-weight: bold; color: #C00000; } 
    </style>
</head>
<body>

    <h3 style="font-weight: bold; text-transform: uppercase;">DATA INDUK PEGAWAI & MONITORING KENAIKAN</h3>
    <p style="margin-bottom: 5px;">Tanggal Unduh: {{ date('d F Y, H:i') }}</p>
    
    {{-- ========================================== --}}
    {{-- LEGENDA WARNA (KETERANGAN) --}}
    {{-- ========================================== --}}
    <table style="margin-bottom: 15px; border-collapse: collapse;">
        <tr>
            <td colspan="2" style="border: none; padding-bottom: 5px;">
                <strong style="font-size: 11pt;">Keterangan Status (Warna Baris):</strong>
            </td>
        </tr>
        <tr>
            <td width="30" style="background-color: #FFFFFF; border: 1px solid #000000;">&nbsp;</td>
            <td style="border: none; padding-left: 10px;">Aman (Tidak ada jadwal terdekat)</td>
        </tr>
        <tr>
            <td width="30" style="background-color: #DEEBF7; border: 1px solid #000000;">&nbsp;</td>
            <td style="border: none; padding-left: 10px;">Mendekati / Telat Naik Pangkat (H-60)</td>
        </tr>
        <tr>
            <td width="30" style="background-color: #FFF2CC; border: 1px solid #000000;">&nbsp;</td>
            <td style="border: none; padding-left: 10px;">Mendekati / Telat Naik Gaji Berkala (H-60)</td>
        </tr>
        <tr>
            <td width="30" style="background-color: #FCE4D6; border: 1px solid #000000;">&nbsp;</td>
            <td style="border: none; padding-left: 10px;">Persiapan Pensiun (Tahun Ini / Sudah Lewat)</td>
        </tr>
    </table>
    <br>
    {{-- ========================================== --}}

    {{-- TABEL DATA UTAMA --}}
    <table class="table-data">
        <thead>
            <tr style="height: 40px;">
                {{-- 1. IDENTITAS UTAMA (Hitam/Abu Tua) --}}
                <th width="40" class="h-dark">No</th>
                <th width="250" class="h-dark">Nama Lengkap</th>
                <th width="180" class="h-dark">NIP</th>
                <th width="160" class="h-dark">NIK (KTP)</th>
                <th width="200" class="h-dark">Unit Kerja</th>
                
                {{-- 2. BIODATA (Hijau) --}}
                <th width="40"  class="h-info">L/P</th>
                <th width="150" class="h-info">Tempat Lahir</th>
                <th width="100" class="h-info">Tgl Lahir</th>
                <th width="100" class="h-info">Pendidikan</th>
                <th width="130" class="h-info">No. HP</th>

                {{-- 3. DATA JABATAN (Biru) --}}
                <th width="100" class="h-blue">Status</th>
                <th width="60"  class="h-blue">Gol.</th>
                <th width="250" class="h-blue">Jabatan</th>
                <th width="100" class="h-blue">TMT Awal</th>

                {{-- 4. MONITORING (Header Biru juga biar seragam) --}}
                <th width="110" class="h-blue">TMT Pangkat</th>
                <th width="110" class="h-blue">Est Pangkat</th>

                <th width="110" class="h-blue">TMT Gaji</th>
                <th width="110" class="h-blue">Est Gaji</th>
                
                <th width="110" class="h-blue">Tgl Pensiun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            
            @php
                // LOGIKA MEWARNAI SELURUH BARIS
                // Prioritas warna: Pensiun (Merah) -> Pangkat (Biru) -> Gaji (Kuning)
                $rowBgColor = 'background-color: #FFFFFF;'; // Default Putih
                
                if ($row->status_pensiun) {
                    $rowBgColor = 'background-color: #FCE4D6;'; // Merah Muda
                } elseif ($row->status_pangkat) {
                    $rowBgColor = 'background-color: #DEEBF7;'; // Biru Muda
                } elseif ($row->status_gaji) {
                    $rowBgColor = 'background-color: #FFF2CC;'; // Kuning Muda
                }
            @endphp

            {{-- Terapkan warna ke <tr> agar 1 baris full berwarna --}}
            <tr style="{{ $rowBgColor }}">
                
                {{-- 1. IDENTITAS --}}
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong style="text-transform: uppercase;">{{ $row->nama }}</strong></td>
                <td style="mso-number-format:'\@';">{{ $row->nip ?? '-' }}</td>
                <td style="mso-number-format:'\@';">{{ $row->nik ?? '-' }}</td>
                <td>{{ $row->unit_kerja }}</td>

                {{-- 2. BIODATA --}}
                <td class="text-center">{{ $row->jenis_kelamin }}</td>
                <td>{{ $row->tempat_lahir }}</td>
                <td class="text-center">{{ $row->tanggal_lahir ? $row->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $row->pendidikan }}</td>
                <td style="mso-number-format:'\@';">{{ $row->no_hp }}</td>

                {{-- 3. JABATAN --}}
                <td class="text-center">{{ $row->jenis }}</td>
                <td class="text-center">{{ $row->golongan ?? '-' }}</td>
                <td>{{ $row->jabatan }}</td>
                <td class="text-center">
                    {{ $row->tmt_pengangkatan ? $row->tmt_pengangkatan->format('d/m/Y') : '-' }}
                </td>

                {{-- 4. MONITORING (Teks menjadi Bold Merah jika sedang warning) --}}
                
                {{-- PANGKAT --}}
                <td class="text-center">{{ $row->tmt_pangkat_terakhir ? $row->tmt_pangkat_terakhir->format('d/m/Y') : '-' }}</td>
                <td class="text-center {{ $row->status_pangkat ? 'due-date' : '' }}">
                    {{ $row->tgl_naik_pangkat ? $row->tgl_naik_pangkat->format('d/m/Y') : '-' }}
                </td>

                {{-- GAJI --}}
                <td class="text-center">{{ $row->tmt_gaji_terakhir ? $row->tmt_gaji_terakhir->format('d/m/Y') : '-' }}</td>
                <td class="text-center {{ $row->status_gaji ? 'due-date' : '' }}">
                    {{ $row->tgl_naik_gaji ? $row->tgl_naik_gaji->format('d/m/Y') : '-' }}
                </td>

                {{-- PENSIUN --}}
                <td class="text-center {{ $row->status_pensiun ? 'due-date' : '' }}">
                    {{ $row->tgl_pensiun ? $row->tgl_pensiun->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>