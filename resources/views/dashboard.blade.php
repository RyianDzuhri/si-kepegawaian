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
                        <h3 class="fw-bold text-primary mb-0">125</h3>
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
                        <h3 class="fw-bold text-success mb-0">450</h3>
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
                        <h3 class="fw-bold text-danger mb-0">3</h3>
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
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">Naik Pangkat</p>
                        <h3 class="fw-bold text-warning mb-0">12</h3>
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
                    <div>Daftar pegawai yang sudah waktunya naik pangkat (4 tahun sejak TMT terakhir).</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>NIP & Nama</th>
                                <th>Jabatan</th>
                                <th>TMT Terakhir</th>
                                <th>Estimasi Naik</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-bold">Budi Santoso</div>
                                    <small class="text-muted">19850101 201001 1 001</small>
                                </td>
                                <td>Pranata Komputer</td>
                                <td>01 Jan 2022</td>
                                <td class="fw-bold text-primary">01 Jan 2026</td>
                                <td><span class="badge bg-warning text-dark">Segera Proses</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold">Siti Aminah</div>
                                    <small class="text-muted">19900202 201501 2 005</small>
                                </td>
                                <td>Analis Kebijakan</td>
                                <td>01 Apr 2022</td>
                                <td class="fw-bold text-primary">01 Apr 2026</td>
                                <td><span class="badge bg-secondary">Bulan Depan</span></td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="gaji" role="tabpanel">
                <div class="alert alert-success bg-opacity-10 border-0 d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 text-success"></i>
                    <div>Daftar pegawai yang berhak Kenaikan Gaji Berkala (2 tahun sejak terakhir).</div>
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
                        <tr>
                            <td>Ahmad Dahlan</td>
                            <td>III/a</td>
                            <td>01 Mar 2024</td>
                            <td class="text-success fw-bold">01 Mar 2026</td>
                            <td><a href="#" class="btn btn-sm btn-success">Buat SK</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="pensiun" role="tabpanel">
                 <div class="alert alert-danger bg-opacity-10 border-0 d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                    <div class="text-danger">Pegawai yang akan memasuki usia pensiun dalam 1 tahun ke depan.</div>
                </div>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Usia Saat Ini</th>
                            <th>Estimasi Pensiun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>19660101 199003 1 002</td>
                            <td>Drs. Suherman</td>
                            <td>01 Jan 1966</td>
                            <td>59 Thn 11 Bln</td>
                            <td class="text-danger fw-bold">01 Feb 2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection