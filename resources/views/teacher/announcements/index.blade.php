{{--
filepath: resources/views/teacher/announcements/index.blade.php

VIEW: Manage Announcements
FUNGSI: Halaman untuk guru membuat dan melihat daftar pengumuman

CONTROLLER: AnnouncementController@index
ROUTE: route('teacher.announcements.index')

VARIABEL DARI CONTROLLER:
- $announcements: Paginated Collection of Announcement
--}}
@extends('layouts.app')

@section('title', 'Kelola Pengumuman')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Dashboard
    </a>
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Create Assignment
    </a>
    <a href="{{ route('teacher.assignments.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        View Assignment
    </a>
    {{-- Active: Announcement --}}
    <a href="{{ route('teacher.announcements.index') }}" class="bg-blue-400 px-6 py-3 font-medium text-sm uppercase">
        Announcement
    </a>
@endsection

@section('content')
    <div class="flex gap-8">

        {{-- LEFT: FORM BUAT PENGUMUMAN --}}
        <div class="flex-1">
            <h2 class="text-xl font-semibold mb-4">Buat Pengumuman Baru</h2>

            {{--
            TODO: Integrasi dengan route teacher.announcements.store
            Controller: AnnouncementController@store
            --}}
            <form action="{{ route('teacher.announcements.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan judul pengumuman">
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                    <textarea name="content" id="content" rows="6" required
                        class="w-full border border-gray-300 rounded px-3 py-2"
                        placeholder="Tulis isi pengumuman...">{{ old('content') }}</textarea>
                    @error('content')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded font-medium">
                    Buat Pengumuman
                </button>
            </form>
        </div>

        {{-- RIGHT: HISTORY PENGUMUMAN --}}
        <div class="flex-1">
            <h2 class="text-xl font-semibold mb-4">Riwayat Pengumuman</h2>

            <div class="space-y-4 max-h-[500px] overflow-y-auto">
                {{--
                Loop melalui data announcements dari controller
                Variabel: $announcements (Paginated Collection)
                --}}
                @forelse($announcements as $announcement)
                    <div class="border border-gray-300 p-4 rounded">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $announcement->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            {{--
                            TODO: Integrasi dengan route teacher.announcements.destroy
                            --}}
                            <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                        <p class="text-gray-700 mt-2">{{ Str::limit($announcement->content, 150) }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada pengumuman.</p>
                @endforelse

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection