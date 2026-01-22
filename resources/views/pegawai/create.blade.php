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
                    <h6 class="mb-0 text-primary"><i class="fas fa-user-plus me-2"></i>Formulir Data Pegawai</h6>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('simpan-pegawai') }}" method="POST" enctype="multipart/form-data">
                        @csrf <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Identitas Diri</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text" name="nip" class="form-control" placeholder="Contoh: 19850101 201001 1 001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama beserta gelar" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Profil</label>
                            <input type="file" name="foto_profil" class="form-control">
                            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB.</small>
                        </div>

                        <h6 class="text-uppercase text-muted border-bottom pb-2 mb-3 mt-4" style="font-size: 0.8rem; letter-spacing: 1px;">Data Jabatan & Pangkat</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Kepegawaian</label>
                                <select name="jenis_pegawai" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="PNS">PNS</option>
                                    <option value="PPPK">PPPK</option>
                                    <option value="Honorer">Honorer / Kontrak</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jabatan Saat Ini</label>
                                <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Pranata Komputer Ahli Pertama" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Golongan (Opsional)</label>
                                <select name="golongan" class="form-select">
                                    <option value="">-- Tidak Ada --</option>
                                    <option value="II/a">II/a - Pengatur Muda</option>
                                    <option value="II/b">II/b - Pengatur Muda Tk. I</option>
                                    <option value="III/a">III/a - Penata Muda</option>
                                    <option value="III/b">III/b - Penata Muda Tk. I</option>
                                    <option value="IV/a">IV/a - Pembina</option>
                                    </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="Contoh: S1 Teknik Informatika">
                            </div>
                        </div>

                        <div class="alert alert-info mt-4 d-flex">
                            <i class="fas fa-bell me-3 mt-1"></i>
                            <div>
                                <strong>Penting untuk Notifikasi:</strong><br>
                                Masukkan tanggal TMT (Terhitung Mulai Tanggal) terakhir agar sistem bisa mengingatkan jadwal Kenaikan Pangkat & Gaji Berkala berikutnya.
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Kenaikan Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat_terakhir" class="form-control">
                                <small class="text-muted">Kosongkan jika pegawai baru.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Gaji Berkala Terakhir</label>
                                <input type="date" name="tmt_gaji_berkala_terakhir" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border">Reset Form</button>
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