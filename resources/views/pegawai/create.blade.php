@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Tambah Pegawai Baru</h4>
                <a href="{{ route('manajemen-pegawai') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-user-plus me-2"></i>Formulir Data Pegawai
                    </h6>
                </div>

                <div class="card-body p-4">

                    {{-- error umum --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('simpan-pegawai') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3"
                            style="font-size: 0.8rem; letter-spacing: 1px;">
                            Identitas Diri
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">NIP *</label>
                                <input type="text" name="nip"
                                    class="form-control @error('nip') is-invalid @enderror"
                                    value="{{ old('nip') }}">
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir"
                                    class="form-control"
                                    value="{{ old('tempat_lahir') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    class="form-control"
                                    value="{{ old('tanggal_lahir') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Profil</label>
                            <input type="file"
                                name="foto_profil"
                                class="form-control @error('foto_profil') is-invalid @enderror">
                            <small class="text-muted">
                                Format JPG/PNG. Ukuran maksimal 2 MB.
                            </small>
                            @error('foto_profil')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4"
                            style="font-size: 0.8rem; letter-spacing: 1px;">
                            Data Jabatan & Pangkat
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Kepegawaian</label>
                                <select name="jenis_pegawai" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    <option value="PNS" {{ old('jenis_pegawai') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="PPPK" {{ old('jenis_pegawai') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                    <option value="Honorer" {{ old('jenis_pegawai') == 'Honorer' ? 'selected' : '' }}>Honorer / Kontrak</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="jabatan"
                                    class="form-control"
                                    value="{{ old('jabatan') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Golongan</label>
                                <select name="golongan" class="form-select">
                                    <option value="">-- Tidak Ada --</option>
                                    <option value="II/a" {{ old('golongan') == 'II/a' ? 'selected' : '' }}>II/a</option>
                                    <option value="III/a" {{ old('golongan') == 'III/a' ? 'selected' : '' }}>III/a</option>
                                    <option value="IV/a" {{ old('golongan') == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pendidikan Terakhir</label>
                                <input type="text"
                                    name="pendidikan_terakhir"
                                    class="form-control"
                                    value="{{ old('pendidikan_terakhir') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border">Reset</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Data
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
