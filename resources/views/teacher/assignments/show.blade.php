{{-- 
    filepath: resources/views/teacher/assignments/show.blade.php
    
    VIEW: Assignment Detail (View Submissions)
    FUNGSI: Halaman detail tugas untuk melihat daftar submission dari murid
    
    CONTROLLER: AssignmentController@show
    ROUTE: route('teacher.assignments.show', $id)
    
    VARIABEL DARI CONTROLLER:
    - $assignment: Assignment model with submissions.user
--}}
@extends('layouts.app')

@section('title', 'Detail Assignment')

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
    <div class="flex gap-6">

        {{-- LEFT SIDE: ASSIGNMENT DETAIL --}}
        <div class="flex-1">
            {{-- Header --}}
            <div class="bg-gray-200 px-4 py-3 flex justify-between items-center">
                <h2 class="font-medium text-gray-700">Assignment Detail</h2>
                <a href="{{ route('teacher.assignments.index') }}" class="text-blue-600 hover:underline text-sm">
                    ← Back to List
                </a>
            </div>

            {{-- Assignment Info --}}
            <div class="bg-white border border-gray-200 p-4 mb-6">
                <h1 class="text-xl font-bold text-gray-800 mb-2">{{ $assignment->title }}</h1>
                <p class="text-gray-600 mb-4">{{ $assignment->description }}</p>

                <div class="text-sm text-gray-500 space-y-1">
                    <p>Created: {{ $assignment->created_at->format('d M Y H:i') }}</p>
                    @if ($assignment->due_date)
                        <p>Deadline: {{ $assignment->due_date->format('d M Y H:i') }}</p>
                    @endif
                    @if ($assignment->file_path)
                        <p>
                            Attachment:
                            <a href="{{ Storage::url($assignment->file_path) }}" target="_blank"
                                class="text-blue-500 hover:underline">
                                Download File
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Submissions Table --}}
            <div class="bg-gray-200 px-4 py-3">
                <h2 class="font-medium text-gray-700">
                    Student Submissions ({{ $assignment->submissions->count() }})
                </h2>
            </div>

            <table class="w-full border-collapse bg-white">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="text-left px-4 py-2 font-medium text-gray-700 text-sm">Student Name</th>
                        <th class="text-left px-4 py-2 font-medium text-gray-700 text-sm">Submitted At</th>
                        <th class="text-center px-4 py-2 font-medium text-gray-700 text-sm">File</th>
                        <th class="text-center px-4 py-2 font-medium text-gray-700 text-sm">Score</th>
                        <th class="text-center px-4 py-2 font-medium text-gray-700 text-sm">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignment->submissions as $submission)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $submission->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $submission->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                    class="text-blue-500 hover:underline">
                                    Download
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                @if ($submission->score !== null)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                        {{ $submission->score }}
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <form action="{{ route('teacher.submissions.grade', $submission->id) }}" method="POST"
                                    class="flex items-center justify-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="score" min="0" max="100"
                                        value="{{ $submission->score }}"
                                        class="w-16 px-2 py-1 border border-gray-300 rounded text-sm text-center"
                                        placeholder="0-100">
                                    <button type="submit"
                                        class="bg-blue-400 hover:bg-blue-500 text-black px-3 py-1 text-xs font-medium">
                                        SAVE
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No submissions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RIGHT SIDE: QUICK STATS --}}
        <div class="w-72">
            <div class="bg-gray-100 p-6">
                <h3 class="text-lg font-medium text-center text-gray-800 mb-6">Statistics</h3>

                <div class="space-y-4">
                    {{-- Total Submissions --}}
                    <div class="bg-white p-4 text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $assignment->submissions->count() }}</p>
                        <p class="text-sm text-gray-600">Total Submissions</p>
                    </div>

                    {{-- Graded --}}
                    <div class="bg-white p-4 text-center">
                        <p class="text-3xl font-bold text-green-600">
                            {{ $assignment->submissions->whereNotNull('score')->count() }}
                        </p>
                        <p class="text-sm text-gray-600">Graded</p>
                    </div>

                    {{-- Not Graded --}}
                    <div class="bg-white p-4 text-center">
                        <p class="text-3xl font-bold text-yellow-600">
                            {{ $assignment->submissions->whereNull('score')->count() }}
                        </p>
                        <p class="text-sm text-gray-600">Not Graded</p>
                    </div>

                    {{-- Average Score --}}
                    @if ($assignment->submissions->whereNotNull('score')->count() > 0)
                        <div class="bg-white p-4 text-center">
                            <p class="text-3xl font-bold text-purple-600">
                                {{ number_format($assignment->submissions->whereNotNull('score')->avg('score'), 1) }}
                            </p>
                            <p class="text-sm text-gray-600">Average Score</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
