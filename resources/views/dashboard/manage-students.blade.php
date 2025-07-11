@extends('layouts.app')

@section('title', 'Kelola Mahasiswa - Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="p-6 space-y-8">
        <!-- Header dengan desain modern -->
        <div class="relative overflow-hidden bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 rounded-2xl p-8 text-white shadow-xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">
                            @if(auth()->user()->hasRole('admin'))
                                Kelola Mahasiswa dan Dosen Pembimbing
                            @else
                                Mahasiswa Bimbingan
                            @endif
                        </h1>
                        <p class="text-blue-100 text-lg">
                            @if(auth()->user()->hasRole('admin'))
                                Assign dan kelola dosen pembimbing untuk mahasiswa
                            @else
                                Daftar mahasiswa yang Anda bimbing
                            @endif
                        </p>
                        <p class="text-blue-200 text-sm mt-1">Kelola data mahasiswa dan pembimbing</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('dashboard') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 backdrop-blur-sm border border-white border-opacity-30">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
        </div>

        <!-- Daftar Mahasiswa dengan desain modern -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-graduate mr-3"></i>
                    @if(auth()->user()->hasRole('admin'))
                        Daftar Semua Mahasiswa
                    @else
                        Mahasiswa Bimbingan
                    @endif
                </h2>
            </div>
            <div class="p-6">
                @if($students->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Mahasiswa</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">NIM</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Program Studi</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Fakultas</th>
                                    @if(auth()->user()->hasRole('admin'))
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Dosen Pembimbing</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                                    @else
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Publikasi</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Status Review</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($students as $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm">
                                                {{ substr($student->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $student->user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-gray-900 dark:text-white">{{ $student->nim }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">{{ $student->major }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">{{ $student->faculty }}</span>
                                    </td>
                                    
                                    @if(auth()->user()->hasRole('admin'))
                                        <td class="px-6 py-4">
                                            @if($student->supervisor)
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $student->supervisor->name }}</span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Assigned
                                                    </span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                    Belum diassign
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                @if($student->supervisor)
                                                    <form action="{{ route('dashboard.remove-supervisor', $student->user_id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 font-semibold py-1 px-3 rounded-lg transition-all duration-200 text-xs border border-red-300 dark:border-red-600" onclick="return confirm('Yakin ingin menghapus dosen pembimbing?')">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <div class="relative">
                                                    <button class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-1 px-3 rounded-lg transition-all duration-200 text-xs" onclick="toggleDropdown('dropdown-{{ $student->user_id }}')">
                                                        <i class="fas fa-user-plus mr-1"></i>
                                                        Assign
                                                    </button>
                                                    <div id="dropdown-{{ $student->user_id }}" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-10">
                                                        @foreach($dosen as $d)
                                                            <form action="{{ route('dashboard.assign-supervisor', $student->user_id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="supervisor_id" value="{{ $d->id }}">
                                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                    {{ $d->name }}
                                                                </button>
                                                            </form>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <a href="{{ route('dashboard.student-publications', $student->user_id) }}" class="ml-2 bg-orange-100 hover:bg-orange-200 text-orange-700 font-semibold py-1 px-3 rounded-lg transition-all duration-200 text-xs border border-orange-300">
                                                    <i class="fas fa-list mr-1"></i> Lihat Publikasi
                                                </a>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $student->publications->count() }} publikasi
                                            </span>
                                            <a href="{{ route('dashboard.dosen-student-publications', $student->user_id) }}" class="ml-2 bg-orange-100 hover:bg-orange-200 text-orange-700 font-semibold py-1 px-3 rounded-lg transition-all duration-200 text-xs border border-orange-300">
                                                <i class="fas fa-list mr-1"></i> Lihat Publikasi
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $pendingCount = $student->publications->where('admin_status', 'approved')->where('dosen_status', 'pending')->count();
                                            @endphp
                                            @if($pendingCount > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                    {{ $pendingCount }} menunggu review
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Semua selesai
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            @if(auth()->user()->hasRole('admin'))
                                Belum Ada Mahasiswa
                            @else
                                Belum Ada Mahasiswa Bimbingan
                            @endif
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            @if(auth()->user()->hasRole('admin'))
                                Mahasiswa akan muncul di sini setelah mendaftar
                            @else
                                Admin belum mengassign mahasiswa untuk Anda bimbing
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user()->hasRole('dosen'))
        <!-- Quick Actions untuk Dosen -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-3"></i>
                    Aksi Cepat
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('dashboard.dosen-review') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-clipboard-check mr-3 text-xl"></i>
                        Review Publikasi
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-4 px-6 rounded-xl transition-all duration-200 border border-gray-300 dark:border-gray-600 flex items-center justify-center">
                        <i class="fas fa-chart-bar mr-3 text-xl"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== id) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    }
});
</script>
@endsection 