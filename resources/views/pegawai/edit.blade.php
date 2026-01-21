@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Edit Data Pegawai</h4>
                <a href="{{ url('/pegawai') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Batal
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning bg-opacity-10 py-3">
                    <h6 class="mb-0 text-dark"><i class="fas fa-edit me-2"></i>Perbarui Informasi Pegawai</h6>
                </div>
                <div class="card-body p-4">
                    
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT') <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3" style="font-size: 0.8rem;">Identitas Diri</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $pegawai->nip }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="{{ $pegawai->nama }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ $pegawai->tempat_lahir }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ $pegawai->tanggal_lahir }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="L" {{ $pegawai->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $pegawai->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Ganti Foto Profil (Opsional)</label>
                            <input type="file" name="foto_profil" class="form-control">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                        </div>

                        <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4" style="font-size: 0.8rem;">Data Jabatan & Pangkat</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Kepegawaian</label>
                                <select name="jenis_pegawai" class="form-select" required>
                                    <option value="PNS" {{ $pegawai->jenis_pegawai == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="PPPK" {{ $pegawai->jenis_pegawai == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                    <option value="Honorer" {{ $pegawai->jenis_pegawai == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jabatan Saat Ini</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ $pegawai->jabatan }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Golongan</label>
                                <select name="golongan" class="form-select">
                                    <option value="">-- Tidak Ada --</option>
                                    <option value="II/a" {{ $pegawai->golongan == 'II/a' ? 'selected' : '' }}>II/a</option>
                                    <option value="III/a" {{ $pegawai->golongan == 'III/a' ? 'selected' : '' }}>III/a</option>
                                    </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $pegawai->pendidikan_terakhir }}">
                            </div>
                        </div>

                        <div class="row mb-4 bg-light p-3 rounded">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat_terakhir" class="form-control" value="{{ $pegawai->tmt_pangkat_terakhir }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Gaji Berkala Terakhir</label>
                                <input type="date" name="tmt_gaji_berkala_terakhir" class="form-control" value="{{ $pegawai->tmt_gaji_berkala_terakhir }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection