@extends('layouts.app')

@section('content')

{{-- STYLE KHUSUS UNTUK TOMBOL MELAYANG --}}
<style>
    .btn-floating {
        position: fixed;      /* Kunci: Membuat elemen tetap di layar */
        bottom: 30px;         /* Jarak dari bawah layar */
        right: 30px;          /* Jarak dari kanan layar */
        z-index: 9999;        /* Pastikan tombol ada di atas elemen lain */
        border-radius: 50px;  /* Membuat sudut membulat (bentuk pil) */
        padding: 12px 25px;   /* Ukuran tombol lebih lega */
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4); /* Efek bayangan merah */
        transition: all 0.3s ease; /* Animasi halus saat hover */
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    /* Efek saat mouse diarahkan ke tombol */
    .btn-floating:hover {
        transform: translateY(-5px); /* Tombol naik sedikit */
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.6); /* Bayangan makin tebal */
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Data Pegawai</h3>
        <p class="text-muted mb-0">Manajemen data seluruh pegawai aktif.</p>
    </div>
    <a href="{{ route('tambah-pegawai') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Pegawai
    </a>
</div>

<div class="card shadow-sm border-0 mb-5"> <div class="card-body">
        
        <form action="{{ url()->current() }}" method="GET"> 
            <div class="row mb-4 g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Cari NIP, NIK, atau Nama..." value="{{ request('q') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="unit_kerja" class="form-select">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($listUnitKerja as $uk)
                            <option value="{{ $uk }}" {{ request('unit_kerja') == $uk ? 'selected' : '' }}>
                                {{ $uk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="PNS" {{ request('status') == 'PNS' ? 'selected' : '' }}>PNS</option>
                        <option value="PPPK" {{ request('status') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                        <option value="Honorer" {{ request('status') == 'Honorer' ? 'selected' : '' }}>Honorer/Kontrak</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark w-100"><i class="fas fa-filter"></i></button>
                </div>
                
                @if(request()->anyFilled(['q', 'status', 'unit_kerja']))
                <div class="col-md-2">
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Pegawai</th>
                        <th width="20%">Unit Kerja</th>
                        <th width="20%">Jabatan & Golongan</th>
                        <th width="10%">Status</th>
                        <th width="15%" class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai as $index => $item)
                    <tr>
                        <td>{{ $pegawai->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->foto_profil)
                                    <img src="{{ asset('storage/'. $item->foto_profil) }}" class="rounded-circle me-3" width="40" height="40" style="object-fit:cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama) }}&background=random" class="rounded-circle me-3" width="40" height="40">
                                @endif
                                
                                <div>
                                    <div class="fw-bold text-dark">{{ $item->nama }}</div>
                                    <small class="text-muted d-block">
                                        {{ $item->nip ? 'NIP: '.$item->nip : 'NIK: '.$item->nik }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-dark">{{ $item->unit_kerja }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->jabatan }}</div>
                            <small class="text-secondary">{{ $item->golongan ?? '-' }}</small>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($item->jenis_pegawai) {
                                    'PNS' => 'bg-success text-success',
                                    'Honorer' => 'bg-warning text-warning',
                                    'PPPK' => 'bg-info text-info',
                                    default => 'bg-secondary text-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} bg-opacity-10 px-3 py-2">{{ $item->jenis_pegawai }}</span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('tampil-pegawai', ['id' => $item->id]) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('edit-pegawai', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('hapus-pegawai', $item->id) }}" 
                                    method="POST" 
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-2x mb-3 opacity-50"></i><br>
                            Data pegawai tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $pegawai->withQueryString()->links() }}
        </div>
        
        {{-- TOMBOL LAMA DIHAPUS DARI SINI --}}

    </div>
</div>

{{-- TOMBOL BARU: EXPORT PDF FLOATING --}}
<a href="{{ route('export-pdf-pegawai') }}" class="btn btn-danger btn-floating" target="_blank">
    <i class="fas fa-file-pdf me-2"></i> Export PDF
</a>

@endsection