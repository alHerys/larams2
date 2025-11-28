{{-- filepath: resources/views/components/sidebar/teacher.blade.php --}}

{{--
KOMPONEN: Sidebar untuk Guru

Menampilkan menu navigasi khusus guru:
- Dashboard
- Create Assignment
- View Assignment
- Announcement

TODO: Integrasi dengan route
- Pastikan semua route sudah didefinisikan di web.php
- Route yang digunakan: teacher.dashboard, teacher.assignments.create,
teacher.assignments.index, teacher.announcements.index
--}}

<nav class="flex flex-col pt-4">
    {{-- Dashboard --}}
    <a href="{{ route('teacher.dashboard') }}"
        class="{{ request()->routeIs('teacher.dashboard') ? 'bg-blue-400' : 'hover:bg-gray-200' }} text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block transition-colors">
        Dashboard
    </a>

    {{-- Create Assignment --}}
    <a href="{{ route('teacher.assignments.create') }}"
        class="{{ request()->routeIs('teacher.assignments.create') ? 'bg-blue-400' : 'hover:bg-gray-200' }} text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block transition-colors">
        Create Assignment
    </a>

    {{-- View Assignment --}}
    <a href="{{ route('teacher.assignments.index') }}"
        class="{{ request()->routeIs('teacher.assignments.index') || request()->routeIs('teacher.assignments.show') ? 'bg-blue-400' : 'hover:bg-gray-200' }} text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block transition-colors">
        View Assignment
    </a>

    {{-- Announcement --}}
    <a href="{{ route('teacher.announcements.index') }}"
        class="{{ request()->routeIs('teacher.announcements.index') ? 'bg-blue-400' : 'hover:bg-gray-200' }} text-black px-6 py-3 font-medium text-sm uppercase tracking-wide block transition-colors">
        Announcement
    </a>
</nav>