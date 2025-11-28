{{-- filepath: resources/views/components/sidebar/student.blade.php --}}

{{--
KOMPONEN: Sidebar untuk Murid

Menampilkan menu navigasi khusus murid:
- Dashboard
- Assignment (untuk melihat dan submit tugas)

TODO: Integrasi dengan route
- Route yang digunakan: student.dashboard
--}}

<nav class="flex flex-col pt-4">
    {{-- Dashboard --}}
    <a href="{{ route('student.dashboard') }}"
        class="{{ request()->routeIs('student.dashboard') ? 'bg-blue-400' : 'hover:bg-gray-200' }} text-black px-6 py-4 font-medium text-sm uppercase tracking-wide block transition-colors">
        Dashboard
    </a>

    {{-- Assignment --}}
    <a href="#assignments-section"
        class="text-black px-6 py-4 font-medium text-sm uppercase tracking-wide hover:bg-gray-200 block transition-colors">
        Assignment
    </a>
</nav>