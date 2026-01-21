@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">Upload Dokumen SK</h4>
                <a href="{{ url('/sk') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Batal
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pemilik SK <span class="text-danger">*</span></label>
                            <select name="pegawai_id" class="form-select form-select-lg bg-light" required>
                                <option value="">-- Cari Nama Pegawai --</option>
                                <option value="1">Budi Santoso - 19850101 201001 1 001</option>
                                <option value="2">Siti Aminah - 19900202 201501 2 005</option>
                                </select>
                            <small class="text-muted">Pilih pegawai yang bersangkutan dengan SK ini.</small>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis SK</label>
                                <select name="jenis_sk" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="SK CPNS">SK CPNS</option>
                                    <option value="SK PNS">SK PNS</option>
                                    <option value="SK Kenaikan Pangkat">SK Kenaikan Pangkat</option>
                                    <option value="SK Gaji Berkala">SK Gaji Berkala</option>
                                    <option value="SK Jabatan">SK Jabatan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Surat</label>
                                <input type="text" name="nomor_sk" class="form-control" placeholder="Nomor tercetak di SK..." required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_sk" class="form-control" required>
                                <small class="text-muted">Tgl. SK ditandatangani</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">TMT (Terhitung Mulai Tanggal)</label>
                                <input type="date" name="tmt_sk" class="form-control" required>
                                <small class="text-muted">Tgl. SK mulai berlaku</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">File Dokumen</label>
                            <div class="border border-2 border-dashed rounded p-4 text-center bg-light">
                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                <input type="file" name="file_sk" class="form-control" required>
                                <div class="small text-muted mt-2">Format PDF atau Gambar (JPG/PNG). Maks 5MB.</div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i> Simpan ke Arsip
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection