{{--
filepath: resources/views/layouts/guest.blade.php

LAYOUT: Guest Layout
FUNGSI: Layout untuk halaman yang tidak memerlukan autentikasi (login, register)

PENGGUNAAN DI VIEW:
@extends('layouts.guest')
@section('title', 'Judul Halaman')
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
    <title>@yield('title', 'LaraMS') - Learning Management System</title>

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
    Uncomment baris berikut setelah mengkonfigurasi Vite:
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}
</head>

<body class="bg-[#AEDEFC] min-h-screen">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content --}}
    @yield('content')

</body>

</html>