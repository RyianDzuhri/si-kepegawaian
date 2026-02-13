<?php
// Header agar file dikenali sebagai Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Pegawai_" . date('d-m-Y') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* 1. FONT SERAGAM (Arial 11pt - Standar Excel) */
        body, table, th, td {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }

        /* 2. TABEL DENGAN GARIS TEGAS */
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        
        th, td { 
            border: 1px solid #000000; /* Garis Hitam Solid */
            padding: 6px;
            vertical-align: middle;
        }

        /* 3. WARNA BACKGROUND (Indikator Status) */
        .bg-header { background-color: #d9d9d9; font-weight: bold; text-align: center; }
        .bg-yellow { background-color: #ffff99; } /* Naik Pangkat */
        .bg-green  { background-color: #ccffcc; } /* Naik Gaji */
        .bg-red    { background-color: #ffcccc; } /* Pensiun */
        
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h3 style="font-weight: bold;">DATA PEGAWAI & JADWAL KENAIKAN</h3>
    <p>Diunduh pada: {{ date('d/m/Y H:i') }}</p>
    <br>

    <table border="1">
        <thead>
            <tr style="height: 35px;">
                <th width="50" class="bg-header">No</th>
                <th width="250" class="bg-header">Nama Pegawai</th>
                <th width="180" class="bg-header">NIP / NIK</th>
                <th width="100" class="bg-header">Status</th>
                <th width="80" class="bg-header">Gol.</th>
                <th width="250" class="bg-header">Jabatan</th>
                
                {{-- KOLOM BARU (Hanya TMT Pengangkatan) --}}
                <th width="120" class="bg-header">TMT Pengangkatan</th>

                <th width="120" class="bg-header">Jadwal Pangkat</th>
                <th width="120" class="bg-header">Jadwal Gaji</th>
                <th width="120" class="bg-header">Tgl Pensiun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->nama }}</td>
                
                {{-- Format Teks NIP (Pakai mso-number-format) --}}
                <td style="mso-number-format:'\@';">{{ $row->nip ? $row->nip : '-' }}</td>
                
                <td class="text-center">{{ $row->jenis }}</td>
                <td class="text-center">{{ $row->golongan ?? '-' }}</td>
                <td>{{ $row->jabatan }}</td>

                {{-- TMT PENGANGKATAN (PNS, PPPK, & PPPK PARUH WAKTU) --}}
                <td class="text-center">
                    @if(in_array($row->jenis, ['PNS', 'PPPK', 'PPPK Paruh Waktu']) && $row->tmt_pengangkatan)
                        {{ $row->tmt_pengangkatan->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>

                {{-- NAIK PANGKAT (Hanya Tanggal) --}}
                <td class="text-center {{ $row->status_pangkat ? 'bg-yellow' : '' }}">
                    {{ $row->tgl_naik_pangkat ? $row->tgl_naik_pangkat->format('d/m/Y') : '-' }}
                </td>

                {{-- GAJI BERKALA (Hanya Tanggal) --}}
                <td class="text-center {{ $row->status_gaji ? 'bg-green' : '' }}">
                    {{ $row->tgl_naik_gaji ? $row->tgl_naik_gaji->format('d/m/Y') : '-' }}
                </td>

                {{-- PENSIUN (Hanya Tanggal) --}}
                <td class="text-center {{ $row->status_pensiun ? 'bg-red' : '' }}">
                    {{ $row->tgl_pensiun ? $row->tgl_pensiun->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>