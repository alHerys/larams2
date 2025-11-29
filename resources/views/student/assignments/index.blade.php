@extends('layouts.app')

@section('title', 'Assignments')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Dashboard
    </a>
    {{-- Active: Assignment --}}
    <a href="{{ route('student.assignments.index') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block">
        Assignment
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Assignments List --}}
        <div class="space-y-4">
            @forelse($assignments as $assignment)
                {{-- Assignment Card --}}
                <div class="border border-black p-4 flex justify-between items-center bg-gray-50">
                    <div>
                        <h3 class="font-normal text-black text-lg">
                            New Assignment: {{ $assignment->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">
                            Posted in {{ $assignment->created_at->format('Y-m-d') }}
                        </p>
                    </div>
                    {{-- VIEW Button --}}
                    <a href="{{ route('student.assignments.show', $assignment->id) }}"
                        class="bg-blue-200 hover:bg-blue-300 text-black px-8 py-2 text-sm font-medium uppercase transition-colors">
                        VIEW
                    </a>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="border border-black p-8 text-center bg-gray-50">
                    <p class="text-gray-500">Belum ada tugas yang tersedia.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
