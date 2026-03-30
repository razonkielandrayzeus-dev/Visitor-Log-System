<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Visitor Log System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300 min-h-screen flex items-center justify-center">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/95 backdrop-blur shadow-2xl rounded-2xl sm:rounded-2xl border border-gray-200">
        {{ $slot }}
    </div>
</body>
</html>