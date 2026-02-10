@extends('layouts.app')

@section('content')

{{-- HEADER: STATISTIK --}}
<div class="mb-4">
    <h3 class="fw-bold text-dark">Dashboard</h3>
    <p class="text-muted">Selamat datang di Sistem Informasi Kepegawaian.</p>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Total Pegawai</p>
                        <h3 class="fw-bold text-primary mb-0">{{ $totalPegawai }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Arsip SK</p>
                        <h3 class="fw-bold text-success mb-0">{{ $totalSK }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-file-archive fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Pensiun Tahun Ini</p>
                        <h3 class="fw-bold text-danger mb-0">{{ $pensiunTahunIni }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Waktunya Naik Pangkat</p>
                        <h3 class="fw-bold text-warning mb-0">{{ $naikPangkatSegera }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-level-up-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BODY: TABEL PEMBERITAHUAN --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
        <h5 class="mb-0"><i class="fas fa-bell text-warning me-2"></i>Pemberitahuan & Reminder</h5>
    </div>
    <div class="card-body p-0">
        
        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="pangkat-tab" data-bs-toggle="tab" data-bs-target="#pangkat" type="button" role="tab">
                    <i class="fas fa-medal me-2"></i>Kenaikan Pangkat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="gaji-tab" data-bs-toggle="tab" data-bs-target="#gaji" type="button" role="tab">
                    <i class="fas fa-money-bill-wave me-2"></i>Gaji Berkala
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold text-danger" id="pensiun-tab" data-bs-toggle="tab" data-bs-target="#pensiun" type="button" role="tab">
                    <i class="fas fa-wheelchair me-2"></i>Persiapan Pensiun
                </button>
            </li>
        </ul>

        <div class="tab-content p-4" id="myTabContent">
            
            {{-- TAB 1: KENAIKAN PANGKAT (KHUSUS PNS) --}}
            <div class="tab-pane fade show active" id="pangkat" role="tabpanel">
                <div class="alert alert-info border-0 d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>Pegawai (PNS) yang sudah <strong>> 4 Tahun</strong> sejak kenaikan pangkat terakhir. Segera proses!</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Jabatan Saat Ini</th>
                                <th>TMT Terakhir</th>
                                <th>Jadwal Seharusnya (+4 Thn)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listNaikPangkat as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $p->nama }}</div>
                                    <small class="text-muted">{{ $p->nip }}</small>
                                    <br>
                                    <span class="badge bg-primary" style="font-size: 0.7rem">{{ $p->jenis_pegawai }}</span>
                                </td>
                                <td>{{ $p->jabatan }} <br> <small class="text-secondary">{{ $p->golongan }}</small></td>
                                <td>{{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->translatedFormat('d M Y') }}</td>
                                <td class="fw-bold text-danger">
                                    {{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->addYears(4)->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-upload me-1"></i> Proses SK
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pegawai yang perlu naik pangkat saat ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: GAJI BERKALA (PNS & PPPK) --}}
            <div class="tab-pane fade" id="gaji" role="tabpanel">
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    <div>Pegawai (PNS & PPPK) yang berhak Kenaikan Gaji Berkala (Sudah <strong>> 2 Tahun</strong>).</div>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Golongan</th>
                            <th>TMT KGB Terakhir</th>
                            <th>Jadwal KGB Baru (+2 Thn)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listGajiBerkala as $p)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                                <br>
                                <span class="badge bg-info text-dark" style="font-size: 0.7rem">{{ $p->jenis_pegawai }}</span>
                            </td>
                            <td>{{ $p->golongan }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->translatedFormat('d M Y') }}</td>
                            <td class="text-success fw-bold">
                                {{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->addYears(2)->translatedFormat('d M Y') }}
                            </td>
                            <td>
                                <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-file-invoice me-1"></i> Proses SK
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada jadwal KGB dalam waktu dekat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TAB 3: PERSIAPAN PENSIUN (UPDATE: KECUALIKAN HONORER & PARUH WAKTU) --}}
            <div class="tab-pane fade" id="pensiun" role="tabpanel">
                 <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                    <div class="text-danger">Pegawai yang memasuki Masa Persiapan Pensiun (BUP 58 / 60 Tahun).</div>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>NIP & Nama</th>
                            <th>Status & Golongan</th>
                            <th>Tanggal Lahir</th>
                            <th>Usia Saat Ini</th>
                            <th>Estimasi Pensiun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listPensiun as $p)
                        
                        {{-- 1. KECUALIKAN HONORER & PPPK PARUH WAKTU --}}
                        @if(in_array($p->jenis_pegawai, ['Honorer', 'PPPK Paruh Waktu']))
                            @continue
                        @endif

                        {{-- 2. LOGIKA HITUNG BATAS PENSIUN --}}
                        @php
                            $batasPensiun = 58; // Default
                            
                            // PNS Gol IV -> 60 Tahun
                            if ($p->jenis_pegawai === 'PNS' && strpos($p->golongan, 'IV') === 0) {
                                $batasPensiun = 60;
                            }
                            
                            // PPPK Golongan Tinggi -> 60 Tahun
                            $pppkHighGrades = ['XIII', 'XIV', 'XV', 'XVI', 'XVII'];
                            if ($p->jenis_pegawai === 'PPPK' && in_array($p->golongan, $pppkHighGrades)) {
                                $batasPensiun = 60;
                            }
                        @endphp

                        <tr>
                            <td>
                                <div class="fw-bold">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>
                                <div class="badge bg-secondary">{{ $p->jenis_pegawai }}</div>
                                <div class="small text-muted mt-1">{{ $p->golongan }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') }}</td>
                            <td>
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }} Tahun</span>
                            </td>
                            <td>
                                <span class="text-danger fw-bold">
                                    {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($batasPensiun)->translatedFormat('d M Y') }}
                                </span>
                                <br>
                                <small class="text-muted">(BUP: {{ $batasPensiun }} Thn)</small>
                            </td>
                            <td>
                                <a href="{{ route('tampil-pegawai', $p->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada pegawai yang mendekati pensiun.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection