@extends('layouts.app')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Arsip Digital SK</h3>
        <p class="text-muted mb-0">Cari, kelola, dan unduh dokumen SK pegawai.</p>
    </div>
    <a href="{{ route('tambah-sk') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-cloud-upload-alt me-2"></i>Upload SK Baru
    </a>
</div>

{{-- CARD UTAMA --}}
<div class="card shadow-sm border-0 mb-5">
    <div class="card-body">
        
        {{-- FORM FILTER PENCARIAN --}}
        <form action="{{ url()->current() }}" method="GET">
            <div class="row mb-4 g-2">
                
                {{-- 1. Cari Kata Kunci --}}
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" class="form-control" 
                               placeholder="Cari NIP, Nama, atau No. SK..." 
                               value="{{ request('q') }}">
                    </div>
                </div>

                {{-- 2. Filter Kategori --}}
                <div class="col-md-3">
                    <select name="jenis_sk" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach(['SK CPNS', 'SK Kenaikan Pangkat', 'SK Gaji Berkala', 'SK Jabatan'] as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis_sk') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Filter Tahun --}}
                <div class="col-md-2">
                    <input type="number" name="tahun" class="form-control" 
                           placeholder="Tahun (Cth: 2024)" 
                           value="{{ request('tahun') }}">
                </div>

                {{-- 4. Tombol Filter --}}
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100" title="Terapkan Filter">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>

                {{-- 5. Tombol Reset --}}
                @if(request()->anyFilled(['q', 'jenis_sk', 'tahun']))
                <div class="col-md-2">
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
                @endif
            </div>
        </form>

        {{-- TABEL DATA --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Pemilik SK (Pegawai)</th>
                        <th width="25%">Detail Dokumen</th>
                        <th width="15%">Tanggal SK</th>
                        <th width="10%">File</th>
                        <th width="15%" class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratKeputusan as $index => $sk)
                        <tr>
                            <td>{{ $suratKeputusan->firstItem() + $index }}</td>
                            
                            {{-- KOLOM PEGAWAI (TANPA PP/AVATAR) --}}
                            <td>
                                @if($sk->pegawai)
                                    <div>
                                        <div class="fw-bold text-dark">{{ $sk->pegawai->nama }}</div>
                                        <small class="text-muted d-block">{{ $sk->pegawai->nip ?? 'Non-ASN' }}</small>
                                    </div>
                                @else
                                    <div class="text-danger small fst-italic">
                                        <i class="fas fa-user-slash me-1"></i> Pegawai Terhapus
                                    </div>
                                @endif
                            </td>

                            {{-- KOLOM DETAIL SK --}}
                            <td>
                                @php
                                    $badgeColor = match($sk->jenis_sk) {
                                        'SK Kenaikan Pangkat' => 'bg-warning text-dark',
                                        'SK Gaji Berkala' => 'bg-success text-white',
                                        'SK Jabatan' => 'bg-info text-dark',
                                        'SK CPNS' => 'bg-primary text-white',
                                        default => 'bg-secondary text-white'
                                    };
                                @endphp
                                <span class="badge {{ $badgeColor }} bg-opacity-75 mb-1">{{ $sk->jenis_sk }}</span>
                                <div class="small fw-bold text-dark text-truncate" style="max-width: 250px;" title="{{ $sk->nomor_sk }}">
                                    No: {{ $sk->nomor_sk }}
                                </div>
                            </td>

                            {{-- KOLOM TANGGAL --}}
                            <td>
                                <div class="text-dark">
                                    {{ \Carbon\Carbon::parse($sk->tanggal_sk)->isoFormat('D MMM Y') }}
                                </div>
                                <small class="text-muted">
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
                            <td class="text-end pe-3">
                                @if($sk->file_sk)
                                    <a href="{{ asset('storage/' . $sk->file_sk) }}" target="_blank" class="btn btn-sm btn-outline-dark shadow-sm">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-light border text-muted" disabled>No File</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-folder-open fa-3x opacity-25"></i></div>
                                <h6 class="fw-bold">Tidak ada dokumen ditemukan.</h6>
                                <small>Coba ubah kata kunci atau filter pencarian Anda.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $suratKeputusan->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection