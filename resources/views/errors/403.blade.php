<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Akses Ditolak — Portal Akademik FTEIC ITS</title>
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F3F6F7;
            font-family: 'IBM Plex Sans', system-ui, -apple-system, sans-serif;
            color: #0B2545;
            padding: 16px;
        }
        .error-panel {
            max-width: 480px;
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #D5DDE1;
            border-top: 3px solid #B3261E;
            border-radius: 4px;
            padding: 32px;
        }
        .error-code {
            font-size: 14px;
            font-weight: 600;
            color: #B3261E;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 12px 0;
        }
        .error-desc {
            font-size: 14px;
            line-height: 1.5;
            color: #55656F;
            margin: 0 0 24px 0;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 8px 16px;
            background-color: #13757A;
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 600;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #0E5C60;
        }
        .btn-back:focus-visible {
            outline: 2px solid #13757A;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <main class="error-panel" role="main">
        <div class="error-code">Kode 403 — Akses ditolak</div>
        <h1 class="error-title">Izin tidak mencukupi</h1>
        <p class="error-desc">
            Anda tidak punya izin membuka halaman ini. Hubungi admin jika Anda merasa ini keliru.
        </p>
        @auth
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staf'))
                <a href="{{ route('admin.dashboard') }}" class="btn-back">Kembali ke dashboard</a>
            @else
                <a href="{{ route('home') }}" class="btn-back">Kembali ke dashboard</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-back">Kembali ke dashboard</a>
        @endauth
    </main>
</body>
</html>
