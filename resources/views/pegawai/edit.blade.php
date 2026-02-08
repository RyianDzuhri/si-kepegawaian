@extends('layouts.app')

@section('content')

<style>
    body, .form-control, .form-select, input, select, textarea {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    .form-control, .form-select {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
    }

    input[type="date"] {
        position: relative;
        font-family: inherit;
    }

    /* Mengubah warna 'mm/dd/yyyy' agar tidak terlalu pudar (mirip dropdown) */
    ::-webkit-datetime-edit-fields-wrapper {
        font-family: inherit;
    }
    ::-webkit-datetime-edit-text {
        color: #6c757d; /* Warna garis miring / */
    }
    ::-webkit-datetime-edit-year-field,
    ::-webkit-datetime-edit-month-field,
    ::-webkit-datetime-edit-day-field {
        color: #6c757d; /* Samakan warna placeholder mm/dd/yyyy dengan placeholder input lain */
    }
    
    /* Jika input date sedang di-klik/focus */
    input[type="date"]:focus::-webkit-datetime-edit-year-field,
    input[type="date"]:focus::-webkit-datetime-edit-month-field,
    input[type="date"]:focus::-webkit-datetime-edit-day-field {
        color: #495057; /* Warna lebih gelap saat diketik */
    }
    /* Transisi halus untuk input yang muncul/hilang */
    .reveal-wrapper {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.4s ease-in-out;
        transform: translateY(-10px);
    }
    
    .reveal-wrapper.show {
        max-height: 150px; /* Sesuaikan tinggi maksimal */
        opacity: 1;
        transform: translateY(0);
        margin-top: 1rem; /* Jarak atas saat muncul */
    }

    /* Styling Section Form agar tidak kaku */
    .form-section {
        background: #fff;
        border-left: 5px solid #0d6efd; /* Aksen biru di kiri */
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }
    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
    }

    /* Input Field yang lebih modern */
    .form-control, .form-select {
        padding: 0.6rem 1rem; /* Lebih lega */
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); /* Glow halus */
        border-color: #86b7fe;
    }

    /* Judul Section */
    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .section-title i {
        margin-right: 0.75rem;
        color: #0d6efd;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Edit Data Pegawai</h4>
                    <p class="text-muted small mb-0">Perbarui informasi pegawai di bawah ini.</p>
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
                                    <strong>Perhatian! Ada input yang kurang pas:</strong>
                                    <ul class="mb-0 mt-1 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('update-pegawai', ['id' => $pegawai->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT') 

                        <div class="form-section">
                            <div class="section-title">
                                <i class="fas fa-id-card"></i> I. Identitas Pribadi
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">NIP / NRK</label>
                                    <input type="text" name="nip" class="form-control" placeholder="Kosongkan jika Honorer" value="{{ old('nip', $pegawai->nip) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">NIK (KTP) <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control" maxlength="16" required value="{{ old('nik', $pegawai->nik) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small text-muted">Nama Lengkap (Beserta Gelar) <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control fw-bold" required value="{{ old('nama', $pegawai->nama) }}">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" required value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir) }}">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Agama</label>
                                    <select name="agama" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                            <option value="{{ $agama }}" {{ old('agama', $pegawai->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">Status Pernikahan</label>
                                    <select name="status_pernikahan" class="form-select">
                                        <option value="Belum Menikah" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                        <option value="Menikah" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                        <option value="Janda/Duda" {{ old('status_pernikahan', $pegawai->status_pernikahan) == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda</option>
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
                                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pegawai->no_hp) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Alamat Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $pegawai->email) }}">
                                </div>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-semibold small text-muted">Foto Profil</label>
                                <div class="d-flex align-items-center gap-3 p-2 bg-light rounded border">
                                    @if($pegawai->foto_profil)
                                        <img src="{{ asset('storage/' . $pegawai->foto_profil) }}" class="rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center text-secondary fw-bold" style="width: 60px; height: 60px; font-size: 0.8rem;">No Pic</div>
                                    @endif
                                    
                                    <div class="flex-grow-1">
                                        <input type="file" name="foto_profil" class="form-control form-control-sm">
                                        <small class="text-muted d-block mt-1 fst-italic" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah foto.</small>
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
                                        <option value="PNS" {{ old('jenis_pegawai', $pegawai->jenis_pegawai) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="PPPK" {{ old('jenis_pegawai', $pegawai->jenis_pegawai) == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                                        <option value="Honorer" {{ old('jenis_pegawai', $pegawai->jenis_pegawai) == 'Honorer' ? 'selected' : '' }}>Honorer / Kontrak</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Unit Kerja / Dinas <span class="text-danger">*</span></label>
                                    
                                    <input type="hidden" name="unit_kerja" id="final_unit_kerja" value="{{ old('unit_kerja', $pegawai->unit_kerja) }}">

                                    <select id="unit_kerja_utama" class="form-select" required>
                                        <option value="">-- Pilih Bidang/Bagian --</option>
                                        <option value="Kepala Dinas">Kepala Dinas</option>
                                        <option value="Sekretaris Dinas">Sekretaris Dinas</option>
                                        <option value="Sekretariat">Sekretariat</option>
                                        <option value="Bidang E-Government">Bidang E-Government</option>
                                        <option value="Bidang Infokom">Bidang Infokom</option>
                                        <option value="Bidang TIK">Bidang TIK</option>
                                        <option value="Bagian Kepegawaian">Bagian Kepegawaian</option>
                                        <option value="Bagian Perencanaan">Bagian Perencanaan</option>
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
                                    <input type="text" name="jabatan" class="form-control" required value="{{ old('jabatan', $pegawai->jabatan) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-muted">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) }}">
                                </div>
                            </div>

                            <div id="wrapper_golongan" class="reveal-wrapper">
                                <div class="p-3 bg-light rounded border border-secondary border-opacity-25 mb-3">
                                    <label class="form-label fw-bold text-dark">Pangkat / Golongan Ruang <span class="text-danger">*</span></label>
                                    <select name="golongan" id="golongan" class="form-select border-secondary border-opacity-25">
                                        <option value="">-- Pilih Golongan --</option>
                                        </select>
                                    <small class="text-muted d-block mt-1"><i class="fas fa-info-circle me-1"></i> Sesuaikan jika ada kenaikan pangkat.</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning shadow-sm border-0 d-flex align-items-center mb-4 rounded-3">
                            <i class="fas fa-calendar-check fs-2 me-3 text-warning"></i>
                            <div>
                                <strong class="text-dark">Pengaturan Tanggal TMT</strong><br>
                                <span class="text-muted small">Update data ini hanya jika ada perubahan TMT (Kenaikan Pangkat/KGB).</span>
                            </div>
                        </div>

                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">TMT Kenaikan Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat_terakhir" class="form-control" required value="{{ old('tmt_pangkat_terakhir', $pegawai->tmt_pangkat_terakhir) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">TMT Gaji Berkala Terakhir</label>
                                <input type="date" name="tmt_gaji_berkala_terakhir" class="form-control" required value="{{ old('tmt_gaji_berkala_terakhir', $pegawai->tmt_gaji_berkala_terakhir) }}">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT PENGENDALI LOGIKA (GOLONGAN & UNIT KERJA) --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. DATA MASTER ---
        const dataPNS = {
            "Golongan I (Juru)": [{val:"I/a",text:"I/a - Juru Muda"},{val:"I/b",text:"I/b - Juru Muda Tk. I"},{val:"I/c",text:"I/c - Juru"},{val:"I/d",text:"I/d - Juru Tk. I"}],
            "Golongan II (Pengatur)": [{val:"II/a",text:"II/a - Pengatur Muda"},{val:"II/b",text:"II/b - Pengatur Muda Tk. I"},{val:"II/c",text:"II/c - Pengatur"},{val:"II/d",text:"II/d - Pengatur Tk. I"}],
            "Golongan III (Penata)": [{val:"III/a",text:"III/a - Penata Muda"},{val:"III/b",text:"III/b - Penata Muda Tk. I"},{val:"III/c",text:"III/c - Penata"},{val:"III/d",text:"III/d - Penata Tk. I"}],
            "Golongan IV (Pembina)": [{val:"IV/a",text:"IV/a - Pembina"},{val:"IV/b",text:"IV/b - Pembina Tk. I"},{val:"IV/c",text:"IV/c - Pembina Utama Muda"},{val:"IV/d",text:"IV/d - Pembina Utama Madya"},{val:"IV/e",text:"IV/e - Pembina Utama"}]
        };
        const dataPPPK = [
            {val:"I",text:"I - Setara SD"},{val:"II",text:"II - Lanjutan SD"},{val:"III",text:"III - Lanjutan"},{val:"IV",text:"IV - Setara SMP"},
            {val:"V",text:"V - Setara SMA"},{val:"VI",text:"VI - Setara D1/D2"},{val:"VII",text:"VII - Setara D3"},{val:"VIII",text:"VIII - Lanjutan D3"},
            {val:"IX",text:"IX - Setara S1"},{val:"X",text:"X - Setara S2"},{val:"XI",text:"XI - Setara S3"},{val:"XII",text:"XII - Ahli Madya"},
            {val:"XIII",text:"XIII - Ahli Madya"},{val:"XIV",text:"XIV - Ahli Utama"},{val:"XV",text:"XV - Ahli Utama"},{val:"XVI",text:"XVI - Ahli Utama"},{val:"XVII",text:"XVII - Ahli Utama"}
        ];
        const subBagianList = ["Sub Bagian Umum Kepegawaian", "Sub Bagian Perencanaan Keuangan dan Pelaporan"];

        // --- 2. VARIABEL & ELEMEN ---
        // Penting: Mengambil data existing dari server via Blade
        const savedGolongan = "{{ old('golongan', $pegawai->golongan) }}";
        const savedUnit = "{{ old('unit_kerja', $pegawai->unit_kerja) }}";

        const jenisPegawai = document.getElementById('jenis_pegawai');
        const wrapperGolongan = document.getElementById('wrapper_golongan');
        const selectGolongan = document.getElementById('golongan');

        const unitUtama = document.getElementById('unit_kerja_utama');
        const unitSub = document.getElementById('unit_kerja_sub');
        const wrapperSub = document.getElementById('wrapper_sub_unit');
        const finalInputUnit = document.getElementById('final_unit_kerja');

        // --- 3. FUNGSI LOGIC GOLONGAN ---
        function updateGolongan() {
            const selected = jenisPegawai.value;
            selectGolongan.innerHTML = '<option value="">-- Pilih Golongan --</option>';

            if (selected === 'Honorer' || selected === '') {
                wrapperGolongan.classList.remove('show');
                selectGolongan.removeAttribute('required');
                return;
            }

            wrapperGolongan.classList.add('show');
            selectGolongan.setAttribute('required', 'required');

            if (selected === 'PNS') {
                for (const [groupLabel, items] of Object.entries(dataPNS)) {
                    let optgroup = document.createElement('optgroup');
                    optgroup.label = groupLabel;
                    items.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.val;
                        option.textContent = item.text;
                        if (item.val === savedGolongan) option.selected = true; // Pre-select
                        optgroup.appendChild(option);
                    });
                    selectGolongan.appendChild(optgroup);
                }
            } else if (selected === 'PPPK') {
                dataPPPK.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.val;
                    option.textContent = "Golongan " + item.text;
                    if (item.val === savedGolongan) option.selected = true; // Pre-select
                    selectGolongan.appendChild(option);
                });
            }
        }

        // --- 4. FUNGSI LOGIC UNIT KERJA ---
        function updateUnitKerja() {
            const val = unitUtama.value;
            if (val === 'Sekretariat') {
                wrapperSub.classList.add('show');
                unitSub.setAttribute('required', 'required');
                finalInputUnit.value = unitSub.value; // Ambil nilai dari sub
            } else {
                wrapperSub.classList.remove('show');
                unitSub.removeAttribute('required');
                unitSub.value = "";
                finalInputUnit.value = val; // Ambil nilai dari utama
            }
        }

        // --- 5. INITIALIZATION (Saat Page Load) ---
        // Init Golongan
        if(jenisPegawai.value !== "") {
            updateGolongan();
        }

        // Init Unit Kerja (Cek apakah Unit kerja user ada di Sub Bagian?)
        if (savedUnit) {
            if (subBagianList.includes(savedUnit)) {
                // Berarti user ada di Sub Bagian -> Set Utama ke Sekretariat
                unitUtama.value = "Sekretariat";
                unitSub.value = savedUnit; // Set Sub ke unit asli user
                wrapperSub.classList.add('show'); // Langsung buka tanpa animasi lambat
            } else {
                // User di Bidang/Bagian biasa
                unitUtama.value = savedUnit;
            }
            // Update hidden input untuk memastikan data terisi
            finalInputUnit.value = savedUnit;
        }

        // --- 6. EVENT LISTENERS ---
        jenisPegawai.addEventListener('change', updateGolongan);
        
        unitUtama.addEventListener('change', updateUnitKerja);
        
        unitSub.addEventListener('change', function() {
            finalInputUnit.value = this.value; // Update hidden input saat sub ganti
        });
    });
</script>

@endsection