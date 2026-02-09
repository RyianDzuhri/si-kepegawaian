@extends('layouts.app')

{{-- 1. CSS KHUSUS (Tampilan Halus + Cropper) --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    /* === CSS TAMPILAN HALUS === */
    body, .form-control, .form-select, input, select, textarea {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }
    .form-control, .form-select {
        padding: 0.6rem 1rem; border-radius: 0.5rem; border: 1px solid #ced4da;
        font-size: 1rem; line-height: 1.5; color: #495057;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); border-color: #86b7fe;
    }
    input[type="date"] { position: relative; font-family: inherit; }
    
    .reveal-wrapper {
        max-height: 0; opacity: 0; overflow: hidden; transition: all 0.4s ease-in-out; transform: translateY(-10px);
    }
    .reveal-wrapper.show {
        max-height: 250px; /* Sedikit lebih tinggi utk menampung alert TMT */
        opacity: 1; transform: translateY(0); margin-top: 1rem;
    }
    .form-section {
        background: #fff; border-left: 5px solid #0d6efd; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
        border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.5rem; transition: transform 0.2s;
    }
    .form-section:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08); }
    .section-title { font-size: 1rem; font-weight: 700; color: #495057; margin-bottom: 1rem; display: flex; align-items: center; }
    .section-title i { margin-right: 0.75rem; color: #0d6efd; }

    /* === CSS KHUSUS CROPPER === */
    .img-container img { max-width: 100%; }
    .preview-crop {
        width: 120px; height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #0d6efd;
        background-color: #f8f9fa;
        display: flex; align-items: center; justify-content: center;
        color: #adb5bd; font-size: 0.8rem;
    }
    .cropper-view-box, .cropper-face { border-radius: 50%; }
</style>
@endpush

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Tambah Pegawai Baru</h4>
                    <p class="text-muted small mb-0">Isi formulir di bawah ini dengan data yang valid.</p>
                </div>
                <a href="{{ route('manajemen-pegawai') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4 p-md-5">

                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-circle fs-4 me-3 mt-1"></i>
                                <div>
                                    <strong>Perhatian! Mohon cek kembali inputan Anda:</strong>
                                    <ul class="mb-0 mt-1 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('simpan-pegawai') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableBtnSubmit(this)"> {{-- <--- TAMBAHKAN INI --}}>
                        @csrf

                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-id-card"></i> I. Identitas Pribadi
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">NIP / NRK</label>
                                    <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika Honorer" value="{{ old('nip') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">NIK (KTP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control" maxlength="16" required value="{{ old('nik') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small text-muted">Nama Lengkap (Beserta Gelar) <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control fw-bold" placeholder="Contoh: Dr. Budi Santoso, S.Kom, M.T." required value="{{ old('nama') }}">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" required value="{{ old('tempat_lahir') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir') }}">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Agama</label>
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
                                    <label class="form-label fw-semibold small text-muted">Status Pernikahan</label>
                                    <select name="status_pernikahan" class="form-select">
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Janda/Duda">Janda/Duda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-address-book"></i> II. Kontak & Foto
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Nomor HP / WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="no_hp" class="form-control border-start-0 ps-0" placeholder="08..." value="{{ old('no_hp') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>
                            
                            {{-- BAGIAN INPUT FOTO DENGAN PREVIEW CROP --}}
                            <div class="mb-0">
                                <label class="form-label fw-semibold small text-muted">Foto Profil (Wajib Crop)</label>
                                <div class="d-flex align-items-center gap-3 p-2 bg-light rounded border">
                                    
                                    <div class="preview-crop" id="box_preview">
                                        <small>Preview</small>
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <input type="file" id="upload_image" class="form-control mb-1" accept="image/*">
                                        <small class="text-muted d-block" style="font-size: 0.8rem;">
                                            <i class="fas fa-info-circle me-1"></i> Pilih foto lalu sesuaikan lingkaran.
                                        </small>
                                        <input type="hidden" name="foto_cropped" id="foto_cropped">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section border-left-warning">
                            <div class="section-title text-dark">
                                <i class="fas fa-briefcase"></i> III. Data Kepegawaian
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Status Kepegawaian <span class="text-danger">*</span></label>
                                    <select name="jenis_pegawai" id="jenis_pegawai" class="form-select bg-light" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="PNS" {{ old('jenis_pegawai') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="PPPK" {{ old('jenis_pegawai') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                        <option value="Honorer" {{ old('jenis_pegawai') == 'Honorer' ? 'selected' : '' }}>Honorer / Kontrak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Unit Kerja / Dinas <span class="text-danger">*</span></label>
                                    
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
                            </div>

                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div id="wrapper_sub_unit" class="reveal-wrapper">
                                        <div class="mb-3"> 
                                            <label class="form-label fw-semibold small text-muted text-primary fst-italic ms-1">
                                                <i class="fas fa-level-up-alt fa-rotate-90 me-1"></i> Pilih Sub Bagian:
                                            </label>
                                            <select id="unit_kerja_sub" class="form-select border-primary border-opacity-25 bg-light">
                                                <option value="">-- Pilih Sub Bagian --</option>
                                                <option value="Sub Bagian Umum Kepegawaian">Sub Bagian Umum Kepegawaian</option>
                                                <option value="Sub Bagian Perencanaan Keuangan dan Pelaporan">Sub Bagian Perencanaan Keuangan dan Pelaporan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" placeholder="Cth: Analis Kebijakan" required value="{{ old('jabatan') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="Cth: S1 Teknik Informatika" value="{{ old('pendidikan_terakhir') }}">
                                </div>
                            </div>

                            <div id="wrapper_golongan" class="reveal-wrapper">
                                <div class="p-3 bg-light rounded border border-secondary border-opacity-25 mb-3">
                                    <label class="form-label fw-bold text-dark">Pangkat / Golongan Ruang <span class="text-danger">*</span></label>
                                    <select name="golongan" id="golongan" class="form-select border-secondary border-opacity-25">
                                        <option value="">-- Pilih Golongan --</option>
                                    </select>
                                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i> Opsi golongan menyesuaikan status pegawai.</small>
                                </div>
                            </div>
                        </div>

                        {{-- === TMT SETTINGS (DIBUNGKUS ID wrapper_tmt) === --}}
                        <div id="wrapper_tmt" class="reveal-wrapper">
                            
                            <div class="alert alert-warning shadow-sm border-0 d-flex align-items-center mb-4 rounded-3">
                                <i class="fas fa-calendar-check fs-2 me-3 text-warning"></i>
                                <div>
                                    <strong class="text-dark">Pengaturan Tanggal TMT (Penting!)</strong><br>
                                    <span class="text-muted small">Data ini digunakan sistem untuk menghitung jadwal Kenaikan Pangkat & KGB.</span>
                                </div>
                            </div>

                            <div class="row g-3 mb-5">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">TMT Kenaikan Pangkat Terakhir</label>
                                    <input type="date" name="tmt_pangkat_terakhir" id="tmt_pangkat" class="form-control" 
                                           value="{{ old('tmt_pangkat_terakhir') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">TMT Gaji Berkala Terakhir</label>
                                    <input type="date" name="tmt_gaji_berkala_terakhir" id="tmt_gaji" class="form-control" 
                                           value="{{ old('tmt_gaji_berkala_terakhir') }}">
                                </div>
                            </div>

                        </div>
                        {{-- === END TMT === --}}
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light border px-4 py-2 fw-semibold text-muted">Reset</button>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Data
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Potong & Sesuaikan Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-dark">
                <div class="img-container" style="height: 500px; display: flex; align-items: center; justify-content: center;">
                    <img id="image_to_crop" src="" style="display: block; max-width: 100%; max-height: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary px-4" id="crop_button">
                    <i class="fas fa-cut me-2"></i>Potong Sekarang
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
        
        // --- CROPPER START ---
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
            previewBox.innerHTML = '<img src="' + base64data + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
            modal.hide();
        });
        // --- CROPPER END ---

        // ============================================
        // 2. LOGIKA GOLONGAN & TMT (UPDATE DISINI)
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

        // Variabel
        const savedGolongan = "{{ old('golongan') }}";
        const savedUnit = "{{ old('unit_kerja') }}";

        const jenisPegawai = document.getElementById('jenis_pegawai');
        const wrapperGolongan = document.getElementById('wrapper_golongan');
        const selectGolongan = document.getElementById('golongan');
        
        // Variabel TMT (BARU)
        const wrapperTMT = document.getElementById('wrapper_tmt');
        const inputTMTPangkat = document.getElementById('tmt_pangkat');
        const inputTMTGaji = document.getElementById('tmt_gaji');

        const unitUtama = document.getElementById('unit_kerja_utama');
        const unitSub = document.getElementById('unit_kerja_sub');
        const wrapperSub = document.getElementById('wrapper_sub_unit');
        const finalInputUnit = document.getElementById('final_unit_kerja');

        function updateGolongan() {
            const selected = jenisPegawai.value;
            selectGolongan.innerHTML = '<option value="">-- Pilih Golongan --</option>';

            // === JIKA HONORER / KOSONG ===
            if (selected === 'Honorer' || selected === '') {
                // Sembunyikan Golongan
                wrapperGolongan.classList.remove('show');
                selectGolongan.removeAttribute('required');

                // Sembunyikan TMT (INI LOGIKA BARUNYA)
                wrapperTMT.classList.remove('show');
                inputTMTPangkat.removeAttribute('required');
                inputTMTGaji.removeAttribute('required');
                
                return;
            }

            // === JIKA PNS / PPPK ===
            // Munculkan Golongan
            wrapperGolongan.classList.add('show');
            selectGolongan.setAttribute('required', 'required');

            // Munculkan TMT
            wrapperTMT.classList.add('show');
            inputTMTPangkat.setAttribute('required', 'required');
            inputTMTGaji.setAttribute('required', 'required');

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

        jenisPegawai.addEventListener('change', updateGolongan);
        unitUtama.addEventListener('change', updateUnitKerja);
        unitSub.addEventListener('change', function() { finalInputUnit.value = this.value; });
    });
</script>
@endpush