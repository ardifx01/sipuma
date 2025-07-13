@extends('layouts.app')

@section('title', 'Dashboard Admin - Sipuma')

@section('content')
<div class="bg-orange-50">
    <div class="p-6 space-y-8">
        <!-- Header sederhana -->
        <div class="bg-white rounded-xl p-8 text-gray-900 shadow border border-orange-200 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-orange-600">Dashboard Admin</h1>
                <p class="text-gray-700 text-base">Selamat datang, {{ auth()->user()->name }}!</p>
                <p class="text-gray-500 text-sm mt-1">Kelola dan review publikasi mahasiswa</p>
            </div>
            <div class="text-right">
                <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                    <p class="text-gray-500 text-xs">Terakhir login</p>
                    <p class="text-orange-600 font-semibold">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-dashboard-card title="Total Publikasi">{{ $totalPublications }}</x-dashboard-card>
            <x-dashboard-card title="Menunggu Review">{{ $pendingReviews }}</x-dashboard-card>
            <x-dashboard-card title="Publikasi Disetujui">{{ $approvedPublications }}</x-dashboard-card>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 gap-8">
            <!-- Recent Publications -->
            <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
                <div class="bg-orange-100 px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-lg font-bold text-orange-600 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        Publikasi Terbaru
                    </h2>
                    <form method="GET" action="" class="flex items-center gap-2 w-full md:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM atau Nama Mahasiswa..." class="border border-orange-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 w-full md:w-64" />
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                    </form>
                </div>
                <div class="p-6">
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse($recentPublications as $publication)
                        <div class="bg-orange-50 rounded-lg p-4 hover:bg-orange-100 transition-all border border-orange-100 hover:border-orange-300">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ Str::limit($publication->title, 50) }}</h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-user mr-1"></i>{{ $publication->student->name }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        <i class="fas fa-tag mr-1"></i>{{ $publication->publicationType->name }}
                                    </p>
                                    <!-- Status Review Dosen -->
                                    <div class="mt-2 flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Dosen:</span>
                                        @if($publication->dosen_status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <i class="fas fa-clock mr-1"></i>Menunggu Review
                                            </span>
                                        @elseif($publication->dosen_status === 'approved')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check mr-1"></i>Disetujui Dosen
                                            </span>
                                        @elseif($publication->dosen_status === 'rejected')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-times mr-1"></i>Ditolak Dosen
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                <i class="fas fa-minus mr-1"></i>Belum Review
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    @if($publication->admin_status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                            <i class="fas fa-clock mr-1"></i>Menunggu
                                        </span>
                                    @elseif($publication->admin_status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-200 text-orange-900 border border-orange-300">
                                            <i class="fas fa-check mr-1"></i>Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-700 border border-gray-300">
                                            <i class="fas fa-times mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-orange-100">
                                <i class="fas fa-inbox text-xl text-orange-300"></i>
                            </div>
                            <p class="font-medium text-gray-900">Belum ada publikasi</p>
                            <p class="text-sm text-gray-500">Publikasi akan muncul di sini</p>
                        </div>
                        @endforelse
                    </div>
                    <!-- Pagination dan tombol review tetap -->
                    @if($recentPublications->hasPages())
                    <div class="mt-6 flex justify-center">
                        <div class="flex items-center space-x-2">
                            @if($recentPublications->onFirstPage())
                                <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $recentPublications->previousPageUrl() }}" class="px-3 py-2 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-all">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif
                            <span class="px-3 py-2 text-gray-700 bg-white border border-orange-200 rounded-lg">
                                Halaman {{ $recentPublications->currentPage() }} dari {{ $recentPublications->lastPage() }}
                            </span>
                            @if($recentPublications->hasMorePages())
                                <a href="{{ $recentPublications->nextPageUrl() }}" class="px-3 py-2 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-all">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="mt-6">
                        <a href="{{ route('dashboard.admin-review') }}" class="w-full bg-orange-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-orange-600 transition-all flex items-center justify-center border border-orange-600">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            Review Publikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('input[name="search"]');
    const listContainer = document.querySelector('.space-y-4.max-h-96');
    if (input && listContainer) {
        let timeout = null;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const val = input.value;
                fetch(`/admin/ajax-search-publications?type=approved&search=${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        if (data.data.length === 0) {
                            html = `<div class='text-center py-8'><div class='w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-orange-100'><i class='fas fa-inbox text-xl text-orange-300'></i></div><p class='font-medium text-gray-900'>Tidak ada hasil</p><p class='text-sm text-gray-500'>Coba kata kunci lain</p></div>`;
                        } else {
                            data.data.forEach(pub => {
                                html += `<div class='bg-orange-50 rounded-lg p-4 hover:bg-orange-100 transition-all border border-orange-100 hover:border-orange-300'><div class='flex items-center justify-between'><div class='flex-1'><h3 class='font-semibold text-gray-900 text-sm'>${pub.title}</h3><p class='text-xs text-gray-600 mt-1'><i class='fas fa-user mr-1'></i>${pub.student_name}</p><p class='text-xs text-gray-600'><i class='fas fa-tag mr-1'></i>${pub.type}</p></div><div class='ml-4'><span class='inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-200 text-orange-900 border border-orange-300'><i class='fas fa-check mr-1'></i>Disetujui</span></div></div></div>`;
                            });
                        }
                        listContainer.innerHTML = html;
                    });
            }, 300);
        });
    }
});
</script>
@endpush 