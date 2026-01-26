@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Edit Data Pegawai</h4>
                <a href="{{ route('manajemen-pegawai') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning bg-opacity-10 py-3">
                    <h6 class="mb-0 text-dark"><i class="fas fa-edit me-2"></i>Perbarui Informasi Pegawai</h6>
                </div>
                
                <div class="card-body p-4">
                    
                    {{-- Tampilkan Error Validasi Global --}}
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

                    <form action="{{ route('update-pegawai', ['id' => $pegawai->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT') 

                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">I. Identitas Pribadi</h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIP / NRK</label>
                                    <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika Honorer" value="{{ old('nip', $pegawai->nip) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIK (KTP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control" maxlength="16" required value="{{ old('nik', $pegawai->nik) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Nama Lengkap (Beserta Gelar) <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" required value="{{ old('nama', $pegawai->nama) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" required value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Agama</label>
                                    <select name="agama" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                            <option value="{{ $agama }}" {{ old('agama', $pegawai->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status Pernikahan</label>
                                    <select name="status_pernikahan" class="form-select">
                                        <option value="Belum Menikah" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                        <option value="Menikah" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                        <option value="Janda/Duda" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">II. Kontak & Foto</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor HP / WhatsApp</label>
                                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pegawai->no_hp) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $pegawai->email) }}">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Foto Profil</label>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($pegawai->foto_profil)
                                            <img src="{{ asset('storage/' . $pegawai->foto_profil) }}" class="img-thumbnail rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px;">No Pic</div>
                                        @endif
                                        
                                        <div class="flex-grow-1">
                                            <input type="file" name="foto_profil" class="form-control">
                                            <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah foto. Format JPG/PNG Maks 2MB.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">III. Data Kepegawaian</h6>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status Kepegawaian <span class="text-danger">*</span></label>
                                    <select name="jenis_pegawai" class="form-select" required>
                                        <option value="PNS" {{ old('jenis_pegawai', $pegawai->jenis_pegawai) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="PPPK" {{ old('jenis_pegawai', $pegawai->jenis_pegawai) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                        <option value="Honorer" {{ old('jenis_pegawai', $pegawai->jenis_pegawai) == 'Honorer' ? 'selected' : '' }}>Honorer / Kontrak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Unit Kerja / Dinas <span class="text-danger">*</span></label>
                                    <input type="text" name="unit_kerja" class="form-control" required value="{{ old('unit_kerja', $pegawai->unit_kerja) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" required value="{{ old('jabatan', $pegawai->jabatan) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Pangkat / Golongan Ruang</label>
                                    <select name="golongan" class="form-select">
                                        <option value="">-- Tidak Ada (Untuk Honorer) --</option>
                                        <optgroup label="Golongan I (Juru)">
                                            @foreach(['I/a', 'I/b', 'I/c', 'I/d'] as $g)
                                                <option value="{{ $g }}" {{ old('golongan', $pegawai->golongan) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Golongan II (Pengatur)">
                                            @foreach(['II/a', 'II/b', 'II/c', 'II/d'] as $g)
                                                <option value="{{ $g }}" {{ old('golongan', $pegawai->golongan) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Golongan III (Penata)">
                                            @foreach(['III/a', 'III/b', 'III/c', 'III/d'] as $g)
                                                <option value="{{ $g }}" {{ old('golongan', $pegawai->golongan) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Golongan IV (Pembina)">
                                            @foreach(['IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'] as $g)
                                                <option value="{{ $g }}" {{ old('golongan', $pegawai->golongan) == $g ? 'selected' : '' }}>{{ $g }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                            <div>
                                <strong>Pengaturan Tanggal TMT (Penting!):</strong><br>
                                Ubah data ini hanya jika ada kesalahan input atau penyesuaian manual. Idealnya data ini diupdate otomatis lewat Upload SK.
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Kenaikan Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat_terakhir" class="form-control" required value="{{ old('tmt_pangkat_terakhir', $pegawai->tmt_pangkat_terakhir) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">TMT Gaji Berkala Terakhir</label>
                                <input type="date" name="tmt_gaji_berkala_terakhir" class="form-control" required value="{{ old('tmt_gaji_berkala_terakhir', $pegawai->tmt_gaji_berkala_terakhir) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-4 btn-lg">
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