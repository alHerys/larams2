{{--
filepath: resources/views/layouts/app.blade.php

LAYOUT: App Layout (Authenticated)
FUNGSI: Layout utama untuk halaman yang memerlukan autentikasi

PENGGUNAAN DI VIEW:
@extends('layouts.app')
@section('title', 'Judul Halaman')
@section('sidebar')
... isi sidebar ...
@endsection
@section('content')
... isi halaman ...
@endsection
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - LaraMS</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    {{--
    TODO: Integrasi dengan Vite
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}
</head>

<body class="bg-gray-50 h-screen flex flex-col">

    {{-- HEADER --}}
    <header class="bg-blue-300 h-16 flex items-center justify-between px-6 shadow-sm flex-shrink-0">
        {{-- Back Button --}}
        {{-- <button onclick="history.back()" class="text-black hover:opacity-70">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button> --}}

        {{-- User Info & Logout --}}
        <div class="flex items-center space-x-4 ml-auto">
            <span class="text-black text-sm font-medium">
                {{ Auth::user()->isTeacher() ? 'Teacher' : 'Student' }}: {{ Auth::user()->name }}
            </span>

            {{-- TODO: Integrasi dengan route logout
                Route: route('logout') - POST method --}}
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-black text-sm hover:underline">
                    Logout
                </button>
            </form>
        </div>
    </header>

    {{-- MAIN LAYOUT --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-gray-50 flex-shrink-0 border-r border-gray-200 pt-4">
            <nav class="flex flex-col">
                @yield('sidebar')
            </nav>
        </aside>

        {{-- CONTENT AREA --}}
        <main class="flex-1 p-8 overflow-y-auto bg-white">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')

        </main>
    </div>

</body>

</html>
