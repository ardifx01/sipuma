@extends('layouts.app')

@section('title', 'Kelola Mahasiswa - Dashboard')

@section('content')
<div class="bg-gray-50">
    <div class="p-6 space-y-8">
        <!-- Header dengan desain modern -->
        <div class="relative overflow-hidden bg-gradient-to-r from-gray-700 to-gray-800 rounded-2xl p-8 text-white shadow-xl">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2 text-white">
                            @if(auth()->user()->hasRole('admin'))
                                Kelola Mahasiswa dan Dosen Pembimbing
                            @else
                                Mahasiswa Bimbingan
                            @endif
                        </h1>
                        <p class="text-gray-200 text-lg">
                            @if(auth()->user()->hasRole('admin'))
                                Assign dan kelola dosen pembimbing untuk mahasiswa
                            @else
                                Daftar mahasiswa yang Anda bimbing
                            @endif
                        </p>
                        <p class="text-gray-300 text-sm mt-1">Kelola data mahasiswa dan pembimbing</p>
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
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-user-graduate mr-3"></i>
                        @if(auth()->user()->hasRole('admin'))
                            Daftar Semua Mahasiswa
                        @else
                            Mahasiswa Bimbingan
                        @endif
                    </h2>
                    <div class="text-white text-sm font-medium">
                        Total: {{ $students->total() }} mahasiswa
                    </div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" 
                               placeholder="Cari mahasiswa berdasarkan nama, NIM, atau email..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-900">
                    </div>
                    <button type="button" id="clearSearch" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors hidden">
                        <i class="fas fa-times mr-2"></i>
                        Reset
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div id="studentsTable">
                    @if($students->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-100 border-b border-gray-200">
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Mahasiswa</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">NIM</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Program Studi</th>
                                        <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Fakultas</th>
                                        @if(auth()->user()->hasRole('admin'))
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Dosen Pembimbing</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Aksi</th>
                                        @else
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Publikasi</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Status Review</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($students as $student)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-gradient-to-r from-gray-600 to-gray-700 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                                                    {{ substr($student->user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 text-base">{{ $student->user->name }}</div>
                                                    <div class="text-sm text-gray-600 font-medium">{{ $student->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-gray-900 font-bold">{{ $student->nim }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 font-medium">{{ $student->major }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-gray-900 font-medium">{{ $student->faculty }}</span>
                                        </td>
                                        
                                        @if(auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4">
                                                @if($student->supervisor)
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-bold text-gray-900">{{ $student->supervisor->name }}</span>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
                                                            Assigned
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-300">
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
                                                            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-2 px-4 rounded-lg transition-all duration-200 text-xs border border-red-300" onclick="return confirm('Yakin ingin menghapus dosen pembimbing?')">
                                                                <i class="fas fa-times mr-1"></i>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <div class="relative">
                                                        <button class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 text-xs" onclick="toggleDropdown('dropdown-{{ $student->user_id }}')">
                                                            <i class="fas fa-user-plus mr-1"></i>
                                                            Assign
                                                        </button>
                                                        <div id="dropdown-{{ $student->user_id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                            @foreach($dosen as $d)
                                                                <form action="{{ route('dashboard.assign-supervisor', $student->user_id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="supervisor_id" value="{{ $d->id }}">
                                                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 font-medium">
                                                                        {{ $d->name }}
                                                                    </button>
                                                                </form>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('dashboard.student-publications', $student->user_id) }}" class="ml-2 bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold py-2 px-4 rounded-lg transition-all duration-200 text-xs border border-orange-300">
                                                        <i class="fas fa-list mr-1"></i> Lihat Publikasi
                                                    </a>
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                                                    {{ $student->publications->count() }} publikasi
                                                </span>
                                                <a href="{{ route('dashboard.dosen-student-publications', $student->user_id) }}" class="ml-2 bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold py-2 px-4 rounded-lg transition-all duration-200 text-xs border border-orange-300">
                                                    <i class="fas fa-list mr-1"></i> Lihat Publikasi
                                                </a>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $pendingCount = $student->publications->where('admin_status', 'approved')->where('dosen_status', 'pending')->count();
                                                @endphp
                                                @if($pendingCount > 0)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-300">
                                                        {{ $pendingCount }} menunggu review
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
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
                        <div class="mt-6">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-3xl text-gray-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                @if(auth()->user()->hasRole('admin'))
                                    Belum Ada Mahasiswa
                                @else
                                    Belum Ada Mahasiswa Bimbingan
                                @endif
                            </h3>
                            <p class="text-gray-600 font-medium">
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
        </div>

        @if(auth()->user()->hasRole('dosen'))
        <!-- Quick Actions untuk Dosen -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-bolt mr-3"></i>
                    Aksi Cepat
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('dashboard.dosen-review') }}" class="bg-black hover:bg-gray-800 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 flex items-center justify-center shadow-lg border-2 border-gray-300">
                        <i class="fas fa-clipboard-check mr-3 text-xl"></i>
                        Review Publikasi
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-4 px-6 rounded-xl transition-all duration-200 border border-gray-300 flex items-center justify-center">
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
let searchTimeout;

// AJAX Search Function
function performSearch(searchTerm) {
    const tableContainer = document.getElementById('studentsTable');
    
    // Show loading state
    tableContainer.innerHTML = `
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-spinner fa-spin text-3xl text-gray-500"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Mencari...</h3>
            <p class="text-gray-600 font-medium">Mohon tunggu sebentar</p>
        </div>
    `;

    // Make AJAX request
    fetch(`{{ route('dashboard.manage-students') }}?search=${encodeURIComponent(searchTerm)}&ajax=1`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        // Extract table content from response
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTableContent = doc.getElementById('studentsTable');
        
        if (newTableContent) {
            tableContainer.innerHTML = newTableContent.innerHTML;
            
            // Re-initialize dropdown functionality for new content
            initializeDropdowns();
        } else {
            // Fallback if no table found
            tableContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-gray-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada hasil</h3>
                    <p class="text-gray-600 font-medium">Coba kata kunci lain</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        tableContainer.innerHTML = `
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                <p class="text-gray-600 font-medium">Terjadi kesalahan saat mencari</p>
                <button onclick="location.reload()" class="mt-4 bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg">
                    Coba Lagi
                </button>
            </div>
        `;
    });
}

// Initialize dropdown functionality
function initializeDropdowns() {
    // Re-attach click event listeners for dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
}

// Search input event listener
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.trim();
    const clearButton = document.getElementById('clearSearch');
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    // Show/hide clear button
    if (searchTerm.length > 0) {
        clearButton.classList.remove('hidden');
    } else {
        clearButton.classList.add('hidden');
    }
    
    // Debounce search (wait 500ms after user stops typing)
    searchTimeout = setTimeout(() => {
        if (searchTerm.length > 0) {
            performSearch(searchTerm);
        } else {
            // If search is empty, reload original content
            location.reload();
        }
    }, 500);
});

// Clear search button
document.getElementById('clearSearch').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    this.classList.add('hidden');
    location.reload();
});

function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (dropdown.classList.contains('hidden')) {
        // Hide all other dropdowns first
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
        // Show this dropdown
        dropdown.classList.remove('hidden');
    } else {
        dropdown.classList.add('hidden');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[id^="dropdown-"]') && !event.target.closest('button')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

// Initialize dropdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDropdowns();
});
</script>
@endsection 