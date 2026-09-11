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
    
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-emerald-700 selection:text-white">
    <div id="app" class="flex flex-col min-h-screen"></div>
</body>
</html>
