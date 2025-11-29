@extends('layouts.app')

@section('title', 'Announcement')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Dashboard
    </a>
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Create Assignment
    </a>
    <a href="{{ route('teacher.assignments.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        View Assignment
    </a>
    {{-- Active: Announcement --}}
    <a href="{{ route('teacher.announcements.index') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block">
        Announcement
    </a>
@endsection

@section('content')
    <div class="flex gap-8 h-full">

        {{-- LEFT: Create Announcement Form --}}
        <div class="w-1/2 bg-gray-50 p-8 flex flex-col items-center">
            <h2 class="text-2xl mb-8">Create Announcement</h2>

            <form action="{{ route('teacher.announcements.store') }}" method="POST" class="w-full space-y-4">
                @csrf

                {{-- Title Field --}}
                <div>
                    <input type="text" name="title" placeholder="Title" value="{{ old('title') }}"
                        class="w-full bg-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description/Content Field --}}
                <div>
                    <textarea name="content" placeholder="Description"
                        class="w-full bg-gray-300 p-3 h-32 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none" required>{{ old('content') }}</textarea>
                    @error('content')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full bg-blue-300 py-3 mt-4 uppercase font-medium hover:bg-blue-400 transition">
                    POST
                </button>
            </form>
        </div>

        {{-- RIGHT: History Announcement --}}
        <div class="w-1/2">
            {{-- Table Header --}}
            <div class="flex bg-gray-100 p-3 text-sm font-medium mb-2">
                <span class="flex-1">History Announcement</span>
                <span class="w-24 text-center">Date</span>
                <span class="w-24 text-center">Action</span>
            </div>

            {{-- Announcement List --}}
            <div class="space-y-1 max-h-[500px] overflow-y-auto">
                @forelse($announcements as $announcement)
                    <div class="flex items-center bg-gray-50 p-3 border border-gray-100">
                        {{-- Title/Content Preview --}}
                        <span class="flex-1 text-sm truncate" title="{{ $announcement->title }}">
                            {{ Str::limit($announcement->title . ': ' . $announcement->content, 40) }}
                        </span>

                        {{-- Date --}}
                        <span class="w-24 text-center text-sm bg-blue-200 py-1 mx-2">
                            {{ $announcement->created_at->format('y/m/d') }}
                        </span>

                        {{-- Delete Button --}}
                        <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}" method="POST"
                            class="inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-24 text-center text-sm bg-blue-200 hover:bg-blue-300 py-1 uppercase transition">
                                DELETE
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8 bg-gray-50 border border-gray-100">
                        Belum ada pengumuman.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
