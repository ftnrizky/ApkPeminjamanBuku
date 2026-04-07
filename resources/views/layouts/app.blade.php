<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SportRent | Entry</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .bg-dots {
            background-image: radial-gradient(#10b981 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.2;
        }
    </style>
</head>
<body class="antialiased">
    <div class="fixed inset-0 bg-dots -z-10"></div>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>