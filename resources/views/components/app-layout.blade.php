<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MathQuest SD') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Fredoka', 'sans-serif'],
                    },
                    colors: {
                        primary: '#FF6B6B',
                        secondary: '#4ECDC4',
                        accent: '#FFE66D',
                        dark: '#2C3E50',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Fredoka', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-blue-50">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="p-4">
            {{ $slot }}
        </main>
    </div>
</body>
</html>