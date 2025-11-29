@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">

            {{-- Header --}}
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">LOGIN</h2>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--
            TODO: Integrasi dengan route login
            Route: route('login') - POST method
            Controller: AuthController@login
            --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Email Field --}}
                <div>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required autofocus>
                </div>

                {{-- Password Field --}}
                <div>
                    <input type="password" name="password" placeholder="Password"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-3 bg-[#90a8ff] hover:bg-[#7a94ff] text-black font-medium rounded transition">
                    LOGIN
                </button>
            </form>

            {{-- Register Link --}}
            <div class="text-center mt-6">
                {{--
                TODO: Integrasi dengan route register
                Route: route('register')
                --}}
                <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:underline">
                    Belum punya akun? Daftar
                </a>
            </div>

        </div>
    </div>
@endsection
