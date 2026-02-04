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
    </style>
</head>
<body class="font-sans antialiased bg-blue-50 text-gray-800">
    <div class="min-h-screen flex flex-col justify-center items-center bg-cover bg-center p-4" style="background-image: url('{{ asset('images/bg-mathquest.png') }}');">
        <div class="absolute inset-0 bg-blue-900/10"></div>
        
        <div class="relative w-full sm:max-w-md mt-6 px-8 py-10 bg-white/95 backdrop-blur-md shadow-[0_20px_50px_rgba(0,0,0,0.2)] overflow-hidden rounded-[3rem] border-[6px] border-secondary transform transition-all duration-300">
            <div class="flex flex-col items-center mb-8">
                <div class="bg-primary/10 p-4 rounded-full mb-4">
                    <h1 class="text-5xl font-extrabold text-primary drop-shadow-[0_2px_2px_rgba(0,0,0,0.1)] tracking-tight">MathQuest</h1>
                </div>
                <div class="h-1 w-20 bg-accent rounded-full"></div>
            </div>
            {{ $slot }}
        </div>
    </div>
</body>
</html>