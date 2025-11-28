{{--
filepath: resources/views/dashboard/student.blade.php

VIEW: Student Dashboard
FUNGSI: Halaman dashboard untuk murid yang menampilkan:
- Daftar pengumuman
- Daftar tugas yang tersedia
- Daftar submission dan nilai

CONTROLLER: DashboardController@index
ROUTE: route('student.dashboard')

VARIABEL DARI CONTROLLER:
- $announcements: Collection of Announcement
- $assignments: Collection of Assignment
- $submittedAssignmentIds: Array of assignment IDs yang sudah di-submit
- $mySubmissions: Collection of Submission milik student ini
--}}
@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('sidebar')
    {{-- Active: Dashboard --}}
    <a href="{{ route('student.dashboard') }}"
        class="bg-blue-400 text-black px-6 py-4 font-medium text-sm uppercase tracking-wide">
        Dashboard
    </a>
    <a href="#assignments-section"
        class="text-black px-6 py-4 font-medium text-sm uppercase tracking-wide hover:bg-gray-200">
        Assignment
    </a>
@endsection

@section('content')
    {{-- SECTION: PENGUMUMAN --}}
    <div class="mb-10">
        <h1 class="text-2xl text-black mb-4">Pengumuman</h1>
        <div class="space-y-4">
            {{--
            Loop melalui data announcements dari controller
            Variabel: $announcements (Collection of Announcement)
            --}}
            @forelse($announcements as $announcement)
                <div class="border border-black bg-white p-4">
                    <div class="text-sm font-bold space-y-1 mb-2">
                        <p>Published In: {{ $announcement->created_at->format('Y-m-d H:i:s') }}</p>
                        <p>Published By: {{ $announcement->user->name ?? 'Teacher' }}</p>
                    </div>
                    <h2 class="text-xl font-bold mb-2 uppercase">{{ $announcement->title }}</h2>
                    <p class="text-base text-gray-900">{{ $announcement->content }}</p>
                </div>
            @empty
                <div class="border border-gray-300 bg-white p-4 text-center text-gray-500">
                    Belum ada pengumuman.
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION: DAFTAR TUGAS --}}
    <div id="assignments-section">
        <h1 class="text-2xl text-black mb-4">Daftar Tugas</h1>
        <div class="space-y-4">
            {{--
            Loop melalui data assignments dari controller
            Variabel: $assignments (Collection of Assignment)
            Variabel: $submittedAssignmentIds (Array) - untuk cek status submit
            --}}
            @forelse($assignments as $assignment)
                <div class="border border-black p-4 flex justify-between items-center bg-gray-50">
                    <div class="flex-1">
                        <h3 class="font-semibold text-black text-lg">{{ $assignment->title }}</h3>
                        <p class="text-gray-600 text-sm mt-1">
                            Posted: {{ $assignment->created_at->format('Y-m-d') }}
                        </p>
                        <p class="text-gray-700 mt-2">
                            {{ Str::limit($assignment->description, 100) }}
                        </p>

                        @if($assignment->file_path)
                            <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                                class="text-blue-500 text-sm hover:underline">
                                📎 Download Materi
                            </a>
                        @endif
                    </div>

                    <div class="ml-4">
                        {{--
                        Cek apakah tugas ini sudah dikumpulkan
                        Menggunakan $submittedAssignmentIds dari controller
                        --}}
                        @if(in_array($assignment->id, $submittedAssignmentIds))
                            <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                                ✓ Sudah Dikumpulkan
                            </span>
                        @else
                            {{--
                            TODO: Integrasi dengan route student.submissions.store
                            Form untuk mengumpulkan tugas
                            --}}
                            <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data"
                                class="flex flex-col items-end space-y-2">
                                @csrf
                                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                                <input type="file" name="file_path" required class="text-sm border rounded px-2 py-1"
                                    accept=".pdf,.doc,.docx,.zip,.jpg,.png">

                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium">
                                    Kumpulkan
                                </button>
                                <p class="text-xs text-gray-500">Max 5MB (PDF, DOC, ZIP, JPG, PNG)</p>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="border border-gray-300 bg-white p-4 text-center text-gray-500">
                    Belum ada tugas.
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION: NILAI SAYA --}}
    <div class="mt-10">
        <h1 class="text-2xl text-black mb-4">Nilai Saya</h1>
        <div class="bg-white border border-black">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium">Tugas</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Waktu Pengumpulan</th>
                        <th class="px-4 py-2 text-left text-sm font-medium">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    {{--
                    Loop melalui data submissions dari controller
                    Variabel: $mySubmissions (Collection of Submission with assignment)
                    --}}
                    @forelse($mySubmissions as $submission)
                        <tr class="border-t">
                            <td class="px-4 py-2 text-sm">{{ $submission->assignment->title }}</td>
                            <td class="px-4 py-2 text-sm">{{ $submission->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">
                                @if($submission->score !== null)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-bold">
                                        {{ $submission->score }}
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded">
                                        Menunggu Penilaian
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                Anda belum mengumpulkan tugas apapun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection