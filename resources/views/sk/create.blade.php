@extends('layouts.app')

@push('styles')
<style>
    /* === SERAGAMKAN FONT === */
    body {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }
    .form-control, .form-select, select, option, optgroup, .btn, h4, h6, label, p, small, .alert, input, textarea {
        font-family: inherit !important;
    }

    .form-control, .form-select {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        border-color: #86b7fe;
    }
    .transition-all { transition: all 0.3s ease-in-out; }
    .btn-white { background: #fff; }
    .btn-white:hover { background: #f8f9fa; }
    .upload-area { border: 2px dashed #dee2e6; transition: border-color 0.3s; }
    .upload-area:hover { border-color: #0d6efd; background-color: #f8f9fa; }
</style>
@endpush

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- HEADER HALAMAN --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Upload Dokumen SK</h4>
                    <p class="text-muted small mb-0">Arsipkan Surat Keputusan pegawai di sini.</p>
                </div>
                <a href="{{ route('arsip-sk') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- ALERT ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fs-4 me-3"></i>
                                <div>
                                    <strong>Perhatian!</strong> Mohon periksa inputan berikut:
                                    <ul class="mb-0 mt-1 ps-3 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('simpan-sk') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableBtnSubmit(this)">
                        @csrf
                        
                        {{-- BAGIAN 1: PILIH PEGAWAI --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-2">1. Identitas Pemilik SK</label>
                            
                            @if(isset($selectedPegawai) && $selectedPegawai)
                                {{-- Jika pegawai sudah dipilih dari halaman sebelumnya --}}
                                <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 shadow-sm">
                                    <div class="card-body d-flex align-items-center p-3">
                                        <div class="bg-white p-2 rounded-circle shadow-sm me-3 text-primary">
                                            <i class="fas fa-user-check fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-primary mb-0">{{ $selectedPegawai->nama }}</h6>
                                            <small class="text-muted">
                                                <strong>{{ $selectedPegawai->jenis_pegawai }}</strong> 
                                                &bull; {{ $selectedPegawai->jabatan }}
                                            </small>
                                        </div>
                                        
                                        {{-- HIDDEN INPUT UNTUK ID & JENIS PEGAWAI --}}
                                        <input type="hidden" name="pegawai_id" value="{{ $selectedPegawai->id }}">
                                        <input type="hidden" id="current_jenis_pegawai" value="{{ $selectedPegawai->jenis_pegawai }}">
                                        
                                        <a href="{{ route('tambah-sk') }}" class="btn btn-sm btn-white border text-danger ms-2" title="Ganti Pegawai">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{-- Jika akses langsung, tampilkan Dropdown Pencarian --}}
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                    <select name="pegawai_id" id="pegawai_select" class="form-select form-select-lg bg-light" required onchange="toggleInputs()">
                                        <option value="" data-jenis="">-- Cari Nama Pegawai --</option>
                                        @foreach ($pegawaiList as $pegawai)
                                            {{-- SIMPAN JENIS PEGAWAI DI DATA ATTRIBUTE --}}
                                            <option value="{{ $pegawai->id }}" data-jenis="{{ $pegawai->jenis_pegawai }}">
                                                {{ $pegawai->nama }} - {{ $pegawai->jenis_pegawai }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-text mt-1"><i class="fas fa-info-circle me-1"></i>Silakan cari nama pegawai yang bersangkutan.</div>
                            @endif
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        {{-- BAGIAN 2: DETAIL DOKUMEN --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-3">2. Detail Dokumen SK</label>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Jenis SK <span class="text-danger">*</span></label>
                                    <select name="jenis_sk" id="jenis_sk" class="form-select" required onchange="toggleInputs()">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="SK Kenaikan Pangkat">SK Kenaikan Pangkat</option>
                                        <option value="SK Gaji Berkala">SK Gaji Berkala (KGB)</option>
                                        <option value="SK Jabatan">SK Jabatan / Mutasi</option>
                                        <option value="SK CPNS">SK CPNS</option>
                                        <option value="SK PNS">SK PNS (100%)</option>
                                        <option value="Lainnya">Lainnya (Izin/Tugas/dll)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_sk" class="form-control" placeholder="No. SK..." required>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Tanggal Surat</label>
                                    <input type="date" name="tanggal_sk" class="form-control" required>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem">Tgl. Penandatanganan SK</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-primary">TMT Berlaku <span class="text-danger">*</span></label>
                                    <input type="date" name="tmt_sk" class="form-control border-primary bg-primary bg-opacity-10" required>
                                    <small class="text-primary d-block mt-1" style="font-size: 0.75rem"><strong>Penting:</strong> Tgl. Mulai Berlakunya SK</small>
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN 3: INPUT DINAMIS (Muncul Sesuai Jenis SK) --}}
                        <div id="dynamic_inputs" class="card bg-light border-0 mb-4 d-none transition-all">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-magic me-2"></i>Update Otomatis Data Pegawai
                                </h6>
                                
                                {{-- A. INPUT GOLONGAN (Hanya utk Kenaikan Pangkat PNS & PPPK) --}}
                                <div id="input_golongan" class="row mb-3 d-none">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Naik ke Golongan Berapa?</label>
                                        <select name="golongan_baru" id="golongan_baru" class="form-select border-success">
                                            <option value="">-- Pilih Golongan Baru --</option>
                                            {{-- Opsi akan di-generate via Javascript --}}
                                        </select>
                                    </div>
                                </div>

                                {{-- B. INPUT JABATAN (Hanya utk SK Jabatan) --}}
                                <div id="input_jabatan" class="row g-3 mb-3 d-none">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Jabatan Baru</label>
                                        <input type="text" name="jabatan_baru" class="form-control border-success" placeholder="Cth: Kepala Bidang...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Unit Kerja Baru</label>
                                        <input type="text" name="unit_kerja_baru" class="form-control border-success" placeholder="Cth: Dinas Kominfo...">
                                    </div>
                                </div>

                                {{-- C. CHECKBOX UPDATE (Muncul untuk semua jenis yg relevan) --}}
                                <div class="form-check bg-white p-3 rounded border">
                                    <input class="form-check-input" type="checkbox" name="update_otomatis" value="1" id="autoUpdate" checked>
                                    <label class="form-check-label fw-bold text-dark" for="autoUpdate">
                                        Update Data Profil Pegawai?
                                    </label>
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        Sistem akan otomatis memperbarui <strong>TMT, Golongan, atau Jabatan</strong> pada data pegawai.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN 4: UPLOAD FILE --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">3. File Dokumen</label>
                            <div class="upload-area p-4 text-center border rounded bg-white">
                                <div class="mb-3">
                                    <div class="bg-light d-inline-block p-3 rounded-circle text-secondary">
                                        <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-1">Upload File SK</h6>
                                <p class="text-muted small mb-3">Format PDF atau Gambar (JPG/PNG). Maks 5MB.</p>
                                
                                <input type="file" name="file_sk" id="file_sk" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="btnSubmit" class="btn btn-primary py-3 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Dokumen
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Data List Golongan
    const dataPNS = {
        "Golongan I (Juru)": [{val:"I/a",text:"I/a - Juru Muda"},{val:"I/b",text:"I/b - Juru Muda Tk. I"},{val:"I/c",text:"I/c - Juru"},{val:"I/d",text:"I/d - Juru Tk. I"}],
        "Golongan II (Pengatur)": [{val:"II/a",text:"II/a - Pengatur Muda"},{val:"II/b",text:"II/b - Pengatur Muda Tk. I"},{val:"II/c",text:"II/c - Pengatur"},{val:"II/d",text:"II/d - Pengatur Tk. I"}],
        "Golongan III (Penata)": [{val:"III/a",text:"III/a - Penata Muda"},{val:"III/b",text:"III/b - Penata Muda Tk. I"},{val:"III/c",text:"III/c - Penata"},{val:"III/d",text:"III/d - Penata Tk. I"}],
        "Golongan IV (Pembina)": [{val:"IV/a",text:"IV/a - Pembina"},{val:"IV/b",text:"IV/b - Pembina Tk. I"},{val:"IV/c",text:"IV/c - Pembina Utama Muda"},{val:"IV/d",text:"IV/d - Pembina Utama Madya"},{val:"IV/e",text:"IV/e - Pembina Utama"}]
    };
    const dataPPPK = [
        {val:"I",text:"I"},{val:"II",text:"II"},{val:"III",text:"III"},{val:"IV",text:"IV"},
        {val:"V",text:"V"},{val:"VI",text:"VI"},{val:"VII",text:"VII"},{val:"VIII",text:"VIII"},
        {val:"IX",text:"IX"},{val:"X",text:"X"},{val:"XI",text:"XI"},{val:"XII",text:"XII"},
        {val:"XIII",text:"XIII"},{val:"XIV",text:"XIV"},{val:"XV",text:"XV"},{val:"XVI",text:"XVI"},{val:"XVII",text:"XVII"}
    ];

    // Fungsi membaca Jenis Pegawai yang terpilih
    function getJenisPegawai() {
        const selectEl = document.getElementById('pegawai_select');
        if (selectEl) {
            // Jika via dropdown pencarian
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            return selectedOption ? selectedOption.getAttribute('data-jenis') : '';
        } else {
            // Jika via hidden input (Pre-selected detail pegawai)
            const hiddenEl = document.getElementById('current_jenis_pegawai');
            return hiddenEl ? hiddenEl.value : '';
        }
    }

    // Fungsi Render Golongan ke Select HTML
    function renderGolongan(jenisPegawai) {
        const selectGolongan = document.getElementById('golongan_baru');
        selectGolongan.innerHTML = '<option value="">-- Pilih Golongan Baru --</option>';

        if (jenisPegawai === 'PNS') {
            for (const [groupLabel, items] of Object.entries(dataPNS)) {
                let optgroup = document.createElement('optgroup');
                optgroup.label = groupLabel;
                items.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.val; option.textContent = item.text;
                    optgroup.appendChild(option);
                });
                selectGolongan.appendChild(optgroup);
            }
        } else if (jenisPegawai === 'PPPK') {
            let optgroup = document.createElement('optgroup');
            optgroup.label = "PPPK (Golongan I - XVII)";
            dataPPPK.forEach(item => {
                let option = document.createElement('option');
                option.value = item.val; option.textContent = "Golongan " + item.text;
                optgroup.appendChild(option);
            });
            selectGolongan.appendChild(optgroup);
        }
    }

    function toggleInputs() {
        const jenisSK = document.getElementById('jenis_sk').value;
        const jenisPegawai = getJenisPegawai();
        
        const container = document.getElementById('dynamic_inputs');
        const inputGol = document.getElementById('input_golongan');
        const inputJab = document.getElementById('input_jabatan');
        
        // Reset (Sembunyikan Semua)
        container.classList.add('d-none');
        inputGol.classList.add('d-none');
        inputJab.classList.add('d-none');

        // Jika user belum pilih pegawai, jangan proses
        if (!jenisPegawai) return;

        // === LOGIKA TAMPILAN ===
        
        // 1. Kenaikan Pangkat
        if (jenisSK === 'SK Kenaikan Pangkat') {
            // HANYA MUNCUL JIKA PNS ATAU PPPK PENUH WAKTU
            if (jenisPegawai === 'PNS' || jenisPegawai === 'PPPK') {
                container.classList.remove('d-none');
                inputGol.classList.remove('d-none');
                renderGolongan(jenisPegawai); // Load dropdown sesuai jenis
            }
        } 
        // 2. Mutasi Jabatan -> Butuh Input Jabatan & Unit Kerja
        else if (jenisSK === 'SK Jabatan') {
            container.classList.remove('d-none');
            inputJab.classList.remove('d-none');
        } 
        // 3. KGB / CPNS / PNS -> Cuma butuh Update TMT (Cukup Checkbox)
        else if (['SK Gaji Berkala', 'SK PNS', 'SK CPNS'].includes(jenisSK)) {
            // KGB tidak berlaku untuk Honorer & Paruh Waktu
            if (jenisSK === 'SK Gaji Berkala' && (jenisPegawai === 'Honorer' || jenisPegawai === 'PPPK Paruh Waktu')) {
                container.classList.add('d-none');
            } else {
                container.classList.remove('d-none');
            }
        }
    }

    // Fungsi Mencegah Double Submit
    function disableBtnSubmit(form) {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengupload...';
        btn.setAttribute('disabled', 'disabled');
        return true;
    }

    // Jalankan saat load jika ada nilai default
    window.onload = function() {
        if (document.getElementById('jenis_sk').value !== "") {
            toggleInputs();
        }
    };
</script>
@endpush