<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Kepegawaian</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- TEMPAT MENAMPUNG CSS TAMBAHAN (Seperti Cropper.js) --}}
    @stack('styles')

    <style>
        body {
            background-color: #f5f7fa;
            overflow-x: hidden;
        }
        
        /* Gaya Sidebar */
        .sidebar {
            height: 100vh; /* Tinggi pas 1 layar penuh */
            position: sticky; /* Membuat elemen menempel */
            top: 0; /* Menempel di bagian atas layar */
            overflow-y: auto; /* Agar sidebar bisa di-scroll sendiri jika menunya panjang */
            
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            transition: all 0.3s;
            z-index: 100; /* Memastikan sidebar di atas elemen lain */
        }
        
        /* Custom Scrollbar untuk Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-size: 0.95rem;
            border-left: 4px solid transparent;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.05);
            color: #fff;
        }

        .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #3498db;
        }

        .main-content {
            width: 100%;
            padding: 0;
            min-height: 100vh; 
        }
    </style>
</head>
<body>

<div class="d-flex">
    
    {{-- SIDEBAR --}}
    <div class="sidebar d-flex flex-column flex-shrink-0 p-0" style="width: 260px;">
        <div class="sidebar-header">
            <h5 class="mb-0 fw-bold"><i class="fas fa-building me-2"></i>SI-PEGAWAI</h5>
            <small class="text-light opacity-75">Manajemen Kepegawaian</small>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2" style="width: 20px;"></i> Dashboard
            </a>

            <div class="text-uppercase text-light opacity-50 px-3 mt-4 mb-2" style="font-size: 11px; letter-spacing: 1px;">Menu Utama</div>

            <a href="{{ route('manajemen-pegawai') }}" class="{{ request()->routeIs('manajemen-pegawai*') ? 'active' : '' }}">
                <i class="fas fa-users me-2" style="width: 20px;"></i> Data Pegawai
            </a>

            <a href="{{ route('arsip-sk') }}" class="{{ request()->routeIs('arsip-sk*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice me-2" style="width: 20px;"></i> Arsip SK
            </a>
        </div>

        <div class="mt-auto p-3">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="d-flex align-items-center text-danger rounded bg-dark bg-opacity-25 p-2 text-decoration-none">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </form>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="main-content">
        
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-text text-secondary">
                    Hari ini: <strong>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</strong>
                </span>
                
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(substr(Auth::user()->username ?? 'U', 0, 2)) }}&background=0D8ABC&color=fff" 
                        class="rounded-circle me-2" 
                        width="35" 
                        height="35">
                    <span class="fw-semibold">{{ Auth::user()->username ?? '-' }}</span>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- TEMPAT MENAMPUNG SCRIPT JAVASCRIPT TAMBAHAN (Seperti Logic Cropper) --}}
@stack('scripts')



{{-- SCRIPT ANTI SPAM SUBMIT (GLOBAL) --}}
    <script>
        function disableBtnSubmit(form) {
            // Cari tombol submit di dalam form yang sedang ditekan
            var btn = form.querySelector('button[type="submit"]');
            
            if (btn) {
                // Matikan tombol
                btn.disabled = true;
                
                // Ubah tampilan tombol jadi loading (Pakai Spinner Bootstrap)
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            }
            
            // Izinkan form untuk lanjut kirim data
            return true; 
        }
    </script>

</body> </html>