@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa - Sipuma')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-orange-600">Dashboard Mahasiswa</h1>
                <p class="text-gray-700">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">NIM</p>
                <p class="text-sm font-medium">{{ auth()->user()->studentProfile->nim ?? 'Belum diisi' }}</p>
            </div>
        </div>

        <!-- Success Notification -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Error Notification -->
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Error!</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard-card title="Total Publikasi">{{ $totalPublications }}</x-dashboard-card>
            <x-dashboard-card title="Menunggu Review">{{ $pendingPublications }}</x-dashboard-card>
            <x-dashboard-card title="Disetujui">{{ $approvedPublications }}</x-dashboard-card>
            <x-dashboard-card title="Ditolak">{{ $rejectedPublications }}</x-dashboard-card>
        </div>

        <!-- My Publications -->
        <div class="bg-white shadow rounded-lg p-6 border border-orange-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-orange-600 text-lg font-bold flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Publikasi Saya
                </h2>
                <a href="{{ route('publications.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center text-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Publikasi
                </a>
            </div>
            @if($publications->count() > 0)
                <!-- Latest Publication Highlight -->
                @if(session('success') && $publications->count() > 0)
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-sm font-medium text-green-800">Publikasi Terbaru</h3>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-green-300">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 mb-1">{{ $publications->first()->title }}</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($publications->first()->abstract, 100) }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>Status: 
                                        @if($publications->first()->admin_status === 'pending')
                                            <span class="text-orange-600 font-medium">Menunggu Review</span>
                                        @elseif($publications->first()->admin_status === 'approved')
                                            <span class="text-green-600 font-medium">Disetujui</span>
                                        @else
                                            <span class="text-red-600 font-medium">Ditolak</span>
                                        @endif
                                    </span>
                                    <span>Upload: {{ $publications->first()->submission_date->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('publications.show', $publications->first()) }}" class="text-green-600 hover:text-green-700" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('publications.download', $publications->first()) }}" class="text-green-600 hover:text-green-700" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="overflow-x-auto">
                    <x-dashboard-table :head="'<tr><th>Judul</th><th>Jenis</th><th>Status Admin</th><th>Status Dosen</th><th>Tanggal Submit</th><th>Aksi</th></tr>'">
                        @foreach($publications as $publication)
                        <tr class="even:bg-orange-50 odd:bg-white hover:bg-orange-100">
                            <td>
                                <div class="font-medium">{{ Str::limit($publication->title, 40) }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($publication->abstract, 60) }}</div>
                            </td>
                            <td>
                                <span class="border border-orange-300 text-orange-600 px-2 py-1 rounded text-xs">{{ $publication->publicationType->name }}</span>
                            </td>
                            <td>
                                @if($publication->admin_status === 'pending')
                                    <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu</span>
                                @elseif($publication->admin_status === 'approved')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($publication->dosen_status === 'pending')
                                    <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu</span>
                                @elseif($publication->dosen_status === 'approved')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                                @elseif($publication->dosen_status === 'rejected')
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">-</span>
                                @endif
                            </td>
                            <td>{{ $publication->submission_date->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('publications.show', $publication) }}" class="text-orange-600 hover:underline" title="Lihat"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('publications.edit', $publication) }}" class="text-orange-600 hover:underline" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('publications.download', $publication) }}" class="text-orange-600 hover:underline" title="Download"><i class="fas fa-download"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </x-dashboard-table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada publikasi</h3>
                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan publikasi pertama Anda</p>
                    <a href="{{ route('publications.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Publikasi Pertama
                    </a>
                </div>
            @endif
        </div>

        <!-- Profile Information & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Academic Info -->
            <div class="bg-white shadow rounded-lg p-6 border border-orange-200">
                <h2 class="text-orange-600 text-lg font-bold flex items-center mb-4">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Informasi Akademik
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm">NIM:</span>
                        <span class="font-medium">{{ auth()->user()->studentProfile->nim ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Fakultas:</span>
                        <span class="font-medium">{{ auth()->user()->studentProfile->faculty ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Program Studi:</span>
                        <span class="font-medium">{{ auth()->user()->studentProfile->major ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Angkatan:</span>
                        <span class="font-medium">{{ auth()->user()->studentProfile->year ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Pembimbing:</span>
                        <span class="font-medium">{{ auth()->user()->studentProfile->supervisor->name ?? 'Belum ditentukan' }}</span>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="{{ route('profile.edit') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 text-sm">Edit Profil</a>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6 border border-orange-200">
                <h2 class="text-orange-600 text-lg font-bold flex items-center mb-4">
                    <i class="fas fa-bolt mr-2"></i>
                    Aksi Cepat
                </h2>
                <div class="space-y-3">
                    <a href="{{ route('publications.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center text-sm w-full">
                        <i class="fas fa-plus mr-2"></i>
                        Upload Publikasi Baru
                    </a>
                    <a href="{{ route('publications.index') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm w-full">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Publikasi
                    </a>
                    <a href="{{ route('profile.edit') }}" class="bg-orange-50 text-orange-600 border border-orange-200 px-4 py-2 rounded hover:bg-orange-100 flex items-center text-sm w-full">
                        <i class="fas fa-user-edit mr-2"></i>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.bg-green-50, .bg-red-50');
    
    notifications.forEach(notification => {
        setTimeout(() => {
            if (notification && notification.parentElement) {
                notification.style.transition = 'opacity 0.5s ease-out';
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
    
    // Add click to dismiss functionality
    const dismissButtons = document.querySelectorAll('[onclick*="remove()"]');
    dismissButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const notification = this.closest('.bg-green-50, .bg-red-50');
            if (notification) {
                notification.style.transition = 'opacity 0.3s ease-out';
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        });
    });
});
</script>
@endsection 