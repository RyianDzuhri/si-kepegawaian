@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Upload Dokumen SK</h4>
                <a href="{{ route('arsip-sk') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Batal
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    {{-- Tampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('simpan-sk') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableBtnSubmit(this)"> {{-- <--- TAMBAHKAN INI --}}>
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pemilik SK <span class="text-danger">*</span></label>
                            
                            @if($selectedPegawai)
                                <div class="alert alert-primary d-flex align-items-center mb-0" role="alert">
                                    <i class="fas fa-user-check fa-2x me-3"></i>
                                    <div>
                                        <div class="fw-bold">{{ $selectedPegawai->nama }}</div>
                                        <div class="small">NIP: {{ $selectedPegawai->nip ?? '-' }} | {{ $selectedPegawai->jabatan }}</div>
                                    </div>
                                    <input type="hidden" name="pegawai_id" value="{{ $selectedPegawai->id }}">
                                </div>
                                <div class="mt-2 text-end">
                                    <a href="{{ route('tambah-sk') }}" class="small text-muted text-decoration-none">
                                        <i class="fas fa-sync-alt me-1"></i>Bukan pegawai ini? Reset Pilihan
                                    </a>
                                </div>
                            @else
                                <select name="pegawai_id" class="form-select form-select-lg bg-light" required>
                                    <option value="">-- Cari Nama Pegawai --</option>
                                    @foreach ($pegawaiList as $pegawai)
                                        <option value="{{ $pegawai->id }}">
                                            {{ $pegawai->nama }} ({{ $pegawai->nip ?? 'Non-ASN' }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih pegawai pemilik dokumen ini.</small>
                            @endif
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis SK <span class="text-danger">*</span></label>
                                <select name="jenis_sk" id="jenis_sk" class="form-select" required onchange="toggleInputs()">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="SK Kenaikan Pangkat">SK Kenaikan Pangkat</option>
                                    <option value="SK Gaji Berkala">SK Gaji Berkala (KGB)</option>
                                    <option value="SK Jabatan">SK Jabatan / Mutasi</option>
                                    <option value="SK CPNS">SK CPNS</option>
                                    <option value="SK PNS">SK PNS (100%)</option>
                                    <option value="Lainnya">Lainnya (Izin Belajar/Tugas/dll)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_sk" class="form-control" placeholder="Contoh: 800/123/BKD/2024" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_sk" class="form-control" required>
                                <small class="text-muted">Tanggal penandatanganan SK.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary">TMT Berlaku (Penting)</label>
                                <input type="date" name="tmt_sk" class="form-control border-primary" required>
                                <small class="text-muted">Tanggal mulai berlakunya pangkat/gaji baru.</small>
                            </div>
                        </div>

                        <div id="dynamic_inputs" class="bg-light p-3 rounded mb-4 d-none">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-edit me-2"></i>Perubahan Data Pegawai</h6>
                            
                            <div id="input_golongan" class="row mb-2 d-none">
                                <div class="col-md-12">
                                    <label class="form-label text-success fw-bold">Naik ke Golongan Berapa?</label>
                                    <select name="golongan_baru" class="form-select">
                                        <option value="">-- Pilih Golongan Baru --</option>
                                        <optgroup label="Golongan II">
                                            <option value="II/a">II/a</option><option value="II/b">II/b</option>
                                            <option value="II/c">II/c</option><option value="II/d">II/d</option>
                                        </optgroup>
                                        <optgroup label="Golongan III">
                                            <option value="III/a">III/a</option><option value="III/b">III/b</option>
                                            <option value="III/c">III/c</option><option value="III/d">III/d</option>
                                        </optgroup>
                                        <optgroup label="Golongan IV">
                                            <option value="IV/a">IV/a</option><option value="IV/b">IV/b</option>
                                            <option value="IV/c">IV/c</option><option value="IV/d">IV/d</option>
                                            <option value="IV/e">IV/e</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div id="input_jabatan" class="row mb-2 d-none">
                                <div class="col-md-6">
                                    <label class="form-label text-success fw-bold">Jabatan Baru</label>
                                    <input type="text" name="jabatan_baru" class="form-control" placeholder="Nama jabatan baru...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-success fw-bold">Unit Kerja Baru</label>
                                    <input type="text" name="unit_kerja_baru" class="form-control" placeholder="Dinas/Bagian baru...">
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="update_otomatis" value="1" id="autoUpdate" checked>
                                <label class="form-check-label fw-bold text-dark" for="autoUpdate">
                                    Update data Pegawai secara otomatis?
                                </label>
                                <div class="small text-muted">
                                    Jika dicentang, Golongan/TMT/Jabatan pada profil pegawai akan langsung berubah sesuai data di atas. Notifikasi kadaluarsa akan hilang.
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload File Dokumen</label>
                            <div class="border border-2 border-dashed rounded p-4 text-center bg-white" id="drop_area">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <input type="file" name="file_sk" id="file_sk" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                <div class="small text-muted mt-2">Wajib PDF/JPG. Pastikan tulisan terbaca jelas.</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Simpan & Proses
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT SEDERHANA UNTUK LOGIKA TAMPILAN --}}
<script>
    function toggleInputs() {
        const jenis = document.getElementById('jenis_sk').value;
        const container = document.getElementById('dynamic_inputs');
        const inputGol = document.getElementById('input_golongan');
        const inputJab = document.getElementById('input_jabatan');
        
        // Reset
        container.classList.add('d-none');
        inputGol.classList.add('d-none');
        inputJab.classList.add('d-none');

        // Logic
        if (jenis === 'SK Kenaikan Pangkat') {
            container.classList.remove('d-none');
            inputGol.classList.remove('d-none');
        } else if (jenis === 'SK Jabatan') {
            container.classList.remove('d-none');
            inputJab.classList.remove('d-none');
        } else if (jenis === 'SK Gaji Berkala' || jenis === 'SK PNS' || jenis === 'SK CPNS') {
            container.classList.remove('d-none');
            // Hanya butuh checkbox auto-update, tidak butuh input teks tambahan
        }
    }
</script>

@endsection