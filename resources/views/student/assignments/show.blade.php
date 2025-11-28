{{-- 
    filepath: resources/views/student/assignments/show.blade.php
    
    VIEW: Assignment Detail for Student
    FUNGSI: Halaman detail tugas untuk murid, termasuk form submit tugas
    
    CONTROLLER: AssignmentController@show (student version)
    ROUTE: route('student.assignments.show', $id)
    
    VARIABEL DARI CONTROLLER:
    - $assignment: Assignment model
    - $submission: Submission model milik student ini (nullable)
    - $hasSubmitted: boolean - apakah sudah submit atau belum
--}}
@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}"
        class="text-black px-6 py-4 font-medium text-sm uppercase hover:bg-gray-200 block">
        Dashboard
    </a>
    <a href="#" class="bg-blue-400 text-black px-6 py-4 font-medium text-sm uppercase block">
        Assignment
    </a>
@endsection

@section('content')
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Tugas</h1>
        <a href="{{ route('student.dashboard') }}" class="text-blue-500 hover:underline text-sm">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- DETAIL TUGAS --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">{{ $assignment->title }}</h2>

        <div class="text-sm text-gray-500 mb-4 space-y-1">
            <p>Dibuat oleh: {{ $assignment->user->name ?? 'Teacher' }}</p>
            <p>Tanggal posting: {{ $assignment->created_at->format('d M Y H:i') }}</p>
            @if ($assignment->due_date)
                <p class="{{ $assignment->due_date->isPast() ? 'text-red-600 font-semibold' : 'text-orange-600' }}">
                    Deadline: {{ $assignment->due_date->format('d M Y H:i') }}
                    @if ($assignment->due_date->isPast())
                        (Sudah lewat)
                    @else
                        ({{ $assignment->due_date->diffForHumans() }})
                    @endif
                </p>
            @endif
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-semibold mb-2">Deskripsi / Instruksi:</h3>
            <div class="bg-gray-50 p-4 rounded border whitespace-pre-line text-gray-700">
                {{ $assignment->description }}
            </div>
        </div>

        {{-- File Lampiran dari Guru --}}
        @if ($assignment->file_path)
            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-medium text-blue-800 mb-2">File Lampiran dari Guru:</h4>
                <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Materi / Soal
                </a>
            </div>
        @endif
    </div>

    {{-- STATUS SUBMISSION --}}
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-bold mb-4">Status Pengumpulan</h3>

        @if ($hasSubmitted && $submission)
            {{-- SUDAH SUBMIT --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex items-center text-green-700 mb-2">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">Tugas Sudah Dikumpulkan!</span>
                </div>

                <div class="text-sm text-gray-600 space-y-1 ml-8">
                    <p>Waktu pengumpulan: {{ $submission->created_at->format('d M Y H:i') }}</p>
                    <p>
                        File:
                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                            class="text-blue-500 hover:underline">
                            Download file yang dikumpulkan
                        </a>
                    </p>
                </div>
            </div>

            {{-- STATUS NILAI --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium mb-2">Nilai:</h4>
                @if ($submission->score !== null)
                    <div class="flex items-center">
                        <span class="text-4xl font-bold text-green-600">{{ $submission->score }}</span>
                        <span class="text-gray-500 ml-2 text-lg">/100</span>
                    </div>
                    @if ($submission->feedback)
                        <div class="mt-3 p-3 bg-white rounded border">
                            <p class="text-sm font-medium text-gray-700">Feedback dari Guru:</p>
                            <p class="text-gray-600">{{ $submission->feedback }}</p>
                        </div>
                    @endif
                @else
                    <div class="flex items-center text-yellow-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Menunggu penilaian dari guru...</span>
                    </div>
                @endif
            </div>
        @else
            {{-- BELUM SUBMIT --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-center text-yellow-700">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-semibold">Tugas Belum Dikumpulkan</span>
                </div>
            </div>

            {{-- FORM SUBMIT --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium mb-4">Kumpulkan Tugas:</h4>

                {{-- 
                    TODO: Integrasi dengan route student.submissions.store
                    Controller: SubmissionController@store
                --}}
                <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload File Tugas
                        </label>
                        <input type="file" name="file_path" required accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <p class="text-xs text-gray-500 mt-1">
                            Format yang diterima: PDF, DOC, DOCX, ZIP, JPG, PNG. Maksimal 5MB.
                        </p>
                        @error('file_path')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" rows="3"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded font-medium transition">
                        Kumpulkan Tugas
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
