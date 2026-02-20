@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <h3 class="fw-bold text-dark">Dashboard</h3>
    <p class="text-muted">Melayani dengan hati, mengabdi untuk negeri. Selamat bertugas hari ini!</p>
</div>

{{-- BARIS 1: 2 KARTU UTAMA --}}
<div class="row mb-4">
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

{{-- BARIS 2: 3 KARTU REMINDER --}}
<div class="row mb-4">
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

    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Segera Naik Pangkat</p>
                        <h3 class="fw-bold text-warning mb-0">{{ $naikPangkatSegera }}</h3>
                        <small class="text-muted" style="font-size: 0.7rem;">(Khusus PNS - H-60)</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-level-up-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Segera Naik Gaji</p>
                        <h3 class="fw-bold text-info mb-0" style="color: #0dcaf0 !important;">{{ $naikGajiSegera }}</h3>
                        <small class="text-muted" style="font-size: 0.7rem;">(PNS & PPPK - H-60)</small>
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
                    <i class="fas fa-medal me-2"></i>Kenaikan Pangkat
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
                    <div class="text-primary small"><strong>PNS</strong> yang jadwal naiknya kurang dari <strong>2 Bulan</strong> atau sudah terlewat.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Jabatan & Golongan</th>
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
                                <td>{{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->isoFormat('dddd, D MMMM Y') }}</td>
                                <td class="fw-bold text-danger">
                                    {{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->addYears(4)->isoFormat('dddd, D MMMM Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-upload"></i> SK
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pegawai yang mendekati jadwal naik pangkat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: GAJI BERKALA --}}
            <div class="tab-pane fade" id="gaji" role="tabpanel">
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center mb-3">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    <div class="text-success small"><strong>PNS & PPPK</strong> yang jadwal naiknya kurang dari <strong>2 Bulan</strong> atau sudah terlewat.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Jabatan & Golongan</th>
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
                                    <small class="text-muted">{{ $p->nip }}</small>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $p->jabatan }}</div>
                                    <span class="badge bg-light text-dark border">{{ $p->golongan }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->isoFormat('dddd, D MMMM Y') }}</td>
                                <td class="text-success fw-bold">
                                    {{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->addYears(2)->isoFormat('dddd, D MMMM Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-success shadow-sm">
                                        <i class="fas fa-upload"></i> SK
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pegawai yang mendekati jadwal kenaikan gaji.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 3: PENSIUN --}}
            <div class="tab-pane fade" id="pensiun" role="tabpanel">
                <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                    <div class="text-danger small">Pegawai yang <strong>akan pensiun dalam 1 tahun ke depan</strong> atau <strong>sudah lewat</strong>.</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Jabatan & Golongan</th>
                                <th>Tanggal Lahir</th>
                                <th>Target Pensiun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listPensiun as $p)
                            
                            @php
                                $batas = 58;
                                if (stripos($p->jabatan, 'Kepala Dinas') !== false) {
                                    $batas = 60;
                                }
                            @endphp

                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->nama }}</div>
                                    <small class="text-muted">{{ $p->nip }}</small>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $p->jabatan }}</div>
                                    <span class="badge bg-light text-dark border">{{ $p->golongan }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($p->tanggal_lahir)->isoFormat('dddd, D MMMM Y') }}
                                    <br>
                                    <small class="text-muted">Usia Saat Ini: {{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }} Thn</small>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">
                                        {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($batas)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted" style="font-size: 0.7rem;">(BUP: {{ $batas }} Thn)</small>
                                </td>
                                <td>
                                    <a href="{{ route('tampil-pegawai', $p->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pegawai yang masuk masa persiapan pensiun (1 Tahun Terakhir).</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection