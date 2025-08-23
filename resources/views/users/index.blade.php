@extends('layouts.app')

@section('title', 'Publikasi Mahasiswa dan Dosen')

@section('content')
<div class="min-h-screen bg-orange-50">
    <div class="p-6 space-y-8">
        <!-- Header dengan desain modern -->
        <div class="relative overflow-hidden bg-orange-600 rounded-2xl p-8 text-white shadow-xl border border-orange-200">
            <div class="absolute inset-0 bg-black opacity-5"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Daftar User Sistem</h1>
                        <p class="text-orange-100 text-lg">Lihat seluruh akun mahasiswa, dosen, dan admin</p>
                        <p class="text-orange-200 text-sm mt-1">Kelola akses dan data user</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('users.create') }}" class="bg-white hover:bg-orange-100 text-orange-600 font-semibold py-3 px-6 rounded-xl transition-all duration-200 border border-orange-300 shadow">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah User
                        </a>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600 dark:text-green-400"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600 dark:text-red-400"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table dengan desain modern -->
        <div class="bg-white rounded-2xl shadow-lg border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4">
                <h2 class="text-xl font-bold text-orange-700 flex items-center">
                    <i class="fas fa-file-alt mr-3"></i>
                    Daftar Seluruh Publikasi
                </h2>
            </div>
            <div class="p-6">
                @if($publications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-orange-50 border-b border-orange-200">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Judul</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nama Mahasiswa</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tipe</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-orange-100">
                                @foreach($publications as $publication)
                                <tr class="hover:bg-orange-50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ Str::limit($publication->title, 60) }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $publication->student->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $publication->publicationType->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($publication->admin_status === 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                                <i class="fas fa-clock mr-1"></i>Menunggu
                                            </span>
                                        @elseif($publication->admin_status === 'approved')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check mr-1"></i>Disetujui
                                            </span>
                                        @elseif($publication->admin_status === 'rejected')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-times mr-1"></i>Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                <i class="fas fa-minus mr-1"></i>{{ ucfirst($publication->admin_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-900">{{ $publication->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('publications.show', $publication->id) }}" class="bg-orange-100 hover:bg-orange-200 text-orange-700 font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-xs border border-orange-300">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>
                                            @if($publication->file_path)
                                            <a href="{{ asset('storage/' . $publication->file_path) }}" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-xs border border-gray-300">
                                                <i class="fas fa-download mr-1"></i> File
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada publikasi</h3>
                        <p class="text-gray-600">Publikasi akan muncul di sini setelah ditambahkan</p>
                    </div>
                @endif
                
                <!-- Pagination -->
                @if($publications->hasPages())
                <div class="mt-6">
                    {{ $publications->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 