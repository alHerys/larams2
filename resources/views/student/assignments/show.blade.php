{{-- 
    filepath: resources/views/student/assignments/show.blade.php
    
    VIEW: Student Assignment Detail
    FUNGSI: Halaman detail tugas untuk murid dengan form submit
    
    CONTROLLER: AssignmentController@studentShow
    ROUTE: route('student.assignments.show', $id)
    
    VARIABEL DARI CONTROLLER:
    - $assignment: Assignment model
    - $submission: Submission model (nullable - jika sudah submit)
    - $hasSubmitted: boolean
--}}
@extends('layouts.app')

@section('title', 'View Assignment')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Dashboard
    </a>
    <a href="{{ route('student.assignments.index') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block">
        Assignment
    </a>
@endsection

@section('content')
    <div class="flex justify-center">

        {{-- Assignment Detail Card --}}
        <div class="w-full max-w-4xl border border-gray-300 bg-gray-100 p-8 min-h-[500px]">

            {{-- Header Info --}}
            <div class="mb-12">
                <h1 class="text-xl text-black font-medium">{{ $assignment->title }}</h1>
                <p class="text-gray-500 text-sm mt-1">Posted in {{ $assignment->created_at->format('Y-m-d') }}</p>
            </div>

            {{-- Form/Detail Table Layout --}}
            <div class="space-y-4 max-w-2xl mx-auto">

                {{-- Due Date Row --}}
                @if ($assignment->due_date)
                    <div class="flex">
                        <div class="w-36 bg-gray-300 text-gray-500 py-3 px-4 text-sm font-medium flex items-center">
                            DueDate
                        </div>
                        <div class="flex-1 bg-gray-200 py-3 px-4 text-black text-sm flex items-center">
                            {{ $assignment->due_date->format('Y/m/d') }}
                        </div>
                    </div>
                @endif

                {{-- Description Row --}}
                <div class="flex">
                    <div class="w-36 bg-gray-300 text-gray-500 py-3 px-4 text-sm font-medium flex items-center">
                        Description<br>Assignment
                    </div>
                    <div class="flex-1 bg-gray-200 py-3 px-4 text-black text-sm flex items-center min-h-[60px]">
                        {{ $assignment->description }}
                    </div>
                </div>

                {{-- File Lampiran dari Guru (jika ada) --}}
                @if ($assignment->file_path)
                    <div class="flex">
                        <div class="w-36 bg-gray-300 text-gray-500 py-3 px-4 text-sm font-medium flex items-center">
                            Attachment
                        </div>
                        <div class="flex-1 bg-gray-200 py-3 px-4 text-sm flex items-center">
                            <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                                class="text-blue-600 hover:underline">
                                Download File Soal
                            </a>
                        </div>
                    </div>
                @endif

                @if ($hasSubmitted && $submission)
                    {{-- SUDAH SUBMIT: Tampilkan Info Submission --}}
                    <div class="mt-8 p-4 bg-green-50 border border-green-200">
                        <p class="text-green-700 font-medium mb-2">✓ Tugas sudah dikumpulkan</p>
                        <p class="text-sm text-gray-600">
                            Waktu submit: {{ $submission->created_at->format('Y-m-d H:i') }}
                        </p>
                        @if ($submission->score !== null)
                            <p class="text-sm text-gray-600 mt-1">
                                Nilai: <span class="font-bold text-green-600">{{ $submission->score }}/100</span>
                            </p>
                        @else
                            <p class="text-sm text-yellow-600 mt-1">
                                Nilai: Belum dinilai
                            </p>
                        @endif
                        <p class="text-sm mt-2">
                            File:
                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                class="text-blue-600 hover:underline">
                                Download file yang dikumpulkan
                            </a>
                        </p>
                    </div>
                @else
                    {{-- BELUM SUBMIT: Form Upload --}}
                    <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                        {{-- Browse File Row --}}
                        <div class="flex mt-8">
                            <label
                                class="w-36 bg-blue-200 hover:bg-blue-300 cursor-pointer text-black py-3 px-4 text-sm font-medium flex items-center justify-center transition-colors">
                                Browse
                                <input type="file" name="file_path" class="hidden"
                                    accept=".pdf,.doc,.docx,.zip,.png,.jpg,.jpeg" onchange="updateFileName(this)" required>
                            </label>
                            <div class="flex-1 bg-gray-200 py-3 px-4 text-sm flex items-center">
                                <span id="file-name" class="text-gray-400">No File Selected</span>
                            </div>
                        </div>

                        @error('file_path')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        {{-- Submit Button --}}
                        <div class="flex justify-center mt-12">
                            <button type="submit"
                                class="bg-blue-200 hover:bg-blue-300 text-black px-16 py-3 text-sm font-medium uppercase transition-colors">
                                SUBMIT
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>

    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No File Selected';
            document.getElementById('file-name').textContent = fileName;

            // Change color when file is selected
            if (input.files[0]) {
                document.getElementById('file-name').classList.remove('text-gray-400');
                document.getElementById('file-name').classList.add('text-black');
            }
        }
    </script>
@endsection
