<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Induk_Pegawai_" . date('d-m-Y') . ".xls"); // Nama file saya ubah jadi Data Induk
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body, table, th, td { font-family: Arial, sans-serif; font-size: 10pt; } /* Font sedikit diperkecil biar muat */
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000000; padding: 5px; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }

        /* WARNA HEADER */
        .h-dark   { background-color: #404040; color: #FFFFFF; font-weight: bold; text-align: center; } /* Identitas Utama */
        .h-blue   { background-color: #4472C4; color: #FFFFFF; font-weight: bold; text-align: center; } /* Kepegawaian */
        .h-info   { background-color: #70AD47; color: #FFFFFF; font-weight: bold; text-align: center; } /* Biodata (Hijau) */
        
        /* WARNA MONITORING (Alert) */
        .alert-pangkat { background-color: #DEEBF7; color: #000000; } /* Biru Muda */
        .alert-gaji    { background-color: #FFF2CC; color: #000000; } /* Kuning Muda */
        .alert-pensiun { background-color: #FCE4D6; color: #000000; } /* Merah Muda */
        
        .due-date { font-weight: bold; color: #C00000; } /* Teks Merah jika jatuh tempo */
    </style>
</head>
<body>

    <h3 style="font-weight: bold; text-transform: uppercase;">DATA INDUK PEGAWAI & MONITORING KENAIKAN</h3>
    <p>Tanggal Unduh: {{ date('d F Y, H:i') }}</p>
    <br>

    <table border="1">
        <thead>
            <tr style="height: 40px;">
                {{-- 1. IDENTITAS UTAMA (Hitam/Abu Tua) --}}
                <th width="40" class="h-dark">No</th>
                <th width="250" class="h-dark">Nama Lengkap</th>
                <th width="180" class="h-dark">NIP</th>
                <th width="160" class="h-dark">NIK (KTP)</th> {{-- BARU --}}
                <th width="200" class="h-dark">Unit Kerja</th> {{-- BARU --}}
                
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
            <tr>
                {{-- 1. IDENTITAS --}}
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong style="text-transform: uppercase;">{{ $row->nama }}</strong></td>
                <td style="mso-number-format:'\@';">{{ $row->nip ?? '-' }}</td>
                <td style="mso-number-format:'\@';">{{ $row->nik ?? '-' }}</td> {{-- Format text agar angka 0 tidak hilang --}}
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

                {{-- 4. MONITORING (Background Berwarna jika Warning) --}}
                
                {{-- PANGKAT --}}
                <td class="text-center">{{ $row->tmt_pangkat_terakhir ? $row->tmt_pangkat_terakhir->format('d/m/Y') : '-' }}</td>
                <td class="text-center {{ $row->status_pangkat ? 'alert-pangkat due-date' : '' }}">
                    {{ $row->tgl_naik_pangkat ? $row->tgl_naik_pangkat->format('d/m/Y') : '-' }}
                </td>

                {{-- GAJI --}}
                <td class="text-center">{{ $row->tmt_gaji_terakhir ? $row->tmt_gaji_terakhir->format('d/m/Y') : '-' }}</td>
                <td class="text-center {{ $row->status_gaji ? 'alert-gaji due-date' : '' }}">
                    {{ $row->tgl_naik_gaji ? $row->tgl_naik_gaji->format('d/m/Y') : '-' }}
                </td>

                {{-- PENSIUN --}}
                <td class="text-center {{ $row->status_pensiun ? 'alert-pensiun due-date' : '' }}">
                    {{ $row->tgl_pensiun ? $row->tgl_pensiun->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>