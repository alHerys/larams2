{{-- filepath: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - LaraMS</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{--
    TODO: Integrasi dengan Vite
    Uncomment baris di bawah jika sudah setup Vite:
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    {{-- Stack untuk CSS tambahan dari child views --}}
    @stack('styles')
</head>

<body class="bg-gray-50 h-screen flex flex-col">

    {{-- HEADER --}}
    <header class="bg-blue-300 h-16 flex items-center justify-between px-6 shadow-sm flex-shrink-0">
        {{-- Back Button --}}
        <button onclick="history.back()" class="text-black hover:opacity-70 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>

        {{-- User Info --}}
        <div class="flex items-center space-x-4">
            <span class="text-black text-sm font-medium">
                {{--
                TODO: Integrasi dengan Auth
                Akan menampilkan: "Teacher: Nama" atau "Student: Nama"
                --}}
                {{ Auth::user()->isTeacher() ? 'Teacher' : 'Student' }}: {{ Auth::user()->name }}
            </span>

            {{-- Logout Button --}}
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-black hover:opacity-70 transition-opacity text-sm underline">
                    Logout
                </button>
            </form>
        </div>
    </header>

    {{-- MAIN LAYOUT --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-gray-50 flex-shrink-0 border-r border-gray-200">
            {{--
            TODO: Sidebar akan di-include berdasarkan role
            Menggunakan komponen sidebar yang berbeda untuk teacher dan student
            --}}
            @if(Auth::user()->isTeacher())
                @include('components.sidebar.teacher')
            @else
                @include('components.sidebar.student')
            @endif
        </aside>

        {{-- CONTENT AREA --}}
        <main class="flex-1 p-8 overflow-y-auto bg-white">
            {{-- Flash Messages --}}
            @include('components.flash-message')

            {{-- Page Content --}}
            {{ $slot }}
        </main>
    </div>

    {{-- Stack untuk JS tambahan dari child views --}}
    @stack('scripts')
</body>

</html>