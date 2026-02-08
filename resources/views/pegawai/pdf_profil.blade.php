<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Biodata Pegawai</title>

<style>
body {
    font-family: Arial, sans-serif;
    font-size: 11pt;
    color: #333;
}

.container {
    width: 100%;
    padding: 20px;
}

/* Header Tengah */
.header-center {
    text-align: center;
    margin-bottom: 20px;
}

/* Foto */
.profile-img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #0d6efd;
    padding: 3px;
    margin-bottom: 10px;
}

.no-pic {
    width: 150px;
    height: 150px;
    line-height: 150px;
    border-radius: 50%;
    background-color: #eee;
    color: #888;
    font-weight: bold;
    margin: 0 auto 10px auto;
    border: 5px solid #0d6efd;
}

/* Nama */
h2 {
    font-size: 18pt;
    margin: 5px 0;
    color: #2c3e50;
}

/* NIP */
.nip-badge {
    display: inline-block;
    background-color: #0d6efd;
    color: white;
    padding: 5px 15px;
    border-radius: 5px;
    font-weight: bold;
    margin-top: 5px;
    font-size: 10pt;
}

/* Section Title */
.section-title {
    font-size: 12pt;
    font-weight: bold;
    color: #555;
    text-transform: uppercase;
    border-bottom: 2px solid #eee;
    padding-bottom: 5px;
    margin: 25px 0 15px 0;
}

/* Badge */
.badge {
    padding: 3px 8px;
    border-radius: 4px;
    color: white;
    font-size: 9pt;
    font-weight: bold;
}
.bg-pns { background-color: #0dcaf0; }
.bg-pppk { background-color: #ffc107; color: #000; }
.bg-honorer { background-color: #6c757d; }

/* Detail Table */
.detail-table {
    width: 100%;
    border-collapse: collapse;
}

.detail-table td {
    padding: 6px 0;
    vertical-align: top;
}

.label {
    width: 180px;
    color: #666;
}

.separator {
    width: 20px;
    text-align: center;
}

.value {
    font-weight: bold;
}

/* TMT Box */
.tmt-box {
    margin-top: 30px;
    border: 1px solid #cfe2ff;
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.tmt-table {
    width: 100%;
}

.tmt-label {
    font-size: 9pt;
    color: #666;
}

.tmt-val {
    font-size: 11pt;
    font-weight: bold;
    color: #2c3e50;
}

.icon-info {
    display: inline-block;
    width: 20px;
    height: 20px;
    background-color: #0d6efd;
    color: white;
    text-align: center;
    border-radius: 50%;
    font-weight: bold;
    margin-right: 10px;
}

.page-break {
    page-break-after: always;
}
</style>
</head>

<body>

@foreach($pegawai as $p)

<div class="container">

    <!-- FOTO + NAMA -->
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

    <!-- DATA -->
    <div class="section-title">Informasi Pribadi & Jabatan</div>

    <table class="detail-table">
        <tr>
            <td class="label">Tempat, Tgl Lahir</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $p->tempat_lahir }},
                {{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </td>
        </tr>

        <tr>
            <td class="label">Status Kepegawaian</td>
            <td class="separator">:</td>
            <td class="value">
                @php
                $badgeClass = match($p->jenis_pegawai) {
                    'PNS' => 'bg-pns',
                    'PPPK' => 'bg-pppk',
                    default => 'bg-honorer',
                };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $p->jenis_pegawai }}</span>
            </td>
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

        <tr>
            <td class="label">Golongan / Ruang</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->golongan ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Pendidikan Terakhir</td>
            <td class="separator">:</td>
            <td class="value">{{ $p->pendidikan_terakhir ?? '-' }}</td>
        </tr>
    </table>

    <!-- TMT -->
    <div class="tmt-box">
        <table class="tmt-table">
            <tr>
                <td width="10%">
                    <span class="icon-info">i</span>
                </td>
                <td width="45%">
                    <div class="tmt-label">TMT Pangkat Terakhir</div>
                    <div class="tmt-val">
                        {{ $p->tmt_pangkat_terakhir ? \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->translatedFormat('d F Y') : '-' }}
                    </div>
                </td>
                <td width="45%">
                    <div class="tmt-label">TMT Gaji Berkala Terakhir</div>
                    <div class="tmt-val">
                        {{ $p->tmt_gaji_berkala_terakhir ? \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->translatedFormat('d F Y') : '-' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>

@if(!$loop->last)
<div class="page-break"></div>
@endif

@endforeach

</body>
</html>
