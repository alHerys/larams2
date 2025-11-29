@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-8">
        <div class="bg-white w-full max-w-md p-8 rounded-lg shadow-lg">

            {{-- Header --}}
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">REGISTRASI</h2>

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
            TODO: Integrasi dengan route register
            Route: route('register') - POST method
            Controller: AuthController@register
            --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Name Field --}}
                <div>
                    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required autofocus>
                </div>

                {{-- Email Field --}}
                <div>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                {{-- Password Field --}}
                <div>
                    <input type="password" name="password" placeholder="Password"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                {{-- Confirm Password Field --}}
                <div>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                </div>

                {{-- Role Selection --}}
                <div>
                    <select name="role"
                        class="w-full px-4 py-3 bg-gray-200 border-none rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                        <option value="">Pilih Role</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student (Murid)</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher (Guru)</option>
                    </select>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full py-3 bg-[#90a8ff] hover:bg-[#7a94ff] text-black font-medium rounded transition">
                    REGISTRASI
                </button>
            </form>

            {{-- Login Link --}}
            <div class="text-center mt-6">
                {{--
                TODO: Integrasi dengan route login
                Route: route('login')
                --}}
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                    Sudah punya akun? Login
                </a>
            </div>

        </div>
    </div>
@endsection
