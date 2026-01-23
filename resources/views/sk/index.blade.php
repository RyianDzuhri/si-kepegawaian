@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Arsip Digital SK</h3>
        <p class="text-muted mb-0">Cari dan unduh dokumen SK pegawai.</p>
    </div>
    <a href="{{ route('tambah-sk') }}" class="btn btn-success">
        <i class="fas fa-cloud-upload-alt me-2"></i>Upload SK Baru
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        
        <form action="" method="GET">
            <div class="row g-2 mb-4 bg-light p-3 rounded align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Cari Kata Kunci</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="NIP, Nama, atau Nomor SK...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Kategori SK</label>
                    <select class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="SK CPNS">SK CPNS</option>
                        <option value="SK Kenaikan Pangkat">SK Kenaikan Pangkat</option>
                        <option value="SK Gaji Berkala">SK Gaji Berkala</option>
                        <option value="SK Jabatan">SK Jabatan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tahun SK</label>
                    <input type="number" class="form-control" placeholder="Contoh: 2024">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Pemilik SK (Pegawai)</th>
                        <th width="25%">Detail Dokumen</th>
                        <th width="15%">Tanggal Terbit</th>
                        <th width="15%">File</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suratKeputusan as $sk)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $sk->pegawai->nama }}</div>
                                <small class="text-muted">{{ $sk->pegawai->nip }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $sk->jenis_sk }}</span><br>
                                <small class="text-dark">No: {{ $sk->nomor_sk }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($sk->tanggal_sk)->isoFormat('D MMM YYYY') }}</td>
                            <td>
                                <div class="d-flex align-items-center text-danger">
                                    <i class="fas fa-file-pdf fa-lg me-2"></i>
                                    <small>{{ basename($sk->file_sk) }}</small>
                                </div>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $sk->file_sk) }}" target="_blank" class="btn btn-sm btn-dark"><i class="fas fa-download"></i> Unduh</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection