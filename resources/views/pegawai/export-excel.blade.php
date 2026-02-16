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
        /* 1. FONT SERAGAM */
        body, table, th, td {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }

        /* 2. TABEL DENGAN BORDER TEGAS */
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            border: 1px solid #000000; 
            padding: 6px;
            vertical-align: middle;
        }
        .text-center { text-align: center; }

        /* =========================================================
           3. WARNA HEADER Saja (Pekat + Teks Putih)
           ========================================================= */
        .h-umum   { background-color: #595959; color: #FFFFFF; font-weight: bold; text-align: center; } /* Abu-abu Tua */
        .h-hijau  { background-color: #548235; color: #FFFFFF; font-weight: bold; text-align: center; } /* Hijau Daun */
        .h-biru   { background-color: #2E75B6; color: #FFFFFF; font-weight: bold; text-align: center; } /* Biru Laut */
        .h-kuning { background-color: #C69000; color: #FFFFFF; font-weight: bold; text-align: center; } /* Emas/Kuning Gelap */
        .h-merah  { background-color: #C00000; color: #FFFFFF; font-weight: bold; text-align: center; } /* Merah Darah */

        /* =========================================================
           4. WARNA JATUH TEMPO (Muncul di data HANYA jika sudah waktunya)
           ========================================================= */
        .due-biru   { background-color: #1F4E78; color: #FFFFFF; font-weight: bold; } /* Biru Gelap */
        .due-kuning { background-color: #FFC000; color: #000000; font-weight: bold; } /* Kuning Terang */
        .due-merah  { background-color: #FF0000; color: #FFFFFF; font-weight: bold; } /* Merah Terang */
    </style>
</head>
<body>

    <h3 style="font-weight: bold;">DATA PEGAWAI & JADWAL KENAIKAN</h3>
    <p>Diunduh pada: {{ date('d/m/Y H:i') }}</p>
    <br>

    <table border="1">
        <thead>
            <tr style="height: 35px;">
                {{-- IDENTITAS PEGAWAI --}}
                <th width="50" class="h-umum">No</th>
                <th width="250" class="h-umum">Nama Pegawai</th>
                <th width="180" class="h-umum">NIP / NIK</th>
                <th width="100" class="h-umum">Status</th>
                <th width="80" class="h-umum">Gol.</th>
                <th width="250" class="h-umum">Jabatan</th>
                
                {{-- TMT AWAL --}}
                <th width="130" class="h-hijau">TMT Pengangkatan</th>

                {{-- PANGKAT --}}
                <th width="130" class="h-biru">TMT Pangkat Terakhir</th>
                <th width="130" class="h-biru">Est. Naik Pangkat</th>

                {{-- GAJI BERKALA --}}
                <th width="130" class="h-kuning">TMT Gaji Terakhir</th>
                <th width="130" class="h-kuning">Est. Naik Gaji</th>
                
                {{-- PENSIUN --}}
                <th width="130" class="h-merah">Tgl Pensiun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                {{-- DATA IDENTITAS (POLOS) --}}
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->nama }}</td>
                <td style="mso-number-format:'\@';">{{ $row->nip ? $row->nip : '-' }}</td>
                <td class="text-center">{{ $row->jenis }}</td>
                <td class="text-center">{{ $row->golongan ?? '-' }}</td>
                <td>{{ $row->jabatan }}</td>

                {{-- TMT PENGANGKATAN (POLOS) --}}
                <td class="text-center">
                    @if(in_array($row->jenis, ['PNS', 'PPPK', 'PPPK Paruh Waktu']) && $row->tmt_pengangkatan)
                        {{ $row->tmt_pengangkatan->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>

                {{-- TMT PANGKAT TERAKHIR (POLOS) --}}
                <td class="text-center">
                    @if($row->jenis === 'PNS' && $row->tmt_pangkat_terakhir)
                        {{ $row->tmt_pangkat_terakhir->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>

                {{-- ESTIMASI NAIK PANGKAT (Berwarna HANYA JIKA waktunya tiba) --}}
                <td class="text-center {{ $row->status_pangkat ? 'due-biru' : '' }}">
                    {{ $row->tgl_naik_pangkat ? $row->tgl_naik_pangkat->format('d/m/Y') : '-' }}
                </td>

                {{-- TMT GAJI TERAKHIR (POLOS) --}}
                <td class="text-center">
                    @if(in_array($row->jenis, ['PNS', 'PPPK']) && $row->tmt_gaji_terakhir)
                        {{ $row->tmt_gaji_terakhir->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>

                {{-- ESTIMASI NAIK GAJI (Berwarna HANYA JIKA waktunya tiba) --}}
                <td class="text-center {{ $row->status_gaji ? 'due-kuning' : '' }}">
                    {{ $row->tgl_naik_gaji ? $row->tgl_naik_gaji->format('d/m/Y') : '-' }}
                </td>

                {{-- PENSIUN (Berwarna HANYA JIKA waktunya tiba) --}}
                <td class="text-center {{ $row->status_pensiun ? 'due-merah' : '' }}">
                    {{ $row->tgl_pensiun ? $row->tgl_pensiun->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>