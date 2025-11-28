{{-- 
    filepath: resources/views/teacher/assignments/show.blade.php
    
    VIEW: Assignment Detail with Submissions
    FUNGSI: Halaman detail tugas yang menampilkan daftar submission dari murid
    
    CONTROLLER: AssignmentController@show
    ROUTE: route('teacher.assignments.show', $id)
    
    VARIABEL DARI CONTROLLER:
    - $assignment: Assignment model with submissions.user eager loaded
--}}
@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200 block">
        Dashboard
    </a>
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200 block">
        Create Assignment
    </a>
    <a href="{{ route('teacher.assignments.index') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase block">
        View Assignment
    </a>
    <a href="{{ route('teacher.announcements.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200 block">
        Announcement
    </a>
@endsection

@section('content')
    {{-- DETAIL TUGAS --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold mb-4">{{ $assignment->title }}</h1>

            {{-- Tombol Kembali --}}
            <a href="{{ route('teacher.assignments.index') }}" class="text-blue-500 hover:underline text-sm">
                ← Kembali ke Daftar
            </a>
        </div>

        <div class="text-sm text-gray-500 mb-4 space-y-1">
            <p>Dibuat pada: {{ $assignment->created_at->format('d M Y H:i') }}</p>
            @if ($assignment->due_date)
                <p class="text-red-600">Deadline: {{ $assignment->due_date->format('d M Y H:i') }}</p>
            @endif
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-semibold mb-2">Deskripsi:</h3>
            <p class="text-gray-700 whitespace-pre-line bg-white p-4 rounded border">{{ $assignment->description }}</p>
        </div>

        @if ($assignment->file_path)
            <div class="mt-4">
                <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                    class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download File Lampiran
                </a>
            </div>
        @endif
    </div>

    {{-- DAFTAR SUBMISSION --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b">
            <h2 class="text-xl font-bold">
                Submission Murid
                <span class="text-gray-500 font-normal text-base">
                    ({{ $assignment->submissions->count() }} submission)
                </span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Nama Murid</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Waktu Submit</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Nilai</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- 
                        Loop melalui submissions dari assignment
                        Relasi: $assignment->submissions (eager loaded dengan user)
                    --}}
                    @forelse($assignment->submissions as $index => $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $submission->user->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $submission->user->email }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $submission->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                    class="inline-flex items-center text-blue-500 hover:text-blue-700 hover:underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($submission->score !== null)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $submission->score }}/100
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{-- 
                                    TODO: Integrasi dengan route teacher.submissions.grade
                                    Controller: SubmissionController@grade
                                --}}
                                <form action="{{ route('teacher.submissions.grade', $submission->id) }}" method="POST"
                                    class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="score" min="0" max="100"
                                        value="{{ $submission->score }}"
                                        class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        placeholder="0-100" required>
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs font-medium hover:bg-blue-600 transition">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Belum ada submission dari murid untuk tugas ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- STATISTIK SINGKAT --}}
    @if ($assignment->submissions->count() > 0)
        <div class="mt-6 grid grid-cols-3 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $assignment->submissions->count() }}</p>
                <p class="text-sm text-blue-800">Total Submission</p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-green-600">
                    {{ $assignment->submissions->whereNotNull('score')->count() }}
                </p>
                <p class="text-sm text-green-800">Sudah Dinilai</p>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-yellow-600">
                    {{ $assignment->submissions->whereNull('score')->count() }}
                </p>
                <p class="text-sm text-yellow-800">Belum Dinilai</p>
            </div>
        </div>
    @endif
@endsection
