{{--
filepath: resources/views/teacher/assignments/index.blade.php

VIEW: List Assignments
FUNGSI: Halaman untuk guru melihat daftar semua tugas yang dibuat

CONTROLLER: AssignmentController@index
ROUTE: route('teacher.assignments.index')

VARIABEL DARI CONTROLLER:
- $assignments: Paginated Collection of Assignment with submissions_count
--}}
@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Dashboard
    </a>
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Create Assignment
    </a>
    {{-- Active: View Assignment --}}
    <a href="{{ route('teacher.assignments.index') }}" class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase">
        View Assignment
    </a>
    <a href="{{ route('teacher.announcements.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Announcement
    </a>
@endsection

@section('content')
    <h2 class="text-2xl font-semibold mb-6">Daftar Tugas</h2>

    <div class="bg-white border border-gray-200 rounded">
        {{--
        Loop melalui data assignments dari controller
        Variabel: $assignments (Paginated Collection with submissions_count)
        --}}
        @forelse($assignments as $assignment)
            <div
                class="p-4 border-b border-gray-200 flex justify-between items-center {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                <div class="flex-1">
                    <span class="font-medium">{{ $assignment->title }}</span>
                    <p class="text-sm text-gray-500">
                        Dibuat: {{ $assignment->created_at->format('d M Y H:i') }}
                        | Submission: {{ $assignment->submissions_count }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    {{--
                    TODO: Integrasi dengan route teacher.assignments.show
                    Untuk melihat detail tugas dan submission
                    --}}
                    <a href="{{ route('teacher.assignments.show', $assignment->id) }}"
                        class="bg-blue-300 px-3 py-1 text-xs font-medium hover:bg-blue-400 rounded">
                        VIEW
                    </a>
                    {{--
                    TODO: Integrasi dengan route teacher.assignments.destroy
                    --}}
                    <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-300 px-3 py-1 text-xs font-medium hover:bg-red-400 rounded"
                            onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                            DELETE
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                Belum ada tugas.
                <a href="{{ route('teacher.assignments.create') }}" class="text-blue-500 hover:underline">
                    Buat tugas baru
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $assignments->links() }}
    </div>
@endsection
