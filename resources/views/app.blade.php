<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Website Resmi Kelurahan Kraksaan Wetan - Kabupaten Probolinggo</title>
    <meta name="description" content="Website Resmi Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo. Portal informasi publik, transparansi, dan layanan administrasi masyarakat.">
    <meta name="keywords" content="Kelurahan Kraksaan Wetan, Kraksaan, Kabupaten Probolinggo, Pelayanan Kelurahan, Berita Kraksaan, Profil Kelurahan">
    <meta name="author" content="Pemerintah Kelurahan Kraksaan Wetan">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Website Resmi Kelurahan Kraksaan Wetan">
    <meta property="og:description" content="Portal pelayanan masyarakat dan informasi terpadu Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo.">
    
    <!-- Favicon & Tab Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter for clean, human, authentic typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-700 selection:text-white">
    <div id="app" class="flex flex-col min-h-screen"></div>
</body>
</html>
