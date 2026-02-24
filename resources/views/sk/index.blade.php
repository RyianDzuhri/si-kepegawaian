@extends('layouts.app')

@push('styles')
<style>
    /* === CSS TAMPILAN MODERN & HALUS === */
    body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif !important; }
    
    /* Input Styling untuk Filter */
    .form-control-custom {
        padding: 0.6rem 1.2rem; 
        border-radius: 50rem; /* Bentuk pill penuh */
        border: 1px solid #e2e8f0;
        font-size: 0.9rem; 
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    
    /* Select Dropdown Styling Khusus agar Simetris */
    .form-select-custom {
        padding: 0.6rem 2.8rem 0.6rem 1.2rem; /* Padding kanan diperlebar agar teks tidak nabrak panah */
        border-radius: 50rem;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        background-position: right 1.2rem center; /* Menggeser posisi panah ke tengah lengkungan */
    }

    .form-control-custom:focus, .form-select-custom:focus {
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); 
        border-color: #93c5fd;
        outline: none;
    }

    .input-group-text-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-right: none;
        border-top-left-radius: 50rem;
        border-bottom-left-radius: 50rem;
        padding-left: 1.2rem;
        color: #94a3b8;
    }

    /* Tabel Styling */
    .table-custom th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
        padding: 1rem 1.2rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-custom td {
        padding: 1rem 1.2rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-custom tbody tr { transition: background-color 0.2s ease; }
    .table-custom tbody tr:hover { background-color: #f1f5f9; }

    /* === KUSTOMISASI PAGINASI MODERN === */
    .pagination { margin-bottom: 0; gap: 5px; }
    
    /* Menghilangkan teks info bawaan Laravel */
    .custom-pagination-wrapper p.text-muted { display: none !important; }
    .custom-pagination-wrapper nav div.d-sm-flex { justify-content: flex-end !important; }
    
    .page-item .page-link {
        border-radius: 8px !important;
        border: 1px solid transparent;
        color: #475569;
        background-color: #f8fafc;
        padding: 0.5rem 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 4px 6px -1px rgba(13, 110, 253, 0.2);
    }
    .page-item.disabled .page-link { background-color: transparent; color: #cbd5e1; }
    .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }
</style>
@endpush

@section('content')

<div class="container-fluid py-2">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Arsip Digital SK</h4>
            <p class="text-muted small mb-0">Cari, kelola, dan unduh dokumen SK pegawai.</p>
        </div>
        <a href="{{ route('tambah-sk') }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
            <i class="fas fa-cloud-upload-alt me-2"></i>Upload SK Baru
        </a>
    </div>

    {{-- KARTU FILTER PENCARIAN --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row g-3">
                    
                    {{-- 1. Cari Kata Kunci --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="fas fa-search"></i></span>
                            <input type="text" name="q" class="form-control form-control-custom border-start-0 ps-0" 
                                   style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                   placeholder="Cari NIP, Nama, atau No. SK..." 
                                   value="{{ request('q') }}">
                        </div>
                    </div>

                    {{-- 2. Filter Kategori --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Jenis Dokumen SK</label>
                        <select name="jenis_sk" class="form-select form-select-custom">
                            <option value="">Semua Kategori</option>
                            @foreach(['SK CPNS', 'SK PNS', 'SK Kenaikan Pangkat', 'SK Gaji Berkala', 'SK Jabatan'] as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis_sk') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Filter Tahun --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Tahun SK</label>
                        <input type="number" name="tahun" class="form-control form-control-custom" 
                               placeholder="Cth: 2024" 
                               value="{{ request('tahun') }}">
                    </div>

                    {{-- 4. Tombol Filter & Reset --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-dark rounded-pill w-100 fw-semibold shadow-sm" title="Terapkan Filter">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>

                        @if(request()->anyFilled(['q', 'jenis_sk', 'tahun']))
                            <a href="{{ url()->current() }}" class="btn btn-light border rounded-circle shadow-sm flex-shrink-0" style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;" title="Reset Filter">
                                <i class="fas fa-redo-alt text-secondary"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- KARTU TABEL DATA --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="28%">Pemilik SK (Pegawai)</th>
                            <th width="30%">Detail Dokumen</th>
                            <th width="15%">Tanggal SK</th>
                            <th width="10%">File</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratKeputusan as $index => $sk)
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $suratKeputusan->firstItem() + $index }}</td>
                                
                                {{-- KOLOM PEGAWAI --}}
                                <td>
                                    @if($sk->pegawai)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light text-secondary rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $sk->pegawai->nama }}</div>
                                                <small class="text-muted d-block" style="font-size: 0.8rem;">
                                                    {{ $sk->pegawai->nip ? 'NIP. '.$sk->pegawai->nip : 'NIK. '.$sk->pegawai->nik }}
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-danger small fst-italic d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                                <i class="fas fa-user-slash"></i>
                                            </div>
                                            Data Terhapus
                                        </div>
                                    @endif
                                </td>

                                {{-- KOLOM DETAIL SK --}}
                                <td>
                                    @php
                                        $badgeColor = match($sk->jenis_sk) {
                                            'SK Kenaikan Pangkat' => 'bg-warning text-dark border-warning',
                                            'SK Gaji Berkala' => 'bg-success text-success border-success',
                                            'SK Jabatan' => 'bg-info text-info border-info',
                                            'SK PNS', 'SK CPNS' => 'bg-primary text-primary border-primary',
                                            default => 'bg-secondary text-secondary border-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} bg-opacity-10 border border-opacity-25 rounded-pill px-2 py-1 mb-1">
                                        {{ $sk->jenis_sk }}
                                    </span>
                                    <div class="small fw-semibold text-dark text-truncate mt-1" style="max-width: 250px;" title="{{ $sk->nomor_sk }}">
                                        No: {{ $sk->nomor_sk }}
                                    </div>
                                </td>

                                {{-- KOLOM TANGGAL --}}
                                <td>
                                    <div class="text-dark fw-semibold small">
                                        {{ \Carbon\Carbon::parse($sk->tanggal_sk)->isoFormat('D MMM Y') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        TMT: {{ \Carbon\Carbon::parse($sk->tmt_sk)->isoFormat('D MMM Y') }}
                                    </small>
                                </td>

                                {{-- KOLOM FILE --}}
                                <td>
                                    @if($sk->file_sk)
                                        <div class="d-flex align-items-center" title="{{ basename($sk->file_sk) }}">
                                            <i class="fas fa-file-pdf fa-lg text-danger me-2"></i>
                                            <small class="text-muted fw-bold">PDF</small>
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- KOLOM AKSI --}}
                                <td class="text-center">
                                    @if($sk->file_sk)
                                        <a href="{{ asset('storage/' . $sk->file_sk) }}" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                                            <i class="fas fa-download me-1"></i> Unduh
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted" disabled>Kosong</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted d-flex flex-column justify-content-center align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="fas fa-folder-open fa-2x text-secondary opacity-50"></i>
                                        </div>
                                        <h6 class="fw-bold">Tidak ada dokumen ditemukan</h6>
                                        <p class="small mb-0">Coba gunakan kata kunci atau filter yang berbeda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER KARTU: INFO DATA & PAGINASI --}}
        @if($suratKeputusan->hasPages() || $suratKeputusan->total() > 0)
        <div class="card-footer bg-white border-top p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="text-muted small fw-semibold">
                    Menampilkan <span class="text-dark">{{ $suratKeputusan->firstItem() ?? 0 }}</span> sampai <span class="text-dark">{{ $suratKeputusan->lastItem() ?? 0 }}</span> dari total <span class="text-primary">{{ $suratKeputusan->total() }}</span> dokumen
                </div>
                
                {{-- Pembungkus khusus untuk mematikan info default bawaan pagination laravel --}}
                <div class="custom-pagination-wrapper">
                    {{ $suratKeputusan->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection