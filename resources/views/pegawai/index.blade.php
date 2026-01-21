@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Data Pegawai</h3>
        <p class="text-muted mb-0">Manajemen data seluruh pegawai aktif.</p>
    </div>
    <a href="{{ url('/pegawai/create') }}" class="btn btn-primary">
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
                    <tr>
                        <td>1</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <div class="fw-bold text-dark">Budi Santoso, S.Kom</div>
                                    <small class="text-muted d-block">NIP: 19850101 201001 1 001</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">Pranata Komputer</div>
                            <small class="text-secondary">Penata Muda (III/a)</small>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">PNS</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ url('/pegawai/detail') }}" class="btn btn-sm btn-outline-info" title="Detail & SK">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-warning" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=random" class="rounded-circle me-3" width="40" height="40">
                                <div>
                                    <div class="fw-bold text-dark">Siti Aminah, S.Pd</div>
                                    <small class="text-muted d-block">NIP: -</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">Staf Administrasi</div>
                            <small class="text-secondary">-</small>
                        </td>
                        <td>
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Honorer</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ url('/pegawai/detail') }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>

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