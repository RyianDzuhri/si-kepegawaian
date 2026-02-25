@extends('layouts.app')

@push('styles')
<style>
    /* CSS Tambahan untuk memperhalus UI Dashboard */
    .card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }
    
    /* Styling modern untuk Tabs (Pills) agar pas di HP */
    .nav-pills { flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; white-space: nowrap; padding-bottom: 5px; -webkit-overflow-scrolling: touch; }
    .nav-pills::-webkit-scrollbar { display: none; } /* Sembunyikan scrollbar di HP */
    .nav-pills .nav-link { border-radius: 50rem; color: #6c757d; font-weight: 600; padding: 0.5rem 1.25rem; transition: all 0.3s; font-size: 0.9rem; }
    .nav-pills .nav-link.active { background-color: #0d6efd; color: white; box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2); }
    .nav-pills .nav-link:hover:not(.active) { background-color: #e9ecef; }
</style>
@endpush

@section('content')

{{-- HEADER (Jarak dirapatkan jadi mb-3) --}}
<div class="mb-3">
    <h4 class="fw-bold text-dark mb-1">Dashboard</h4>
    <p class="text-muted small">Melayani dengan hati, mengabdi untuk negeri. Selamat bertugas hari ini!</p>
</div>

{{-- BARIS 1: RINGKASAN UTAMA (Jarak antar kartu dirapatkan jadi g-3, margin bawah mb-3) --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-3 p-md-4 d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary me-3">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Pegawai Aktif</p>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalPegawai }} <span class="fs-6 text-muted fw-normal">Orang</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-3 p-md-4 d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success me-3">
                    <i class="fas fa-file-archive fa-2x"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Arsip SK</p>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalSK }} <span class="fs-6 text-muted fw-normal">Dokumen</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BARIS 2: REMINDER --}}
<h6 class="fw-bold text-muted text-uppercase mb-2 mt-4" style="font-size: 0.8rem;"><i class="fas fa-bolt text-warning me-2"></i>Status Perhatian Khusus</h6>
<div class="row g-3 mb-4">
    
    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="bg-danger bg-opacity-10 p-2 rounded text-danger"><i class="fas fa-user-clock"></i></div>
                    <span class="badge bg-light text-muted border">1 Tahun Ke Depan</span>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $pensiunTahunIni }}</h4>
                <p class="text-muted small fw-semibold mb-0">Memasuki Masa Pensiun</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="bg-warning bg-opacity-10 p-2 rounded text-warning"><i class="fas fa-level-up-alt"></i></div>
                    <span class="badge bg-light text-muted border">H-60</span>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $naikPangkatSegera }}</h4>
                <p class="text-muted small fw-semibold mb-0">Segera Kenaikan Pangkat</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="bg-info bg-opacity-10 p-2 rounded text-info"><i class="fas fa-money-bill-wave"></i></div>
                    <span class="badge bg-light text-muted border">H-60</span>
                </div>
                <h4 class="fw-bold text-dark mb-0">{{ $naikGajiSegera }}</h4>
                <p class="text-muted small fw-semibold mb-0">Segera Kenaikan Gaji</p>
            </div>
        </div>
    </div>
</div>

