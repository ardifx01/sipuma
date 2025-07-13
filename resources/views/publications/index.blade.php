@extends('layouts.app')

@section('title', 'Publikasi Saya')

@section('content')
<div class="bg-orange-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Publikasi Saya</h1>
                    <p class="text-gray-600">Kelola dan pantau publikasi Anda</p>
                </div>
                
                <a href="{{ route('publications.create') }}" class="inline-flex items-center px-4 py-2 bg-white hover:bg-orange-50 text-orange-600 border border-orange-300 hover:border-orange-400 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Upload Publikasi Baru
                </a>
            </div>
        </div>

        <!-- Success Notification -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
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
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
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

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Publikasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $publications->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Accepted (LoA)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $publications->where('publication_status', 'accepted')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Published</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $publications->where('publication_status', 'published')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Disetujui</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $publications->where('admin_status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $publications->where('admin_status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publications List -->
        @if($publications->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-orange-200">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-orange-200">
                        <thead>
                            <tr>
                                <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Publikasi</th>
                                <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                                <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left py-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                                <th class="text-center py-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-200">
                            @foreach($publications as $publication)
                            <tr class="hover:bg-orange-50 transition-colors duration-200">
                                <td class="py-4 px-4">
                                    <div>
                                        <h3 class="font-medium text-gray-900 mb-1">{{ Str::limit($publication->title, 50) }}</h3>
                                        @if($publication->abstract)
                                        <p class="text-sm text-gray-600">{{ Str::limit($publication->abstract, 80) }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $publication->sumber_artikel }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if(is_array($publication->tipe_publikasi))
                                            @foreach($publication->tipe_publikasi as $tipe)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $tipe }}
                                            </span>
                                            @endforeach
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $publication->tipe_publikasi }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="space-y-1">
                                        <!-- Publication Status -->
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $publication->getStatusBadgeClass() }}">
                                                {{ $publication->getStatusLabel() }}
                                            </span>
                                        </div>
                                        
                                        <!-- Admin Status -->
                                        @if($publication->admin_status === 'pending')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                                <span class="text-xs font-medium text-yellow-800">Menunggu Review</span>
                                            </div>
                                        @elseif($publication->admin_status === 'approved')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                <span class="text-xs font-medium text-green-800">Disetujui</span>
                                            </div>
                                        @elseif($publication->admin_status === 'rejected')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                <span class="text-xs font-medium text-red-800">Ditolak</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-600">{{ $publication->submission_date->format('d M Y') }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('publications.show', $publication) }}" class="p-2 text-gray-400 hover:text-orange-600 rounded-lg hover:bg-orange-100" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('publications.download', $publication) }}" class="p-2 text-gray-400 hover:text-orange-600 rounded-lg hover:bg-orange-100" title="Download">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                        
                                        @if($publication->admin_status === 'pending')
                                        <a href="{{ route('publications.edit', $publication) }}" class="p-2 text-gray-400 hover:text-orange-600 rounded-lg hover:bg-orange-100" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-orange-200">
            <div class="p-12 text-center">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada Publikasi</h3>
                <p class="text-gray-600 mb-6">Mulai dengan mengupload publikasi pertama Anda</p>
                <a href="{{ route('publications.create') }}" class="inline-flex items-center px-4 py-2 bg-white hover:bg-orange-50 text-orange-600 border border-orange-300 hover:border-orange-400 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Upload Publikasi Baru
                </a>
            </div>
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
@endsection 