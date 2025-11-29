@extends('layouts.app')

@section('title', 'View Assignment')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block">
        Dashboard
    </a>
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block">
        Create Assignment
    </a>
    <a href="{{ route('teacher.assignments.index') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block">
        View Assignment
    </a>
    <a href="{{ route('teacher.announcements.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block">
        Announcement
    </a>
@endsection

@section('content')
    <div class="flex gap-8 h-full">

        {{-- LEFT PANEL: List of Assignments --}}
        <div class="w-2/5 bg-gray-50 border border-gray-200 overflow-y-auto">
            {{-- Table Header --}}
            <div class="bg-gray-100 p-3 font-medium flex justify-between border-b border-gray-200">
                <span>Assignment Name</span>
                <span>Actions</span>
            </div>

            {{-- Assignment List --}}
            @forelse($assignments as $index => $assignment)
                <div
                    class="p-4 border-b border-gray-200 flex justify-between items-center {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} {{ isset($selectedAssignment) && $selectedAssignment->id == $assignment->id ? 'bg-blue-50' : '' }}">
                    <span class="text-sm">{{ $assignment->title }}</span>
                    <div class="flex space-x-1">
                        {{-- EDIT Button --}}
                        <a href="{{ route('teacher.assignments.index', ['id' => $assignment->id, 'mode' => 'edit']) }}"
                            class="bg-blue-400 hover:bg-blue-500 px-3 py-1 text-xs font-medium text-black transition">
                            EDIT
                        </a>
                        {{-- VIEW Button --}}
                        <a href="{{ route('teacher.assignments.index', ['id' => $assignment->id, 'mode' => 'view']) }}"
                            class="bg-blue-300 hover:bg-blue-400 px-3 py-1 text-xs font-medium text-black transition">
                            VIEW
                        </a>
                        {{-- DELETE Button --}}
                        <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST"
                            class="inline" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-blue-400 hover:bg-blue-500 px-3 py-1 text-xs font-medium text-black transition">
                                DELETE
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    Belum ada assignment.
                    <a href="{{ route('teacher.assignments.create') }}" class="text-blue-500 hover:underline block mt-2">
                        Buat assignment baru
                    </a>
                </div>
            @endforelse
        </div>

        {{-- RIGHT PANEL: Dynamic Content (Edit / View Mode) --}}
        <div class="w-3/5 bg-gray-50 border border-gray-200 p-8 overflow-y-auto">

            @if (isset($selectedAssignment) && isset($mode))

                @if ($mode === 'edit')
                    {{-- ======================= --}}
                    {{-- EDIT MODE: Edit Form    --}}
                    {{-- ======================= --}}
                    <h2 class="text-xl mb-6">Edit: {{ $selectedAssignment->title }}</h2>

                    <form action="{{ route('teacher.assignments.update', $selectedAssignment->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Title Field --}}
                        <div>
                            <input type="text" name="title" placeholder="Title"
                                value="{{ old('title', $selectedAssignment->title) }}"
                                class="w-full bg-gray-200 px-4 py-3 placeholder-gray-500 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                            @error('title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description Field --}}
                        <div>
                            <textarea name="description" placeholder="Description" rows="4"
                                class="w-full bg-gray-200 px-4 py-3 placeholder-gray-500 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
                                required>{{ old('description', $selectedAssignment->description) }}</textarea>
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- File Upload Field --}}
                        <div>
                            <div class="flex">
                                <label
                                    class="bg-blue-300 hover:bg-blue-400 px-6 py-3 text-sm font-medium cursor-pointer transition">
                                    Browse
                                    <input type="file" name="file_path" class="hidden"
                                        accept=".pdf,.doc,.docx,.zip,.png,.jpg" onchange="updateEditFileName(this)">
                                </label>
                                <input type="text" id="edit-file-name" placeholder="No File Selected"
                                    value="{{ $selectedAssignment->file_path ? basename($selectedAssignment->file_path) : 'No File Selected' }}"
                                    class="flex-1 bg-gray-200 px-4 py-3 placeholder-gray-500 text-gray-600 focus:outline-none"
                                    readonly>
                            </div>
                            @if ($selectedAssignment->file_path)
                                <p class="text-xs text-gray-500 mt-1">
                                    File saat ini:
                                    <a href="{{ Storage::url($selectedAssignment->file_path) }}" target="_blank"
                                        class="text-blue-500 hover:underline">
                                        Download
                                    </a>
                                </p>
                            @endif
                            @error('file_path')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-blue-300 hover:bg-blue-400 px-8 py-2 text-black font-medium uppercase transition">
                                EDIT
                            </button>
                        </div>
                    </form>
                @elseif($mode === 'view')
                    {{-- ======================= --}}
                    {{-- VIEW MODE: Grading Table --}}
                    {{-- ======================= --}}
                    <h2 class="text-xl mb-6">View: {{ $selectedAssignment->title }}</h2>

                    {{-- Table Header --}}
                    <div class="flex justify-between border-b border-gray-300 pb-2 mb-4">
                        <span class="font-medium">Student Name</span>
                        <span class="font-medium mr-12">Marks</span>
                    </div>

                    {{-- Form untuk menyimpan semua nilai sekaligus --}}
                    <form action="{{ route('teacher.submissions.gradeAll') }}" method="POST">
                        @csrf
                        <input type="hidden" name="assignment_id" value="{{ $selectedAssignment->id }}">

                        {{-- Student Submissions List --}}
                        @forelse($selectedAssignment->submissions as $submission)
                            <div class="flex justify-between items-center bg-gray-100 p-3 mb-2">
                                <span>{{ $submission->user->name }}</span>
                                <div class="flex items-center space-x-2">
                                    <input type="hidden" name="submissions[{{ $submission->id }}][id]"
                                        value="{{ $submission->id }}">

                                    @if ($submission->score !== null)
                                        {{-- Sudah dinilai --}}
                                        <input type="text" name="submissions[{{ $submission->id }}][score]"
                                            value="{{ $submission->score }}"
                                            class="w-20 p-1 text-center border border-gray-300 bg-gray-200" readonly
                                            id="score-{{ $submission->id }}">
                                        <button type="button" onclick="enableEdit({{ $submission->id }})"
                                            class="bg-gray-300 hover:bg-gray-400 px-3 py-1 text-sm text-gray-600 transition"
                                            id="edit-btn-{{ $submission->id }}">
                                            Edit
                                        </button>
                                    @else
                                        {{-- Belum dinilai --}}
                                        <input type="number" name="submissions[{{ $submission->id }}][score]"
                                            placeholder="Input Mark" min="0" max="100"
                                            class="w-24 p-1 text-center border border-gray-300 bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <div class="w-10"></div> {{-- Spacer for alignment --}}
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-8">
                                <p>Belum ada submission dari murid untuk tugas ini.</p>
                            </div>
                        @endforelse

                        {{-- Save Button --}}
                        @if ($selectedAssignment->submissions->count() > 0)
                            <div class="flex justify-end mt-8">
                                <button type="submit"
                                    class="bg-blue-300 hover:bg-blue-400 px-8 py-2 font-medium uppercase transition">
                                    SAVE
                                </button>
                            </div>
                        @endif
                    </form>

                @endif
            @else
                {{-- ======================= --}}
                {{-- EMPTY STATE             --}}
                {{-- ======================= --}}
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg">Pilih Assignment</p>
                    <p class="text-sm mt-1">Klik EDIT untuk mengedit atau VIEW untuk melihat submission</p>
                </div>
            @endif

        </div>

    </div>

    <script>
        // Update file name when file is selected (for edit mode)
        function updateEditFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No File Selected';
            document.getElementById('edit-file-name').value = fileName;
        }

        // Enable editing for a graded submission
        function enableEdit(submissionId) {
            const input = document.getElementById('score-' + submissionId);
            const button = document.getElementById('edit-btn-' + submissionId);

            input.readOnly = false;
            input.type = 'number';
            input.min = '0';
            input.max = '100';
            input.classList.remove('bg-gray-200');
            input.classList.add('bg-white', 'focus:outline-none', 'focus:ring-2', 'focus:ring-blue-400');
            input.focus();

            button.textContent = 'Editing...';
            button.classList.add('bg-yellow-300');
            button.classList.remove('bg-gray-300');
            button.disabled = true;
        }
    </script>
@endsection
