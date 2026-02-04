<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} - SI-PEGAWAI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body { background-color: #f5f7fa; }
        .auth-card { max-width: 420px; margin: 8vh auto; }
        .logo { font-weight:700; color:#0d6efd; }
    </style>
</head>
<body>

<div class="container">
    <div class="auth-card">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <h4 class="mb-0 logo"><i class="fas fa-building me-2"></i>SI-PEGAWAI</h4>
                    <small class="text-muted">Manajemen Kepegawaian</small>
                </div>

                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>