@extends('layouts.app')

@push('styles')
{{-- CSS TOM SELECT (Untuk Dropdown Search) --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

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

    /* === CUSTOM STYLE TOM SELECT (AGAR SERAGAM DENGAN THEMA KITA) === */
    .ts-control {
        border-radius: 0.5rem !important;
        padding: 0.7rem 1.2rem !important;
        background-color: #f8fafc !important;
        border: 1px solid #d1d5db !important;
        font-size: 0.95rem !important;
        min-height: calc(2.8rem + 2px);
        color: #334155;
        transition: all 0.2s ease;
        box-shadow: none !important;
    }
    .ts-control.focus {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
        border-color: #93c5fd !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e2e8f0 !important;
        overflow: hidden;
        margin-top: 5px;
    }
    .ts-dropdown .option { padding: 0.6rem 1.2rem; }
    .ts-dropdown .active {
        background-color: #eff6ff !important;
        color: #1e293b !important;
        font-weight: 600;
    }
    
    /* Section Title Styling */
    .form-section { margin-bottom: 2rem; }
    .section-title { 
        font-size: 1.1rem; 
        font-weight: 700; 
        color: #1e293b; 
        margin-bottom: 1.25rem; 
        display: flex; 
        align-items: center; 
    }
    .section-title .icon-box { 
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.9rem;
    }

    /* Animasi Buka Tutup Form yang Aman */
    .reveal-wrapper {
        max-height: 0; opacity: 0; overflow: hidden; 
        transition: max-height 0.5s ease-in-out, opacity 0.4s ease, transform 0.4s ease; 
        transform: translateY(-10px);
    }
    .reveal-wrapper.show {
        max-height: 1000px; opacity: 1; transform: translateY(0); 
    }

    /* UPLOAD AREA EKSKLUSIF */
    .upload-box {
        border: 2px dashed #cbd5e1;
        background-color: #f8fafc;
        border-radius: 1rem;
        transition: all 0.2s;
        cursor: pointer;
    }
    .upload-box:hover, .upload-box:focus-within { 
        border-color: #3b82f6; background-color: #eff6ff; 
    }
</style>
@endpush

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            
            {{-- HEADER HALAMAN --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Upload Dokumen SK</h4>
                    <p class="text-muted small mb-0">Arsipkan Surat Keputusan pegawai ke dalam sistem digital.</p>
                </div>
                <a href="{{ route('arsip-sk') }}" class="btn btn-white border shadow-sm rounded-pill px-3 fw-semibold text-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            {{-- KARTU UTAMA (SATU KESATUAN) --}}
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

                    <form action="{{ route('simpan-sk') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableBtnSubmit(this)">
                        @csrf
                        
                        {{-- BAGIAN 1: PILIH PEGAWAI --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fas fa-user-tie"></i></div> 
                                1. Identitas Pemilik SK
                            </div>
                            
                            @if(isset($selectedPegawai) && $selectedPegawai)
                                <div class="card bg-primary bg-opacity-10 border border-primary border-opacity-25 shadow-none rounded-4">
                                    <div class="card-body d-flex align-items-center p-3 p-md-4">
                                        <div class="bg-white p-3 rounded-circle shadow-sm me-3 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-check fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-primary mb-1" style="font-size: 1.1rem;">{{ $selectedPegawai->nama }}</h6>
                                            <div class="text-secondary small">
                                                <span class="badge bg-primary bg-opacity-75 rounded-pill me-1">{{ $selectedPegawai->jenis_pegawai }}</span> 
                                                {{ $selectedPegawai->jabatan }}
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="pegawai_id" value="{{ $selectedPegawai->id }}">
                                        <input type="hidden" id="current_jenis_pegawai" value="{{ $selectedPegawai->jenis_pegawai }}">
                                        
                                        <a href="{{ route('tambah-sk') }}" class="btn btn-light border rounded-circle shadow-sm text-danger ms-3 d-flex align-items-center justify-content-center transition-all hover-scale" style="width: 40px; height: 40px;" title="Ganti Pegawai">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{-- DROPDOWN PENCARIAN (TELAH DIUPGRADE MENGGUNAKAN TOM SELECT) --}}
                                <div class="mb-2">
                                    <label class="form-label fw-bold small text-secondary">Cari Pegawai <span class="text-danger">*</span></label>
                                    
                                    {{-- Hilangkan input-group bawaan agar UI TomSelect tampil sempurna --}}
                                    <select name="pegawai_id" id="pegawai_select" required placeholder="Ketik Nama atau NIP Pegawai...">
                                        <option value="">-- Ketik Nama atau NIP Pegawai --</option>
                                        @foreach ($pegawaiList as $pegawai)
                                            <option value="{{ $pegawai->id }}" data-jenis="{{ $pegawai->jenis_pegawai }}">
                                                {{ $pegawai->nama }} - {{ $pegawai->nip ? 'NIP. ' . $pegawai->nip : 'NIK. ' . $pegawai->nik }} ({{ $pegawai->jenis_pegawai }})
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <div class="form-text text-muted mt-2 small"><i class="fas fa-keyboard me-1"></i>Ketik nama pegawai untuk memfilter dengan cepat.</div>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        {{-- BAGIAN 2: DETAIL DOKUMEN --}}
                        <div class="form-section">
                            <div class="section-title">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="fas fa-file-signature"></i></div> 
                                2. Detail Dokumen SK
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Jenis SK <span class="text-danger">*</span></label>
                                    
                                    <select id="jenis_sk" name="jenis_sk" class="form-select border-warning border-opacity-50" required onchange="toggleInputs()">
                                        <option value="">-- Pilih Jenis SK --</option>
                                        <option value="SK Kenaikan Pangkat">SK Kenaikan Pangkat</option>
                                        <option value="SK Gaji Berkala">SK Gaji Berkala (KGB)</option>
                                        <option value="SK Jabatan">SK Jabatan / Mutasi</option>
                                        <option value="SK CPNS">SK CPNS</option>
                                        <option value="SK PNS">SK PNS (100%)</option>
                                        <option value="Lainnya">Lainnya (Ketik Manual)</option>
                                    </select>
                                    
                                    <input type="text" id="jenis_sk_lainnya" class="form-control mt-2 d-none border-warning" placeholder="Tuliskan nama SK...">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Nomor Surat <span class="text-danger">*</span></label>
                                    <input type="text" name="nomor_sk" class="form-control" placeholder="Cth: 800/123/BKPSDM/2026" required>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-secondary">Tanggal Surat</label>
                                    <input type="date" name="tanggal_sk" class="form-control" required>
                                    <small class="text-muted d-block mt-2" style="font-size: 0.75rem"><i class="fas fa-calendar-alt me-1"></i>Tgl. Penandatanganan SK</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-primary small">TMT Berlaku <span class="text-danger">*</span></label>
                                    <input type="date" name="tmt_sk" class="form-control border-primary bg-primary bg-opacity-10" required>
                                    <small class="text-primary d-block mt-2" style="font-size: 0.75rem"><i class="fas fa-flag-checkered me-1"></i>Tgl. Mulai Berlakunya SK</small>
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN 3: INPUT DINAMIS --}}
                        <div id="dynamic_inputs" class="reveal-wrapper">
                            
                            {{-- A. INPUT GOLONGAN --}}
                            <div id="wrapper_golongan" class="reveal-wrapper">
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-secondary">Naik ke Golongan Berapa?</label>
                                    <select name="golongan_baru" id="golongan_baru" class="form-select border-success">
                                        <option value="">-- Pilih Golongan Baru --</option>
                                    </select>
                                </div>
                            </div>

                            {{-- B. INPUT JABATAN --}}
                            <div id="wrapper_jabatan" class="reveal-wrapper">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-secondary">Nama Jabatan Baru</label>
                                        <input type="text" name="jabatan_baru" class="form-control border-success" placeholder="Cth: Kepala Bidang...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-secondary">Unit Kerja Baru</label>
                                        <input type="text" name="unit_kerja_baru" class="form-control border-success" placeholder="Cth: Dinas Kominfo...">
                                    </div>
                                </div>
                            </div>

                            {{-- C. CHECKBOX UPDATE --}}
                            <div class="form-check bg-light p-3 rounded-4 border d-flex align-items-center mb-4">
                                <input class="form-check-input fs-4 mt-0 border-success me-3 ms-1" type="checkbox" name="update_otomatis" value="1" id="autoUpdate" checked>
                                <label class="form-check-label d-block" for="autoUpdate" style="cursor: pointer;">
                                    <strong class="text-dark">Update Data Profil Pegawai</strong><br>
                                    <span class="text-muted small">Centang untuk otomatis memperbarui TMT, Golongan, atau Jabatan di Master Data Pegawai.</span>
                                </label>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        {{-- BAGIAN 4: UPLOAD FILE --}}
                        <div class="form-section mb-0">
                            <div class="section-title">
                                <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="fas fa-file-pdf"></i></div> 
                                3. File Dokumen
                            </div>
                            
                            <label for="file_sk" class="d-block mb-0">
                                <div class="upload-box p-4 p-md-5 text-center">
                                    <div class="mb-3">
                                        <div class="bg-white shadow-sm d-inline-block p-3 rounded-circle text-primary">
                                            <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                        </div>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2">Klik untuk Memilih File SK</h6>
                                    <p class="text-muted small mb-0">Mendukung format PDF, JPG, atau PNG. (Maksimal 5MB)</p>
                                    
                                    <input type="file" name="file_sk" id="file_sk" class="form-control mt-3 d-none" accept=".pdf,.jpg,.jpeg,.png" required onchange="showFileName(this)">
                                    
                                    <div id="file_name_display" class="mt-3 badge bg-primary bg-opacity-10 text-primary fs-6 d-none px-3 py-2 rounded-pill"></div>
                                </div>
                            </label>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-3 mt-5 pt-3 border-top">
                            <button type="reset" class="btn btn-light border rounded-pill px-5 py-2 fw-semibold text-secondary">Reset Form</button>
                            <button type="submit" id="btnSubmit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Dokumen SK
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
{{-- JS TOM SELECT (Library Dropdown Search) --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
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

    function getJenisPegawai() {
        const selectEl = document.getElementById('pegawai_select');
        if (selectEl) {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            return selectedOption ? selectedOption.getAttribute('data-jenis') : '';
        } else {
            const hiddenEl = document.getElementById('current_jenis_pegawai');
            return hiddenEl ? hiddenEl.value : '';
        }
    }

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
        const selectEl = document.getElementById('jenis_sk');
        const jenisSK = selectEl.value;
        const inputLainnya = document.getElementById('jenis_sk_lainnya');
        const jenisPegawai = getJenisPegawai();
        
        if (jenisSK === 'Lainnya') {
            inputLainnya.classList.remove('d-none');
            inputLainnya.setAttribute('required', 'required');
            inputLainnya.setAttribute('name', 'jenis_sk');
            selectEl.removeAttribute('name');
        } else {
            inputLainnya.classList.add('d-none');
            inputLainnya.removeAttribute('required');
            inputLainnya.removeAttribute('name');
            selectEl.setAttribute('name', 'jenis_sk');
        }

        const container = document.getElementById('dynamic_inputs');
        const wrapperGol = document.getElementById('wrapper_golongan');
        const wrapperJab = document.getElementById('wrapper_jabatan');
        
        container.classList.remove('show');
        wrapperGol.classList.remove('show');
        wrapperJab.classList.remove('show');

        if (!jenisPegawai) return;

        setTimeout(() => {
            if (jenisSK === 'SK Kenaikan Pangkat') {
                if (jenisPegawai === 'PNS' || jenisPegawai === 'PPPK') {
                    container.classList.add('show');
                    wrapperGol.classList.add('show');
                    renderGolongan(jenisPegawai); 
                }
            } 
            else if (jenisSK === 'SK Jabatan') {
                container.classList.add('show');
                wrapperJab.classList.add('show');
            } 
            else if (['SK Gaji Berkala', 'SK PNS', 'SK CPNS'].includes(jenisSK)) {
                if (jenisSK === 'SK Gaji Berkala' && (jenisPegawai === 'Honorer' || jenisPegawai === 'PPPK Paruh Waktu')) {
                    // Sembunyikan
                } else {
                    container.classList.add('show');
                }
            }
        }, 150); 
    }

    function showFileName(input) {
        const display = document.getElementById('file_name_display');
        if(input.files && input.files[0]) {
            display.innerHTML = '<i class="fas fa-file-check me-2"></i>' + input.files[0].name;
            display.classList.remove('d-none');
            display.classList.add('d-inline-block');
        } else {
            display.classList.add('d-none');
            display.classList.remove('d-inline-block');
        }
    }

    function disableBtnSubmit(form) {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengupload...';
        btn.setAttribute('disabled', 'disabled');
        return true;
    }

    // Initialize ketika halaman dimuat
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Inisialisasi Dropdown TomSelect (Fitur Pencarian)
        if(document.getElementById('pegawai_select')) {
            new TomSelect("#pegawai_select",{
                create: false,
                sortField: { field: "text", direction: "asc" },
                onChange: function(value) {
                    toggleInputs(); // Panggil fungsi toggle saat ada nama yg dipilih
                }
            });
        }

        // 2. Cek default onload
        if (document.getElementById('jenis_sk').value !== "") {
            toggleInputs();
        }
    });
</script>
@endpush