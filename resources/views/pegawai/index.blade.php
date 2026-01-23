@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Data Pegawai</h3>
        <p class="text-muted mb-0">Manajemen data seluruh pegawai aktif.</p>
    </div>
    <a href="{{ route('tambah-pegawai') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Pegawai
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        
        <div class="row mb-4 g-2">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari NIP atau Nama...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Semua Jabatan</option>
                    <option value="PNS">PNS</option>
                    <option value="Honorer">Honorer/Kontrak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-dark w-100">Filter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="35%">Pegawai</th>
                        <th width="25%">Jabatan & Golongan</th>
                        <th width="15%">Status</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawai as $item)
                    <tr>
                        <td>1</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/'. $item->foto_profil) }}" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <div class="fw-bold text-dark">{{ $item->nama }}</div>
                                    <small class="text-muted d-block">NIP: {{ $item->nip }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->jabatan }}</div>
                            <small class="text-secondary">{{ $item->golongan }}</small>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">{{ $item->jenis_pegawai }}</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('tampil-pegawai', ['id' => $item->id]) }}" class="btn btn-sm btn-outline-info" title="Detail & SK">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('hapus-pegawai', $item->id) }}" 
                                    method="POST" 
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>

    </div>
</div>

@endsection