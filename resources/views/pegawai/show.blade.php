@extends('layouts.app')

@section('content')

{{-- LOGIKA HITUNG PENSIUN --}}
@php
    use Carbon\Carbon;

    // 1. Tentukan Batas Usia Pensiun (BUP) Default
    $batasPensiun = 58; 
    
    // 2. Cek Jenis Pegawai untuk pengecualian
    // Honorer dan PPPK Paruh Waktu dianggap tidak memiliki BUP standar (Sesuai Kontrak)
    $isNonPensionable = in_array($pegawai->jenis_pegawai, ['Honorer', 'PPPK Paruh Waktu']);
    
    // 3. Cek Kelengkapan Data untuk Golongan/Jadwal
    // PPPK Paruh Waktu & Honorer tidak punya Golongan & Jadwal Kenaikan
    $hideGolonganAndJadwal = in_array($pegawai->jenis_pegawai, ['Honorer', 'PPPK Paruh Waktu']);

    $sudahPensiun = false;
    $masaPersiapan = false;
    $tglPensiun = null;

    if (!$isNonPensionable && $pegawai->tanggal_lahir) {
        // Logika Khusus: Jika Jabatan adalah "Kepala Dinas", BUP jadi 60 tahun
        if (stripos($pegawai->jabatan, 'Kepala Dinas') !== false) {
            $batasPensiun = 60;
        }

        // Hitung Tanggal Pensiun
        $tglLahir = Carbon::parse($pegawai->tanggal_lahir);
        $tglPensiun = $tglLahir->copy()->addYears($batasPensiun);
        
        // Hitung Status
        $hariIni = Carbon::now();
        $sudahPensiun = $hariIni->greaterThanOrEqualTo($tglPensiun);
        // Warning jika kurang dari 1 tahun (12 bulan)
        $masaPersiapan = $hariIni->diffInMonths($tglPensiun) <= 12 && !$sudahPensiun; 
    }
@endphp

{{-- ALERT STATUS PENSIUN (Hanya Tampil Jika Bukan Honorer/Paruh Waktu) --}}
@if(!$isNonPensionable && $tglPensiun)
    @if($sudahPensiun)
        <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">PEGAWAI SUDAH MEMASUKI MASA PENSIUN</h5>
                <p class="mb-0">
                    Pegawai ini telah melewati batas usia pensiun <strong>{{ $batasPensiun }} tahun</strong> pada tanggal 
                    <strong>{{ $tglPensiun->translatedFormat('d F Y') }}</strong>.
                </p>
            </div>
        </div>
    @elseif($masaPersiapan)
        <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="fas fa-clock fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">MASA PERSIAPAN PENSIUN (MPP)</h5>
                <p class="mb-0">
                    Pegawai ini akan pensiun dalam <strong>{{ \Carbon\Carbon::now()->diffForHumans($tglPensiun, ['parts' => 2]) }}</strong> 
                    (Tanggal: {{ $tglPensiun->translatedFormat('d F Y') }}).
                </p>
            </div>
        </div>
    @endif
@endif

<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Detail Pegawai</h4>
            <p class="text-muted mb-0">Informasi lengkap dan riwayat dokumen.</p>
        </div>
        <a href="{{ route('manajemen-pegawai') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        {{-- KOLOM FOTO & NAMA --}}
                        <div class="col-md-3 text-center border-end">
                            @if(!empty($pegawai->foto_profil))
                                <img src="{{ asset('storage/' . $pegawai->foto_profil) }}" 
                                alt="Foto Profil"
                                class="rounded-circle shadow"
                                style="width: 150px; height: 150px; object-fit: cover; object-position: center;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=0D8ABC&color=fff&size=200" class="img-thumbnail rounded-circle mb-3" style="width: 180px; height: 180px;">
                            @endif

                            <h5 class="fw-bold mb-1 mt-3">{{ $pegawai->nama }}</h5>
                            
                            @if($pegawai->nip)
                                <span class="badge bg-primary px-3 py-2 mb-2">{{ $pegawai->nip }}</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 mb-2">Non-NIP</span>
                            @endif
                            
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('edit-pegawai', ['id' => $pegawai->id]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-user-edit me-2"></i>Edit Biodata
                                </a>
                            </div>
                        </div>

                        {{-- KOLOM DETAIL DATA --}}
                        <div class="col-md-9 ps-md-4">
                            <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3">Informasi Pribadi & Jabatan</h6>
                            
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="30%" class="text-muted">Tempat, Tgl Lahir</td>
                                    <td width="5%">:</td>
                                    <td class="fw-semibold">
                                        {{ $pegawai->tempat_lahir }}, 
                                        {{ Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMMM Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status Kepegawaian</td>
                                    <td>:</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $pegawai->jenis_pegawai }}</span>
                                    </td>
                                </tr>

                                @if(in_array($pegawai->jenis_pegawai, ['PNS', 'PPPK']))
                                <tr>
                                    <td class="text-muted">TMT Pengangkatan</td>
                                    <td>:</td>
                                    <td>
                                        @if($pegawai->tmt_pengangkatan)
                                            <span class="text-primary fw-bold">{{ \Carbon\Carbon::parse($pegawai->tmt_pengangkatan)->isoFormat('D MMMM Y') }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endif

                                <tr>
                                    <td class="text-muted">Unit Kerja</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->unit_kerja ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->jabatan }}</td>
                                </tr>
                                
                                {{-- GOLONGAN (DISEMBUNYIKAN UNTUK PARUH WAKTU & HONORER) --}}
                                @if(!$hideGolonganAndJadwal)
                                <tr>
                                    <td class="text-muted">Golongan / Ruang</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->golongan ?? '-' }}</td>
                                </tr>
                                @endif

                                <tr>
                                    <td class="text-muted">Pendidikan Terakhir</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->pendidikan_terakhir }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Usia Saat Ini</td>
                                    <td>:</td>
                                    <td class="fw-semibold">{{ Carbon::parse($pegawai->tanggal_lahir)->age }} Tahun</td>
                                </tr>

                                {{-- BATAS PENSIUN (DISEMBUNYIKAN UNTUK PARUH WAKTU & HONORER) --}}
                                @if(!$isNonPensionable && $tglPensiun)
                                <tr>
                                    <td class="text-muted">Batas Pensiun</td>
                                    <td>:</td>
                                    <td class="fw-semibold">
                                        {{ $batasPensiun }} Tahun 
                                        <span class="text-secondary small">({{ $tglPensiun->translatedFormat('d F Y') }})</span>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            {{-- INFO JADWAL KENAIKAN (DISEMBUNYIKAN UNTUK PARUH WAKTU & HONORER) --}}
                            @if(!$hideGolonganAndJadwal)
                            <div class="alert alert-light border mt-3 d-flex align-items-center">
                                <i class="fas fa-calendar-alt text-primary me-3 fs-4"></i>
                                <div>
                                    <small class="text-muted d-block">TMT Pangkat Terakhir:</small>
                                    <strong>{{ $pegawai->tmt_pangkat_terakhir ? Carbon::parse($pegawai->tmt_pangkat_terakhir)->isoFormat('D MMMM Y') : '-' }}</strong>
                                </div>
                                <div class="ms-5">
                                    <small class="text-muted d-block">TMT Gaji Berkala Terakhir:</small>
                                    <strong>{{ $pegawai->tmt_gaji_berkala_terakhir ? Carbon::parse($pegawai->tmt_gaji_berkala_terakhir)->isoFormat('D MMMM Y') : '-' }}</strong>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN DOKUMEN SK --}}
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-folder-open me-2 text-warning"></i> Arsip Dokumen SK
                    </h6>

                    <a href="{{ route('tambah-sk', ['pegawai_id' => $pegawai->id]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Upload SK Baru
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Jenis SK</th>
                                    <th>Nomor Surat</th>
                                    <th>Tanggal SK</th>
                                    <th>TMT Berlaku</th>
                                    <th>File</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- MENGGUNAKAN RELASI 'sk' DARI MODEL PEGAWAI --}}
                                @forelse($pegawai->sk as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $item->jenis_sk }}</span>
                                    </td>
                                    <td>{{ $item->nomor_sk }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_sk)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tmt_sk)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($item->file_sk)
                                            <a href="{{ asset('storage/' . $item->file_sk) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('hapus-sk', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus SK ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted mb-2"><i class="fas fa-folder-open fa-2x opacity-25"></i></div>
                                        <p class="text-muted mb-0">Belum ada dokumen SK yang diupload.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection