<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} - SI-KGB Kendari</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body { 
            background-color: #f5f7fa;
            /* Pastikan gambar background ada di folder public/images/ */
            background-image: url("{{ asset('images/bg-login.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-card { 
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .logo { 
            font-weight: 700; color: #0d6efd; 
        }

        /* === EFEK GLASSMORPHISM (KACA BURAM) === */
        .card {
            /* Latar belakang putih semi-transparan.
               Ubah angka 0.65 (antara 0.1 sampai 0.9) untuk mengatur tingkat keburaman. */
            background-color: rgba(255, 255, 255, 0.3) !important;
            
            /* INI KUNCINYA: Efek blur pada background di belakang card */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px); /* Untuk browser Safari/Mac */

            /* Border putih tipis transparan untuk menegaskan pinggiran kaca */
            border: 1px solid rgba(255, 255, 255, 0.4);
            
            /* Shadow lembut biar card terlihat melayang */
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            
            border-radius: 15px; /* Sudut yang lebih membulat */
        }
        /* ========================================== */

        /* Sedikit penyesuaian pada input agar serasi dengan kaca */
        .form-control {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <h4 class="mb-1 logo fw-bolder"><i class="fas fa-building me-2"></i>SI-KGB Kendari</h4>
            </div>

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>