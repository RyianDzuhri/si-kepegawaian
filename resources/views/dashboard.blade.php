@extends('layouts.app')

@section('content')

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
            
            <div class="tab-pane fade show active" id="pangkat" role="tabpanel">
                <div class="alert alert-info border-0 d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>Pegawai di bawah ini sudah <strong>> 4 Tahun</strong> sejak kenaikan pangkat terakhir. Segera proses!</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Pegawai</th>
                                <th>Jabatan Saat Ini</th>
                                <th>TMT Terakhir</th>
                                <th>Jadwal Seharusnya</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listNaikPangkat as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $p->nama }}</div>
                                    <small class="text-muted">{{ $p->nip }}</small>
                                </td>
                                <td>{{ $p->jabatan }} <br> <small class="text-secondary">{{ $p->golongan }}</small></td>
                                <td>{{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->translatedFormat('d M Y') }}</td>
                                <td class="fw-bold text-danger">
                                    {{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->addYears(4)->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tampil-pegawai', $p->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada pegawai yang perlu naik pangkat saat ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="gaji" role="tabpanel">
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    <div>Pegawai yang berhak Kenaikan Gaji Berkala (Sudah <strong>> 2 Tahun</strong>).</div>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Golongan</th>
                            <th>TMT KGB Terakhir</th>
                            <th>Jadwal KGB Baru</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listGajiBerkala as $p)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>{{ $p->golongan }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->translatedFormat('d M Y') }}</td>
                            <td class="text-success fw-bold">
                                {{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->addYears(2)->translatedFormat('d M Y') }}
                            </td>
                            <td><a href="{{ route('tampil-pegawai', $p->id) }}" class="btn btn-sm btn-success">Proses</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada jadwal KGB dalam waktu dekat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="pensiun" role="tabpanel">
                 <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                    <div class="text-danger">Pegawai yang berusia <strong>> 58 Tahun</strong> (Masa Persiapan Pensiun).</div>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>NIP & Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Usia Saat Ini</th>
                            <th>Estimasi Pensiun (60 Thn)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listPensiun as $p)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $p->nama }}</div>
                                <small class="text-muted">{{ $p->nip }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }} Tahun
                            </td>
                            <td class="text-danger fw-bold">
                                {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears(60)->translatedFormat('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada pegawai yang mendekati pensiun.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection