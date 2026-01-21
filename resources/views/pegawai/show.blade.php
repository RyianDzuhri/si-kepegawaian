@extends('layouts.app')

@section('content')

<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Detail Pegawai</h4>
            <p class="text-muted mb-0">Informasi lengkap dan riwayat dokumen.</p>
        </div>
        <a href="{{ url('/pegawai') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-3 text-center border-end">
                            @if(!empty($pegawai->foto_profil))
                                <img src="{{ asset('storage/' . $pegawai->foto_profil) }}" class="img-thumbnail rounded-circle mb-3" style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->nama) }}&background=0D8ABC&color=fff&size=200" class="img-thumbnail rounded-circle mb-3" style="width: 180px; height: 180px;">
                            @endif

                            <h5 class="fw-bold mb-1">{{ $pegawai->nama }}</h5>
                            <span class="badge bg-primary px-3 py-2 mb-2">{{ $pegawai->nip }}</span>
                            
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ url('/pegawai/edit') }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-user-edit me-2"></i>Edit Biodata
                                </a>
                            </div>
                        </div>

                        <div class="col-md-9 ps-md-4">
                            <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3">Informasi Pribadi & Jabatan</h6>
                            
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="30%" class="text-muted">Tempat, Tgl Lahir</td>
                                    <td width="5%">:</td>
                                    <td class="fw-semibold">
                                        {{ $pegawai->tempat_lahir }}, 
                                        {{-- Format tanggal Indonesia (membutuhkan Carbon nanti) --}}
                                        {{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->isoFormat('D MMMM Y') }}
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
                                    <td><span class="badge bg-info text-dark">{{ $pegawai->jenis_pegawai }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->jabatan }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Golongan / Ruang</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->golongan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pendidikan Terakhir</td>
                                    <td>:</td>
                                    <td>{{ $pegawai->pendidikan_terakhir }}</td>
                                </tr>
                            </table>

                            <div class="alert alert-light border mt-3 d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-3 fs-4"></i>
                                <div>
                                    <small class="text-muted d-block">TMT Pangkat Terakhir:</small>
                                    <strong>{{ \Carbon\Carbon::parse($pegawai->tmt_pangkat_terakhir)->isoFormat('D MMMM Y') }}</strong>
                                </div>
                                <div class="ms-5">
                                    <small class="text-muted d-block">TMT Gaji Berkala Terakhir:</small>
                                    <strong>{{ \Carbon\Carbon::parse($pegawai->tmt_gaji_berkala_terakhir)->isoFormat('D MMMM Y') }}</strong>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-folder-open me-2 text-warning"></i> Arsip Dokumen SK
                    </h6>
                    
                    <a href="{{ url('/sk/create?pegawai_id='.$pegawai->id) }}" class="btn btn-success btn-sm">
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
                                @forelse($pegawai->sk as $index => $item)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td><span class="fw-semibold">{{ $item->jenis_sk }}</span></td>
                                    <td>{{ $item->nomor_sk }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_sk)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tmt_sk)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($item->file_sk)
                                            <a href="{{ asset('storage/' . $item->file_sk) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted small">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Hapus SK ini?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="80" class="mb-3 opacity-50">
                                        <p class="text-muted mb-0">Belum ada dokumen SK yang diupload untuk pegawai ini.</p>
                                        <a href="{{ url('/sk/create?pegawai_id='.$pegawai->id) }}" class="btn btn-sm btn-primary mt-2">
                                            Upload Sekarang
                                        </a>
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