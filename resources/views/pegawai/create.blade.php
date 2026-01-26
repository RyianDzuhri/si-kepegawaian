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
                        <i class="fas fa-user-plus me-2"></i>Formulir Data Pegawai Lengkap
                    </h6>
                </div>

                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Perhatian! Ada input yang kurang pas:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('simpan-pegawai') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">I. Identitas Pribadi</h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIP / NRK</label>
                                    <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika Honorer" value="{{ old('nip') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIK (KTP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control" maxlength="16" required value="{{ old('nik') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Nama Lengkap (Beserta Gelar) <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Dr. Budi Santoso, S.Kom, M.T." required value="{{ old('nama') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" required value="{{ old('tempat_lahir') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Agama</label>
                                    <select name="agama" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status Pernikahan</label>
                                    <select name="status_pernikahan" class="form-select">
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Janda/Duda">Janda/Duda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">II. Kontak & Foto</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor HP / WhatsApp</label>
                                    <input type="text" name="no_hp" class="form-control" placeholder="08..." value="{{ old('no_hp') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" name="foto_profil" class="form-control">
                                <small class="text-muted">Format JPG/PNG. Maks 2MB.</small>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">III. Data Kepegawaian</h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status Kepegawaian <span class="text-danger">*</span></label>
                                    <select name="jenis_pegawai" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="PNS" {{ old('jenis_pegawai') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="PPPK" {{ old('jenis_pegawai') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                        <option value="Honorer" {{ old('jenis_pegawai') == 'Honorer' ? 'selected' : '' }}>Honorer / Kontrak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Unit Kerja / Dinas <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_kerja" class="form-control" placeholder="Cth: Dinas Kominfo / Bidang Aplikasi" required value="{{ old('unit_kerja') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" placeholder="Cth: Analis Kebijakan Ahli Muda" required value="{{ old('jabatan') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="Cth: S1 Teknik Informatika" value="{{ old('pendidikan_terakhir') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Pangkat / Golongan Ruang</label>
                                    <select name="golongan" class="form-select">
                                        <option value="">-- Tidak Ada (Untuk Honorer) --</option>
                                        <optgroup label="Golongan I (Juru)">
                                            <option value="I/a">I/a - Juru Muda</option>
                                            <option value="I/b">I/b - Juru Muda Tk. I</option>
                                            <option value="I/c">I/c - Juru</option>
                                            <option value="I/d">I/d - Juru Tk. I</option>
                                        </optgroup>
                                        <optgroup label="Golongan II (Pengatur)">
                                            <option value="II/a">II/a - Pengatur Muda</option>
                                            <option value="II/b">II/b - Pengatur Muda Tk. I</option>
                                            <option value="II/c">II/c - Pengatur</option>
                                            <option value="II/d">II/d - Pengatur Tk. I</option>
                                        </optgroup>
                                        <optgroup label="Golongan III (Penata)">
                                            <option value="III/a">III/a - Penata Muda</option>
                                            <option value="III/b">III/b - Penata Muda Tk. I</option>
                                            <option value="III/c">III/c - Penata</option>
                                            <option value="III/d">III/d - Penata Tk. I</option>
                                        </optgroup>
                                        <optgroup label="Golongan IV (Pembina)">
                                            <option value="IV/a">IV/a - Pembina</option>
                                            <option value="IV/b">IV/b - Pembina Tk. I</option>
                                            <option value="IV/c">IV/c - Pembina Utama Muda</option>
                                            <option value="IV/d">IV/d - Pembina Utama Madya</option>
                                            <option value="IV/e">IV/e - Pembina Utama</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                            <div>
                                <strong>Pengaturan Tanggal TMT (Penting!):</strong><br>
                                Data ini digunakan sistem untuk menghitung jadwal Kenaikan Pangkat & Gaji Berkala otomatis.
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Kenaikan Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat_terakhir" class="form-control" required value="{{ old('tmt_pangkat_terakhir') }}">
                                <small class="text-muted">Lihat di SK Pangkat terakhir.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Gaji Berkala Terakhir</label>
                                <input type="date" name="tmt_gaji_berkala_terakhir" class="form-control" required value="{{ old('tmt_gaji_berkala_terakhir') }}">
                                <small class="text-muted">Lihat di SK KGB terakhir.</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border">Reset Form</button>
                            <button type="submit" class="btn btn-primary px-4 btn-lg">
                                <i class="fas fa-save me-2"></i>Simpan Data Pegawai
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection