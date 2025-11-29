@extends('layouts.app')

@section('title', 'Buat Tugas')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Dashboard
    </a>
    {{-- Active: Create Assignment --}}
    <a href="{{ route('teacher.assignments.create') }}" class="bg-blue-400 px-6 py-3 font-medium text-sm uppercase">
        Create Assignment
    </a>
    <a href="{{ route('teacher.assignments.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        View Assignment
    </a>
    <a href="{{ route('teacher.announcements.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase hover:bg-gray-200">
        Announcement
    </a>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-gray-50 p-10 text-center shadow-sm">
            <h1 class="text-3xl mb-10 text-black">Create Assignment</h1>

            {{--
            TODO: Integrasi dengan route teacher.assignments.store
            Controller: AssignmentController@store
            enctype="multipart/form-data" untuk file upload
            --}}
            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-5 text-left">
                @csrf

                {{-- Title Field --}}
                <div>
                    <input type="text" name="title" placeholder="Title" value="{{ old('title') }}"
                        class="w-full bg-gray-300 p-3 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div>
                    <textarea name="description" placeholder="Description"
                        class="w-full bg-gray-300 p-3 h-32 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Due Date Field (Optional) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (Opsional)</label>
                    <input type="datetime-local" name="due_date" value="{{ old('due_date') }}"
                        class="w-full bg-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('due_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- File Upload Field --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Lampiran (Opsional)</label>
                    <div class="flex">
                        <label
                            class="bg-blue-300 w-40 p-3 text-sm flex items-center justify-center cursor-pointer hover:bg-blue-400">
                            Browse
                            <input type="file" name="file_path" class="hidden" accept=".pdf,.doc,.docx,.zip,.png,.jpg"
                                onchange="updateFileName(this)">
                        </label>
                        <input type="text" id="file-name-display" placeholder="No File Selected"
                            class="bg-gray-300 flex-1 p-3 placeholder-gray-500 focus:outline-none" readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, ZIP, PNG, JPG. Maks 2MB.</p>
                    @error('file_path')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="text-center">
                    <button type="submit" class="w-1/2 bg-blue-300 py-3 mt-6 font-medium hover:bg-blue-400 uppercase">
                        CREATE
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No File Selected';
            document.getElementById('file-name-display').value = fileName;
        }
    </script>
@endsection
