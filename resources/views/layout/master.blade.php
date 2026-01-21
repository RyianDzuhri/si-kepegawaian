<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Sidebar</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        .container {
            display: grid;
            /* Defines two columns: one fixed-width (200px) sidebar and one auto-width main content area */
            grid-template-columns: 200px auto; 
            grid-template-areas: "sidebar main";
            min-height: 100vh; /* Ensures the container takes at least the full viewport height */
        }

        .sidebar {
            grid-area: sidebar;
            background-color: #f4f4f4;
            padding: 20px;
            border-right: 1px solid #ccc;
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar li a {
            display: block;
            color: #333;
            padding: 10px 0;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar li a:hover {
            background-color: #ddd;
        }

        .main-content {
            grid-area: main;
            padding: 20px;
            background-color: #fff;
        }

    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Navigation</h2>
            <ul>
                <li><a href="#home">Lihat Pemberitahuan</a></li>
                <li><a href="#about">Upload SK</a></li>
                <li><a href="{{ route('manajemen-pegawai') }}">Manajemen Data Pegawai</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
