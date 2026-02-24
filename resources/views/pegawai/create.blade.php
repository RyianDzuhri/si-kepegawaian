@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    /* === CSS TAMPILAN MODERN & HALUS === */
    body, .form-control, .form-select, input, select, textarea {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif !important;
    }
    
    /* Input Styling */
    .form-control, .form-select {
        padding: 0.7rem 1.2rem; 
        border-radius: 0.5rem; 
        border: 1px solid #d1d5db;
        font-size: 0.95rem; 
        color: #334155;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); 
        border-color: #93c5fd;
    }
    .form-control::placeholder { color: #94a3b8; }
    
    /* Section Card Styling */
    .form-section {
        background: #ffffff; 
        border: 1px solid #e2e8f0;
        border-radius: 1rem; 
        padding: 2rem; 
        margin-bottom: 2rem; 
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .form-section:hover { 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); 
    }
    .section-title { 
        font-size: 1.1rem; 
        font-weight: 700; 
        color: #1e293b; 
        margin-bottom: 1.5rem; 
        display: flex; 
        align-items: center; 
        padding-bottom: 0.75rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .section-title .icon-box { 
        background: #eff6ff;
        color: #3b82f6;
        width: 35px; height: 35px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 0.75rem;
    }

    /* Animasi Buka Tutup Form yang Aman */
    .reveal-wrapper {
        max-height: 0; 
        opacity: 0; 
        overflow: hidden; 
        transition: max-height 0.5s ease-in-out, opacity 0.4s ease, transform 0.4s ease; 
        transform: translateY(-10px);
    }
    .reveal-wrapper.show {
        max-height: 1500px; /* Angka diperbesar agar aman dan tidak memotong form */
        opacity: 1; 
        transform: translateY(0); 
        margin-top: 1rem;
    }

    /* === CSS CROPPER & UPLOAD FOTO EKSKLUSIF === */
    .img-container img { max-width: 100%; }
    .upload-box {
        border: 2px dashed #cbd5e1;
        background-color: #f8fafc;
        border-radius: 1rem;
        transition: all 0.2s;
    }
    .upload-box:hover { border-color: #94a3b8; background-color: #f1f5f9; }
    .preview-crop {
        width: 110px; height: 110px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        background-color: #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8;
    }
    .cropper-view-box, .cropper-face { 
        border-radius: 50%; 
    }
</style>
@endpush

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- HEADER HALAMAN --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Tambah Pegawai Baru</h4>
                    <p class="text-muted mb-0">Isi formulir di bawah ini dengan data yang lengkap dan valid.</p>
                </div>
                <a href="{{ route('manajemen-pegawai') }}" class="btn btn-white border shadow-sm rounded-pill px-3 fw-semibold text-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    {{-- ALERT ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4 d-flex align-items-start p-4">
                            <i class="fas fa-exclamation-circle fs-3 me-3 text-danger mt-1"></i>
                            <div>
                                <strong class="d-block mb-2 text-danger">Perhatian! Mohon cek kembali inputan Anda:</strong>
                                <ul class="mb-0 ps-3 small text-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('simpan-pegawai') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableBtnSubmit(this)">
                        @csrf

                        {{-- BAGIAN I: IDENTITAS --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-box"><i class="fas fa-id-card"></i></div> 
                                I. Identitas Pribadi
                            </div>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">NIP / NRK</label>
                                    <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika Honorer" value="{{ old('nip') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">NIK (KTP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control" maxlength="16" placeholder="16 Digit NIK" required value="{{ old('nik') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-secondary">Nama Lengkap (Beserta Gelar) <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control fw-semibold text-dark" placeholder="Contoh: Dr. Budi Santoso, S.Kom., M.T." required value="{{ old('nama') }}">
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota/Kabupaten" required value="{{ old('tempat_lahir') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir') }}">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary">Agama</label>
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
                                    <label class="form-label fw-bold small text-secondary">Status Pernikahan</label>
                                    <select name="status_pernikahan" class="form-select">
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Janda/Duda">Janda/Duda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN II: KONTAK & FOTO --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fas fa-address-book"></i></div> 
                                II. Kontak & Foto
                            </div>
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Nomor HP / WhatsApp</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="no_hp" class="form-control border-start-0 ps-0 bg-white" placeholder="08..." value="{{ old('no_hp') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Alamat Email</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0 bg-white" placeholder="email@contoh.com" value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- UPLOAD FOTO PROFIL MODERN --}}
                            <div class="mb-0">
                                <label class="form-label fw-bold small text-secondary">Foto Profil</label>
                                <div class="upload-box p-4 d-flex flex-column flex-sm-row align-items-center gap-4">
                                    <div class="preview-crop shadow-sm" id="box_preview">
                                        <i class="fas fa-user text-opacity-50 text-secondary fs-1"></i>
                                    </div>
                                    <div class="text-center text-sm-start flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Unggah Foto Wajah</h6>
                                        <p class="text-muted small mb-3">Format JPG/PNG, ukuran ideal rasio 1:1.</p>
                                        {{-- Tombol Custom Pengganti Input File Biasa --}}
                                        <label for="upload_image" class="btn btn-primary rounded-pill px-4 shadow-sm" style="cursor: pointer;">
                                            <i class="fas fa-cloud-upload-alt me-2"></i> Pilih Foto
                                        </label>
                                        <input type="file" id="upload_image" class="d-none" accept="image/*">
                                        <input type="hidden" name="foto_cropped" id="foto_cropped">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN III: DATA KEPEGAWAIAN --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fas fa-briefcase"></i></div> 
                                III. Data Kepegawaian
                            </div>
                            
                            <div class="row g-4">
                                {{-- === KOLOM KIRI === --}}
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-secondary">Status Kepegawaian <span class="text-danger">*</span></label>
                                        <select name="jenis_pegawai" id="jenis_pegawai" class="form-select border-primary" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="PNS" {{ old('jenis_pegawai') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                            <option value="PPPK" {{ old('jenis_pegawai') == 'PPPK' ? 'selected' : '' }}>PPPK (Penuh Waktu)</option>
                                            <option value="PPPK Paruh Waktu" {{ old('jenis_pegawai') == 'PPPK Paruh Waktu' ? 'selected' : '' }}>PPPK Paruh Waktu</option>
                                            <option value="Honorer" {{ old('jenis_pegawai') == 'Honorer' ? 'selected' : '' }}>Honorer / Kontrak</option>
                                        </select>
                                    </div>

                                    <div id="wrapper_tmt_pengangkatan" class="reveal-wrapper">
                                        <div class="p-3 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25">
                                            <label class="form-label fw-bold text-primary small mb-1">TMT Pengangkatan (Awal Karir) <span class="text-danger">*</span></label>
                                            <input type="date" name="tmt_pengangkatan" id="input_tmt_pengangkatan"
                                                   class="form-control bg-white border-primary border-opacity-50" 
                                                   value="{{ old('tmt_pengangkatan') }}">
                                            <div class="form-text text-primary opacity-75 small mt-2 lh-sm">
                                                <i class="fas fa-info-circle me-1"></i> Tanggal pertama diangkat (CPNS/PPPK/Awal Kontrak).
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- === KOLOM KANAN === --}}
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-secondary">Unit Kerja / Dinas <span class="text-danger">*</span></label>
                                        <input type="hidden" name="unit_kerja" id="final_unit_kerja" value="{{ old('unit_kerja') }}">
                                        <select id="unit_kerja_utama" class="form-select" required>
                                            <option value="">-- Pilih Bidang/Bagian --</option>
                                            <option value="Kepala Dinas">Kepala Dinas</option>
                                            <option value="Sekretaris Dinas">Sekretaris Dinas</option>
                                            <option value="Sekretariat">Sekretariat</option> 
                                            <option value="Bidang E-Government">Bidang E-Government</option>
                                            <option value="Bidang Infokom">Bidang Infokom</option>
                                            <option value="Bidang TIK">Bidang TIK</option>
                                        </select>
                                    </div>

                                    <div id="wrapper_sub_unit" class="reveal-wrapper">
                                        <div class="p-3 bg-light rounded-4 border"> 
                                            <label class="form-label fw-bold small text-secondary mb-2">
                                                <i class="fas fa-sitemap me-2 text-muted"></i>Sub Bagian (Khusus Sekretariat):
                                            </label>
                                            <select id="unit_kerja_sub" class="form-select bg-white">
                                                <option value="">-- Pilih Sub Bagian --</option>
                                                <option value="Sub Bagian Umum Kepegawaian">Sub Bagian Umum Kepegawaian</option>
                                                <option value="Sub Bagian Perencanaan Keuangan dan Pelaporan">Sub Bagian Perencanaan Keuangan dan Pelaporan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 text-muted opacity-25">

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" placeholder="Cth: Analis Kebijakan" required value="{{ old('jabatan') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="Cth: S1 Teknik Informatika" value="{{ old('pendidikan_terakhir') }}">
                                </div>
                            </div>

                            <div id="wrapper_golongan" class="reveal-wrapper">
                                <div class="mb-1">
                                    <label class="form-label fw-bold text-dark mb-2">Pangkat / Golongan Ruang <span class="text-danger">*</span></label>
                                    <select name="golongan" id="golongan" class="form-select w-50" style="min-width: 250px;">
                                        <option value="">-- Pilih Golongan --</option>
                                    </select>
                                    <small class="text-muted mt-2 d-block">Menyesuaikan dengan Status Kepegawaian.</small>
                                </div>
                            </div>
                        </div>

                        {{-- === BAGIAN IV: JADWAL KENAIKAN (PANGKAT & GAJI) === --}}
                        <div id="wrapper_tmt" class="reveal-wrapper">
                            
                            {{-- ALERT PANDUAN BIRU LEMBUT (Bukan Kuning Warning lagi) --}}
                            <div class="bg-info bg-opacity-10 border border-info border-opacity-25 rounded-4 p-3 d-flex align-items-center mb-4">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm flex-shrink-0" style="width: 45px; height: 45px;">
                                    <i class="fas fa-calendar-check fs-5"></i>
                                </div>
                                <div>
                                    <strong class="text-dark d-block mb-1">Panduan Pengisian Jadwal</strong>
                                    <span class="text-muted small">TMT (Terhitung Mulai Tanggal) di bawah ini digunakan oleh sistem untuk <b>memprediksi otomatis</b> jadwal kenaikan pangkat dan gaji selanjutnya.</span>
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-4 shadow-sm h-100">
                                        <label class="form-label fw-bold small text-secondary">TMT Kenaikan Pangkat Terakhir</label>
                                        <input type="date" name="tmt_pangkat_terakhir" id="tmt_pangkat" class="form-control bg-light" 
                                               value="{{ old('tmt_pangkat_terakhir') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-4 shadow-sm h-100">
                                        <label class="form-label fw-bold small text-secondary">TMT Gaji Berkala Terakhir</label>
                                        <input type="date" name="tmt_gaji_berkala_terakhir" id="tmt_gaji" class="form-control bg-light" 
                                               value="{{ old('tmt_gaji_berkala_terakhir') }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-3 mt-5 pt-3 border-top">
                            <button type="reset" class="btn btn-light border rounded-pill px-5 py-2 fw-semibold text-secondary">Reset Form</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Data Pegawai
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CROPPER --}}
<div class="modal fade" id="modalCrop" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 px-4 py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-crop-alt me-2 text-primary"></i>Potong & Sesuaikan Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-dark">
                <div class="img-container" style="height: 450px; display: flex; align-items: center; justify-content: center;">
                    <img id="image_to_crop" src="" style="display: block; max-width: 100%; max-height: 100%;">
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="crop_button">
                    <i class="fas fa-check me-2"></i>Gunakan Foto
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // ============================================
        // 1. CROPPER LOGIC (FOTO PROFIL) - TIDAK DIUBAH
        // ============================================
        var inputImage = document.getElementById('upload_image');
        var modalElement = document.getElementById('modalCrop');
        var modal = new bootstrap.Modal(modalElement);
        var image = document.getElementById('image_to_crop');
        var cropper;

        inputImage.addEventListener('change', function(e) {
            var files = e.target.files;
            var done = function(url) {
                inputImage.value = ''; 
                image.src = url;
                modal.show();
            };
            if (files && files.length > 0) {
                var reader = new FileReader();
                reader.onload = function(event) { done(reader.result); };
                reader.readAsDataURL(files[0]);
            }
        });

        modalElement.addEventListener('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1, 
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        });

        modalElement.addEventListener('hidden.bs.modal', function() {
            if(cropper) { cropper.destroy(); cropper = null; }
        });

        document.getElementById('crop_button').addEventListener('click', function() {
            var canvas = cropper.getCroppedCanvas({ width: 500, height: 500 });
            var base64data = canvas.toDataURL('image/png');
            document.getElementById('foto_cropped').value = base64data;
            var previewBox = document.getElementById('box_preview');
            // Menghilangkan padding & border saat foto sudah ada
            previewBox.style.padding = '0';
            previewBox.style.border = 'none';
            previewBox.innerHTML = '<img src="' + base64data + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            modal.hide();
        });


        // ============================================
        // 2. LOGIKA FORM DINAMIS (STATUS, GOLONGAN, TMT) - TIDAK DIUBAH
        // ============================================
        const dataPNS = {
            "Golongan I (Juru)": [{val:"I/a",text:"I/a - Juru Muda"},{val:"I/b",text:"I/b - Juru Muda Tk. I"},{val:"I/c",text:"I/c - Juru"},{val:"I/d",text:"I/d - Juru Tk. I"}],
            "Golongan II (Pengatur)": [{val:"II/a",text:"II/a - Pengatur Muda"},{val:"II/b",text:"II/b - Pengatur Muda Tk. I"},{val:"II/c",text:"II/c - Pengatur"},{val:"II/d",text:"II/d - Pengatur Tk. I"}],
            "Golongan III (Penata)": [{val:"III/a",text:"III/a - Penata Muda"},{val:"III/b",text:"III/b - Penata Muda Tk. I"},{val:"III/c",text:"III/c - Penata"},{val:"III/d",text:"III/d - Penata Tk. I"}],
            "Golongan IV (Pembina)": [{val:"IV/a",text:"IV/a - Pembina"},{val:"IV/b",text:"IV/b - Pembina Tk. I"},{val:"IV/c",text:"IV/c - Pembina Utama Muda"},{val:"IV/d",text:"IV/d - Pembina Utama Madya"},{val:"IV/e",text:"IV/e - Pembina Utama"}]
        };
        const dataPPPK = [
            {val:"I",text:"I - Setara SD"},{val:"II",text:"II - Lanjutan SD"},{val:"III",text:"III - Lanjutan"},{val:"IV",text:"IV - Setara SMP"},
            {val:"V",text:"V - Setara SMA"},{val:"VI",text:"VI - Setara D1"},{val:"VII",text:"VII - Setara D3"},{val:"VIII",text:"VIII - Lanjutan D3"},
            {val:"IX",text:"IX - Setara S1"},{val:"X",text:"X - Setara S2"},{val:"XI",text:"XI - Setara S3"},{val:"XII",text:"XII - Ahli Madya"},
            {val:"XIII",text:"XIII - Ahli Madya"},{val:"XIV",text:"XIV - Ahli Utama"},{val:"XV",text:"XV - Ahli Utama"},{val:"XVI",text:"XVI - Ahli Utama"},{val:"XVII",text:"XVII - Ahli Utama"}
        ];
        const subBagianList = ["Sub Bagian Umum Kepegawaian", "Sub Bagian Perencanaan Keuangan dan Pelaporan"];

        // Element Selectors
        const savedGolongan = "{{ old('golongan') }}";
        const savedUnit = "{{ old('unit_kerja') }}";
        const jenisPegawai = document.getElementById('jenis_pegawai');
        
        // Wrappers
        const wrapperTmtPengangkatan = document.getElementById('wrapper_tmt_pengangkatan');
        const inputTmtPengangkatan = document.getElementById('input_tmt_pengangkatan');
        const wrapperGolongan = document.getElementById('wrapper_golongan');
        const selectGolongan = document.getElementById('golongan');
        const wrapperTMT = document.getElementById('wrapper_tmt');
        const inputTMTPangkat = document.getElementById('tmt_pangkat');
        const inputTMTGaji = document.getElementById('tmt_gaji');

        // Unit Kerja Selectors
        const unitUtama = document.getElementById('unit_kerja_utama');
        const unitSub = document.getElementById('unit_kerja_sub');
        const wrapperSub = document.getElementById('wrapper_sub_unit');
        const finalInputUnit = document.getElementById('final_unit_kerja');

        function updateGolongan() {
            const selected = jenisPegawai.value;
            selectGolongan.innerHTML = '<option value="">-- Pilih Golongan --</option>';

            // A. JIKA HONORER / KOSONG
            if (selected === 'Honorer' || selected === '') {
                wrapperGolongan.classList.remove('show');
                selectGolongan.removeAttribute('required');

                if (selected === 'Honorer') {
                    wrapperTmtPengangkatan.classList.add('show');
                    inputTmtPengangkatan.setAttribute('required', 'required');
                } else {
                    wrapperTmtPengangkatan.classList.remove('show');
                    inputTmtPengangkatan.removeAttribute('required');
                }

                wrapperTMT.classList.remove('show');
                inputTMTPangkat.removeAttribute('required');
                inputTMTGaji.removeAttribute('required');
                
                return;
            }

            // B. JIKA PPPK PARUH WAKTU
            if (selected === 'PPPK Paruh Waktu') {
                wrapperTmtPengangkatan.classList.add('show');
                inputTmtPengangkatan.setAttribute('required', 'required');

                wrapperGolongan.classList.remove('show');
                selectGolongan.removeAttribute('required');

                wrapperTMT.classList.remove('show');
                inputTMTPangkat.removeAttribute('required');
                inputTMTGaji.removeAttribute('required');
                return;
            }

            // C. JIKA PNS / PPPK BIASA
            wrapperTmtPengangkatan.classList.add('show');
            inputTmtPengangkatan.setAttribute('required', 'required');

            wrapperGolongan.classList.add('show');
            selectGolongan.setAttribute('required', 'required');
            
            wrapperTMT.classList.add('show');
            inputTMTPangkat.setAttribute('required', 'required'); 
            inputTMTGaji.setAttribute('required', 'required');

            // Isi Dropdown
            if (selected === 'PNS') {
                for (const [groupLabel, items] of Object.entries(dataPNS)) {
                    let optgroup = document.createElement('optgroup');
                    optgroup.label = groupLabel;
                    items.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.val; option.textContent = item.text;
                        if (item.val === savedGolongan) option.selected = true;
                        optgroup.appendChild(option);
                    });
                    selectGolongan.appendChild(optgroup);
                }
            } else if (selected === 'PPPK') {
                dataPPPK.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.val; option.textContent = "Golongan " + item.text;
                    if (item.val === savedGolongan) option.selected = true;
                    selectGolongan.appendChild(option);
                });
            }
        }

        function updateUnitKerja() {
            const val = unitUtama.value;
            if (val === 'Sekretariat') {
                wrapperSub.classList.add('show');
                unitSub.setAttribute('required', 'required');
                finalInputUnit.value = unitSub.value; 
            } else {
                wrapperSub.classList.remove('show');
                unitSub.removeAttribute('required');
                unitSub.value = "";
                finalInputUnit.value = val; 
            }
        }

        // Jalankan saat load
        if(jenisPegawai.value !== "") { updateGolongan(); }
        
        if (savedUnit) {
            if (subBagianList.includes(savedUnit)) {
                unitUtama.value = "Sekretariat";
                unitSub.value = savedUnit; 
                wrapperSub.classList.add('show'); 
            } else {
                unitUtama.value = savedUnit;
            }
            finalInputUnit.value = savedUnit;
        }

        // Event Listeners
        jenisPegawai.addEventListener('change', updateGolongan);
        unitUtama.addEventListener('change', updateUnitKerja);
        unitSub.addEventListener('change', function() { finalInputUnit.value = this.value; });
    });
</script>
@endpush