{{-- BODY: TABEL PEMBERITAHUAN --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    {{-- Padding disesuaikan jadi p-3 untuk HP dan p-md-4 untuk Desktop --}}
    <div class="card-body p-3 p-md-4">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h6 class="mb-3 mb-md-0 fw-bold text-dark">Detail Jadwal Pegawai</h6>
            
            {{-- WADAH TAB MODERN (PILLS) --}}
            <div class="bg-light p-1 rounded-pill d-inline-block" style="max-width: 100%;">
                <ul class="nav nav-pills border-0 m-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pangkat-tab" data-bs-toggle="tab" data-bs-target="#pangkat" type="button" role="tab">Pangkat</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="gaji-tab" data-bs-toggle="tab" data-bs-target="#gaji" type="button" role="tab">Gaji Berkala</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pensiun-tab" data-bs-toggle="tab" data-bs-target="#pensiun" type="button" role="tab">Pensiun</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="myTabContent">
            
            {{-- TAB 1: KENAIKAN PANGKAT --}}
            <div class="tab-pane fade show active" id="pangkat" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top mb-0" style="min-width: 600px;">
                        <thead class="table-light text-muted text-uppercase" style="font-size: 0.75rem;">
                            <tr>
                                <th class="ps-3 py-2">Nama Pegawai</th>
                                <th class="py-2">Jabatan & Golongan</th>
                                <th class="py-2">TMT Terakhir</th>
                                <th class="py-2">Est. Jadwal Baru</th>
                                <th class="text-end pe-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($listNaikPangkat as $p)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $p->nama }}</div>
                                    <span class="text-muted">{{ $p->nip }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark mb-1">{{ $p->jabatan }}</div>
                                    <span class="badge bg-light text-secondary border">{{ $p->golongan }}</span>
                                </td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->isoFormat('D MMM Y') }}</td>
                                <td>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 border border-warning border-opacity-25 rounded-pill">
                                        {{ \Carbon\Carbon::parse($p->tmt_pangkat_terakhir)->addYears(4)->isoFormat('D MMM Y') }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-upload"></i> <span class="d-none d-sm-inline ms-1">SK</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-folder-open mb-2 fa-2x text-secondary opacity-25"></i><br>Tidak ada jadwal terdekat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: GAJI BERKALA --}}
            <div class="tab-pane fade" id="gaji" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top mb-0" style="min-width: 600px;">
                        <thead class="table-light text-muted text-uppercase" style="font-size: 0.75rem;">
                            <tr>
                                <th class="ps-3 py-2">Nama Pegawai</th>
                                <th class="py-2">Jabatan & Golongan</th>
                                <th class="py-2">TMT Terakhir</th>
                                <th class="py-2">Est. Jadwal Baru</th>
                                <th class="text-end pe-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($listGajiBerkala as $p)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $p->nama }}</div>
                                    <span class="text-muted">{{ $p->nip }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark mb-1">{{ $p->jabatan }}</div>
                                    <span class="badge bg-light text-secondary border">{{ $p->golongan }}</span>
                                </td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->isoFormat('D MMM Y') }}</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 border border-info border-opacity-25 rounded-pill">
                                        {{ \Carbon\Carbon::parse($p->tmt_gaji_berkala_terakhir)->addYears(2)->isoFormat('D MMM Y') }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('tambah-sk', ['pegawai_id' => $p->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-upload"></i> <span class="d-none d-sm-inline ms-1">SK</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-folder-open mb-2 fa-2x text-secondary opacity-25"></i><br>Tidak ada jadwal terdekat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 3: PENSIUN --}}
            <div class="tab-pane fade" id="pensiun" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top mb-0" style="min-width: 600px;">
                        <thead class="table-light text-muted text-uppercase" style="font-size: 0.75rem;">
                            <tr>
                                <th class="ps-3 py-2">Nama Pegawai</th>
                                <th class="py-2">Jabatan & Golongan</th>
                                <th class="py-2">Tanggal Lahir</th>
                                <th class="py-2">Est. Pensiun</th>
                                <th class="text-end pe-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($listPensiun as $p)
                            @php
                                $batas = 58;
                                if (stripos($p->jabatan, 'Kepala Dinas') !== false) { $batas = 60; }
                            @endphp
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $p->nama }}</div>
                                    <span class="text-muted">{{ $p->nip }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark mb-1">{{ $p->jabatan }}</div>
                                    <span class="badge bg-light text-secondary border">{{ $p->golongan }}</span>
                                </td>
                                <td>
                                    <span class="text-dark d-block">{{ \Carbon\Carbon::parse($p->tanggal_lahir)->isoFormat('D MMM Y') }}</span>
                                    <span class="text-muted small">Usia: {{ \Carbon\Carbon::parse($p->tanggal_lahir)->age }} Thn</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25 rounded-pill">
                                        {{ \Carbon\Carbon::parse($p->tanggal_lahir)->addYears($batas)->isoFormat('MMM Y') }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('tampil-pegawai', $p->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                        <i class="fas fa-eye text-muted"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-folder-open mb-2 fa-2x text-secondary opacity-25"></i><br>Belum ada pegawai di masa persiapan pensiun.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection