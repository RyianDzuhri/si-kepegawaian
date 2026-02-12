@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <h3 class="fw-bold text-dark">Dashboard</h3>
    <p class="text-muted">Selamat datang di Sistem Informasi Kepegawaian.</p>
</div>

{{-- ==================================================== --}}
{{-- BARIS 1: 2 KARTU UTAMA (Total Pegawai & SK)          --}}
{{-- ==================================================== --}}
<div class="row mb-4">
    {{-- 1. Total Pegawai --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Pegawai</p>
                        <h2 class="fw-bold text-primary mb-0">{{ $totalPegawai }}</h2>
                        <small class="text-muted">Orang</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Arsip SK --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold small">Total Arsip SK</p>
                        <h2 class="fw-bold text-success mb-0">{{ $totalSK }}</h2>
                        <small class="text-muted">Dokumen</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-file-archive fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================================================== --}}
{{-- BARIS 2: 3 KARTU REMINDER (Pensiun, Pangkat, Gaji)   --}}
{{-- ==================================================== --}}
<div class="row mb-4">
    
    {{-- 3. Pensiun Tahun Ini --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Pensiun Tahun Ini</p>
                        <h3 class="fw-bold text-danger mb-0">{{ $pensiunTahunIni }}</h3>
                        <small class="text-muted" style="font-size: 0.7rem;">(PNS & PPPK)</small>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                        <i class="fas fa-user-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Waktunya Naik Pangkat --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Waktunya Naik Pangkat</p>
                        <h3 class="fw-bold text-warning mb-0">{{ $naikPangkatSegera }}</h3>
                        <small class="text-muted" style="font-size: 0.7rem;">(Khusus PNS, > 4 Thn)</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-level-up-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Waktunya Naik Gaji Berkala --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Kenaikan Gaji Berkala</p>
                        {{-- Warna text Info biar kebaca --}}
                        <h3 class="fw-bold text-info mb-0" style="color: #0dcaf0 !important;">{{ $naikGajiSegera }}</h3>
                        <small class="text-muted" style="font-size: 0.7rem;">(PNS & PPPK, > 2 Thn)</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="fas fa-money-bill-wave fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- BODY: TABEL PEMBERITAHUAN --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white p-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-bell text-warning me-2"></i>Pemberitahuan & Reminder</h5>
    </div>
    <div class="card-body p-0">
        
        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="pangkat-tab" data-bs-toggle="tab" data-bs-target="#pangkat" type="button" role="tab">
                    <i class="fas fa-medal me-2"></i>Naik Pangkat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="gaji-tab" data-bs-toggle="tab" data-bs-target="#gaji" type="button" role="tab">
                    <i class="fas fa-money-bill-wave me-2"></i>Gaji Berkala
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-danger" id="pensiun-tab" data-bs-toggle="tab" data-bs-target="#pensiun" type="button" role="tab">
                    <i class="fas fa-wheelchair me-2"></i>Pensiun
                </button>
            </li>
        </ul>

        <div class="tab-content p-4" id="myTabContent">
            
            {{-- TAB 1: KENAIKAN PANGKAT --}}
            <div class="tab-pane fade show active" id="pangkat" role="tabpanel">
                <div class="alert alert-primary bg-opacity-10 border-0 d-flex align-items-center mb-3">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    <div class="text-primary small"><strong>PNS</strong> yang masa kerjanya sudah genap/lewat <strong>4 Tahun</strong>.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Jabatan & Gol</th>
                                <th>TMT Terakhir</th>
                                <th>Jadwal Baru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listNaikPangkat as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->nama }}</div>
                                    <small class="text-muted">{{ $p->nip }}</small>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $p->jabatan }}</div>
                                    <span class="badge bg-light text-dark border">{{ $p->golongan }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->format('d/m/Y') }}</td>
                                <td class="fw-bold text-danger">
                                    {{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->addYears(4)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-upload"></i> SK
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Semua aman, tidak ada jadwal naik pangkat bulan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: GAJI BERKALA --}}
            <div class="tab-pane fade" id="gaji" role="tabpanel">
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center mb-3">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    <div class="text-success small"><strong>PNS & PPPK</strong> yang masa kerjanya sudah genap/lewat <strong>2 Tahun</strong>.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Golongan</th>
                                <th>TMT Terakhir</th>
                                <th>Jadwal Baru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listGajiBerkala as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->nama }}</div>
                                    <div class="d-flex align-items-center gap-1">
                                        <small class="text-muted">{{ $p->nip }}</small>
                                        <span class="badge bg-secondary" style="font-size: 0.6rem;">{{ $p->jenis_pegawai }}</span>
                                    </div>
                                </td>
                                <td>{{ $p->golongan }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->format('d/m/Y') }}</td>
                                <td class="text-success fw-bold">
                                    {{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->addYears(2)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-success shadow-sm">
                                        <i class="fas fa-upload"></i> SK
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Tidak ada jadwal kenaikan gaji berkala.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 3: PENSIUN --}}
            <div class="tab-pane fade" id="pensiun" role="tabpanel">
                 <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                    <div class="text-danger small">Pegawai (PNS/PPPK) yang <strong>akan pensiun dalam 1 tahun</strong> atau <strong>sudah lewat</strong>.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Status</th>
                                <th>Tgl Lahir</th>
                                <th>Usia</th>
                                <th>Tgl Pensiun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listPensiun as $p)
                            {{-- LOGIKA SUDAH DIFILTER DI CONTROLLER, DISINI TINGGAL TAMPILKAN --}}
                            @php
                                $batas = 58;
                                if ($p->jenis_pegawai === 'PNS' && strpos($p->golongan, 'IV') === 0) $batas = 60;
                                $pppkHigh = ['XIII', 'XIV', 'XV', 'XVI', 'XVII'];
                                if ($p->jenis_pegawai === 'PPPK' && in_array($p->golongan, $pppkHigh)) $batas = 60;
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->nama }}</div>
                                    <small class="text-muted">{{ $p->nip }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $p->jenis_pegawai }}</span>
                                    <span class="badge bg-light text-dark border">{{ $p->golongan }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_lahir)->format('d/m/Y') }}</td>
                                <td><strong>{{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }}</strong> Thn</td>
                                <td>
                                    <span class="text-danger fw-bold">
                                        {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($batas)->format('d/m/Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted" style="font-size: 0.7rem;">(BUP: {{ $batas }} Thn)</small>
                                </td>
                                <td>
                                    <a href="{{ route('tampil-pegawai', $p->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada pegawai yang masuk masa persiapan pensiun.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection