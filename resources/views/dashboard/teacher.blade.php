{{--
filepath: resources/views/dashboard/teacher.blade.php

VIEW: Teacher Dashboard
FUNGSI: Halaman dashboard untuk guru yang menampilkan:
- Daftar pengumuman terbaru
- Daftar tugas yang dibuat
- Daftar submission terbaru dari murid

CONTROLLER: DashboardController@index
ROUTE: route('teacher.dashboard')

VARIABEL DARI CONTROLLER:
- $announcements: Collection of Announcement
- $assignments: Collection of Assignment
- $recentSubmissions: Collection of Submission
--}}
@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('sidebar')
    {{-- Active: Dashboard --}}
    <a href="{{ route('teacher.dashboard') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block">
        Dashboard
    </a>

    {{--
    TODO: Integrasi dengan route teacher.assignments.create
    --}}
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Create Assignment
    </a>

    {{--
    TODO: Integrasi dengan route teacher.assignments.index
    --}}
    <a href="{{ route('teacher.assignments.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        View Assignment
    </a>

    {{--
    TODO: Integrasi dengan route teacher.announcements.index
    --}}
    <a href="{{ route('teacher.announcements.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Announcement
    </a>
@endsection

@section('content')
    {{-- SECTION: PENGUMUMAN --}}
    <div class="mb-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl text-black">Notice / Pengumuman</h1>
        </div>

        <div class="space-y-4 bg-gray-50 p-4">
            {{--
            Loop melalui data announcements dari controller
            Variabel: $announcements (Collection of Announcement)
            --}}
            @forelse($announcements as $announcement)
                <div class="border border-black bg-white p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-sm font-bold space-y-1">
                            <p>Published In: {{ $announcement->created_at->format('Y-m-d H:i:s') }}</p>
                            <p>Published By: {{ $announcement->user->name ?? 'Unknown' }}</p>
                        </div>
                        {{--
                        TODO: Integrasi dengan route teacher.announcements.destroy
                        Form action untuk menghapus pengumuman
                        --}}
                        <form action="{{ route('teacher.announcements.destroy', $announcement->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700"
                                onclick="return confirm('Yakin ingin menghapus?')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <div class="mt-4">
                        <h2 class="text-xl font-bold mb-2 uppercase">{{ $announcement->title }}</h2>
                        <p class="text-base text-gray-900">{{ $announcement->content }}</p>
                    </div>
                </div>
            @empty
                <div class="border border-gray-300 bg-white p-4 text-center text-gray-500">
                    Belum ada pengumuman.
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION: SUBMISSION TERBARU --}}
    <div class="mt-10">
        <h2 class="text-2xl text-black mb-4">Submission Terbaru</h2>
        <div class="bg-white border border-black">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium">Nama Murid</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Tugas</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Waktu</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">File</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Nilai</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{--
                    Loop melalui data submissions dari controller
                    Variabel: $recentSubmissions (Collection of Submission with user & assignment)
                    --}}
                    @forelse($recentSubmissions as $submission)
                        <tr class="border-t">
                            <td class="px-4 py-2 text-sm">{{ $submission->user->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $submission->assignment->title }}</td>
                            <td class="px-4 py-2 text-sm">{{ $submission->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                    class="text-blue-500 hover:underline">
                                    Download
                                </a>
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if($submission->score !== null)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">
                                        {{ $submission->score }}
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">
                                {{--
                                TODO: Integrasi dengan route teacher.submissions.grade
                                Form untuk memberi nilai pada submission
                                --}}
                                <form action="{{ route('teacher.submissions.grade', $submission->id) }}" method="POST"
                                    class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="score" min="0" max="100" value="{{ $submission->score }}"
                                        class="w-16 px-2 py-1 border rounded text-sm" placeholder="0-100">
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                Belum ada submission dari murid.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection