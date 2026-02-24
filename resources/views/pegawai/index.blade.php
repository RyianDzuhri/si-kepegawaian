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

    /* Tombol Aksi Bulat Modern */
    .btn-action {
        width: 35px; height: 35px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 50%; border: none;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-action:hover { transform: scale(1.1); }
    .btn-action-info { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
    .btn-action-info:hover { background-color: #0dcaf0; color: #fff; }
    .btn-action-warning { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .btn-action-warning:hover { background-color: #ffc107; color: #000; }
    .btn-action-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .btn-action-danger:hover { background-color: #dc3545; color: #fff; }

/* === KUSTOMISASI PAGINASI MODERN === */
    .pagination { margin-bottom: 0; gap: 5px; }
    
    /* MENGHILANGKAN TEKS INFO BAWAAN LARAVEL */
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
    
    {{-- HEADER HALAMAN & TOMBOL AKSI UTAMA --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Data Pegawai</h4>
            <p class="text-muted small mb-0">Manajemen dan monitoring data seluruh pegawai aktif.</p>
        </div>
        <div class="d-flex gap-2">
            {{-- Tombol Export Excel dipindah ke sini --}}
            <a href="{{ route('export-excel-pegawai') }}" target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm fw-semibold">
                <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
            <a href="{{ route('tambah-pegawai') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                <i class="fas fa-plus-circle me-2"></i> Tambah Pegawai
            </a>
        </div>
    </div>

    {{-- KARTU FILTER PENCARIAN --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ url()->current() }}" method="GET"> 
                <div class="row g-3">
                    
                    {{-- Pencarian --}}
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-custom"><i class="fas fa-search"></i></span>
                            <input type="text" name="q" class="form-control form-control-custom border-start-0 ps-0" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" placeholder="Cari NIP, NIK, atau Nama..." value="{{ request('q') }}">
                        </div>
                    </div>

                    {{-- Filter Unit Kerja --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Unit Kerja</label>
                        <select name="unit_kerja" class="form-select form-select-custom">
                            <option value="">Semua Unit Kerja</option>
                            @foreach($listUnitKerja as $uk)
                                <option value="{{ $uk }}" {{ request('unit_kerja') == $uk ? 'selected' : '' }}>
                                    {{ $uk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Status Kepegawaian</label>
                        <select name="status" class="form-select form-select-custom">
                            <option value="">Semua Status</option>
                            <option value="PNS" {{ request('status') == 'PNS' ? 'selected' : '' }}>PNS</option>
                            <option value="PPPK" {{ request('status') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            <option value="PPPK Paruh Waktu" {{ request('status') == 'PPPK Paruh Waktu' ? 'selected' : '' }}>PPPK Paruh Waktu</option>
                            <option value="Honorer" {{ request('status') == 'Honorer' ? 'selected' : '' }}>Honorer/Kontrak</option>
                        </select>
                    </div>

                    {{-- Tombol Terapkan & Reset --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-dark rounded-pill w-100 fw-semibold shadow-sm"><i class="fas fa-filter me-1"></i> Filter</button>
                        
                        @if(request()->anyFilled(['q', 'status', 'unit_kerja']))
                            <a href="{{ url()->current() }}" class="btn btn-light border rounded-circle shadow-sm" style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;" title="Reset Filter">
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
                            <th width="30%">Identitas Pegawai</th>
                            <th width="22%">Unit Kerja</th>
                            <th width="20%">Jabatan & Golongan</th>
                            <th width="13%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawai as $index => $item)
                        <tr onclick="window.location='{{ route('tampil-pegawai', $item->id) }}'" style="cursor: pointer;">
                            
                            <td class="text-center text-muted fw-semibold">{{ $pegawai->firstItem() + $index }}</td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3 shadow-sm rounded-circle" style="width: 45px; height: 45px; overflow:hidden; border: 2px solid #fff;">
                                        @if($item->foto_profil)
                                            <img src="{{ asset('storage/'. $item->foto_profil) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=f1f5f9&color=64748b&bold=true" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $item->nama }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.8rem;">
                                            {{ $item->nip ? 'NIP. '.$item->nip : 'NIK. '.$item->nik }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="text-dark fw-semibold small">{{ $item->unit_kerja }}</div>
                            </td>
                            
                            <td>
                                <div class="fw-semibold text-dark small">{{ $item->jabatan }}</div>
                                <span class="badge bg-light text-secondary border mt-1">{{ $item->golongan ?? '-' }}</span>
                            </td>
                            
                            <td class="text-center">
                                @php
                                    $badgeClass = match($item->jenis_pegawai) {
                                        'PNS' => 'bg-success text-success border-success',
                                        'Honorer' => 'bg-warning text-warning border-warning',
                                        'PPPK', 'PPPK Paruh Waktu' => 'bg-info text-info border-info',
                                        default => 'bg-secondary text-secondary border-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} bg-opacity-10 border border-opacity-25 rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.75rem;">
                                    {{ $item->jenis_pegawai }}
                                </span>
                            </td>

                            {{-- KOLOM AKSI (Diperbarui jadi bulat-bulat elegan) --}}
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('tampil-pegawai', ['id' => $item->id]) }}" class="btn-action btn-action-info" title="Detail Profil">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('edit-pegawai', $item->id) }}" class="btn-action btn-action-warning" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('hapus-pegawai', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama }} secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-action btn-action-danger" title="Hapus Data">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted d-flex flex-column justify-content-center align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-search fa-2x text-secondary opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold">Data tidak ditemukan</h6>
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
        @if($pegawai->hasPages() || $pegawai->total() > 0)
        <div class="card-footer bg-white border-top p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="text-muted small fw-semibold">
                    Menampilkan <span class="text-dark">{{ $pegawai->firstItem() ?? 0 }}</span> sampai <span class="text-dark">{{ $pegawai->lastItem() ?? 0 }}</span> dari total <span class="text-primary">{{ $pegawai->total() }}</span> pegawai
                </div>
                
                {{-- TAMBAHKAN CLASS 'custom-pagination-wrapper' DI SINI --}}
                <div class="custom-pagination-wrapper">
                    {{ $pegawai->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
                
            </div>
        </div>
        @endif
        
    </div>

</div>

@endsection