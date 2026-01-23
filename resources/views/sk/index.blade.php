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
        
        <form action="{{ url()->current() }}" method="GET">
            <div class="row g-2 mb-4 bg-light p-3 rounded align-items-end">
                
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Cari Kata Kunci</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" class="form-control" 
                               placeholder="NIP, Nama, atau Nomor SK..." 
                               value="{{ request('q') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Kategori SK</label>
                    <select name="jenis_sk" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach(['SK CPNS', 'SK Kenaikan Pangkat', 'SK Gaji Berkala', 'SK Jabatan'] as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis_sk') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tahun SK</label>
                    <input type="number" name="tahun" class="form-control" 
                           placeholder="Contoh: 2024" 
                           value="{{ request('tahun') }}">
                </div>

                <div class="col-md-2">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Terapkan
                        </button>
                        
                        @if(request()->anyFilled(['q', 'jenis_sk', 'tahun']))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                        @endif
                    </div>
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
                    @forelse ($suratKeputusan as $index => $sk)
                        <tr>
                            <td>{{ $suratKeputusan->firstItem() + $index }}</td>
                            <td>
                                @if($sk->pegawai)
                                    <div class="fw-bold">{{ $sk->pegawai->nama }}</div>
                                    <small class="text-muted">{{ $sk->pegawai->nip }}</small>
                                @else
                                    <span class="text-danger fst-italic small">Data Pegawai Terhapus</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1">{{ $sk->jenis_sk }}</span><br>
                                <small class="text-dark">No: {{ $sk->nomor_sk }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($sk->tanggal_sk)->isoFormat('D MMM YYYY') }}
                            </td>
                            <td>
                                @if($sk->file_sk)
                                    <div class="d-flex align-items-center text-danger">
                                        <i class="fas fa-file-pdf fa-lg me-2"></i>
                                        <small class="text-truncate" style="max-width: 150px;" title="{{ basename($sk->file_sk) }}">
                                            {{ basename($sk->file_sk) }}
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($sk->file_sk)
                                        <a href="{{ asset('storage/' . $sk->file_sk) }}" target="_blank" class="btn btn-sm btn-dark" title="Download">
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-2"><i class="fas fa-folder-open fa-3x opacity-50"></i></div>
                                <h5>Belum ada dokumen SK ditemukan.</h5>
                                <small>Coba ubah kata kunci pencarian atau upload SK baru.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            {{ $suratKeputusan->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection