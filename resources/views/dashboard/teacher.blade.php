@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('sidebar')
    {{-- Active: Dashboard --}}
    <a href="{{ route('teacher.dashboard') }}"
        class="bg-blue-400 text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block">
        Dashboard
    </a>
    <a href="{{ route('teacher.assignments.create') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Create Assignment
    </a>
    <a href="{{ route('teacher.assignments.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        View Assignment
    </a>
    <a href="{{ route('teacher.announcements.index') }}"
        class="text-black px-6 py-3 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Announcement
    </a>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        {{-- Notice Container --}}
        <div class="bg-gray-100 min-h-[500px]">

            {{-- Header with Navigation --}}
            <div class="flex justify-between items-center p-4">
                <h2 class="text-lg font-medium">Notice</h2>

                {{-- Pagination Navigation --}}
                <div class="flex space-x-2">
                    @if ($announcements->previousPageUrl())
                        <a href="{{ $announcements->previousPageUrl() }}"
                            class="px-3 py-1 border border-gray-400 text-sm hover:bg-gray-200 transition">
                            &lt; Previous
                        </a>
                    @else
                        <span class="px-3 py-1 border border-gray-300 text-sm text-gray-400 cursor-not-allowed">
                            &lt; Previous
                        </span>
                    @endif

                    @if ($announcements->nextPageUrl())
                        <a href="{{ $announcements->nextPageUrl() }}"
                            class="px-3 py-1 border border-gray-400 text-sm hover:bg-gray-200 transition">
                            Next&gt;
                        </a>
                    @else
                        <span class="px-3 py-1 border border-gray-300 text-sm text-gray-400 cursor-not-allowed">
                            Next&gt;
                        </span>
                    @endif
                </div>
            </div>

            {{-- Announcements List (Accordion Style) --}}
            <div class="px-4 pb-4 space-y-2">
                @forelse($announcements as $index => $announcement)
                    <div class="border border-gray-300 bg-white">
                        {{-- Accordion Header --}}
                        <button type="button" onclick="toggleAccordion({{ $announcement->id }})"
                            class="w-full p-4 text-left flex justify-between items-start hover:bg-gray-50 transition">
                            <div>
                                <p class="text-sm">
                                    <span class="font-medium">Published In:</span>
                                    {{ $announcement->created_at->format('Y-d-m H:i:s') }}
                                </p>
                                <p class="text-sm">
                                    <span class="font-medium">Published By:</span>
                                    {{ $announcement->user->name ?? 'USER' }}
                                </p>

                                {{-- Title (shown always) --}}
                                <p class="font-bold text-lg mt-2" id="title-{{ $announcement->id }}">
                                    {{ $announcement->title }}
                                </p>
                            </div>

                            {{-- Toggle Icon --}}
                            <span class="text-gray-600 mt-1">
                                {{-- Collapse Icon (up arrow - shown when expanded) --}}
                                <svg id="icon-up-{{ $announcement->id }}"
                                    class="w-5 h-5 {{ $index === 0 ? '' : 'hidden' }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                                {{-- Expand Icon (down arrow - shown when collapsed) --}}
                                <svg id="icon-down-{{ $announcement->id }}"
                                    class="w-5 h-5 {{ $index === 0 ? 'hidden' : '' }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        {{-- Accordion Content (expandable) --}}
                        <div id="content-{{ $announcement->id }}" class="px-4 pb-4 {{ $index === 0 ? '' : 'hidden' }}">
                            <p class="text-gray-700">{{ $announcement->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="border border-gray-300 bg-white p-8 text-center text-gray-500">
                        <p>Belum ada pengumuman.</p>
                        <a href="{{ route('teacher.announcements.index') }}"
                            class="text-blue-500 hover:underline mt-2 inline-block">
                            Buat pengumuman baru
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>
        function toggleAccordion(id) {
            const content = document.getElementById('content-' + id);
            const iconUp = document.getElementById('icon-up-' + id);
            const iconDown = document.getElementById('icon-down-' + id);

            // Toggle content visibility
            content.classList.toggle('hidden');

            // Toggle icons
            iconUp.classList.toggle('hidden');
            iconDown.classList.toggle('hidden');
        }
    </script>
@endsection
