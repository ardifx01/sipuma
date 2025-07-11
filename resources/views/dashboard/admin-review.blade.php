@extends('layouts.app')

@section('title', 'Review Publikasi - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-orange-50">
    <div class="p-6 space-y-8">
        <!-- Header sederhana -->
        <div class="bg-white rounded-xl p-8 text-gray-900 shadow border border-orange-200 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-orange-600">Review Publikasi</h1>
                <p class="text-gray-700 text-base">Review dan verifikasi publikasi mahasiswa</p>
                <p class="text-gray-500 text-sm mt-1">Kelola semua submission yang menunggu review</p>
            </div>
            <div class="text-right">
                <a href="{{ route('dashboard') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-all border border-orange-600">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Daftar Publikasi dengan desain sederhana -->
        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Publikasi Menunggu Review
                </h2>
                <form method="GET" action="" class="flex items-center gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM atau Nama Mahasiswa..." class="border border-orange-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 w-full md:w-64" />
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold">
                        <i class="fas fa-search mr-1"></i> Cari
                    </button>
                </form>
            </div>
            <div class="p-6">
                @if($publications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-orange-50 border-b border-orange-200">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Mahasiswa</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Judul Publikasi</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tipe</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tanggal Submit</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-orange-100">
                                @foreach($publications as $publication)
                                <tr class="hover:bg-orange-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-orange-200 rounded-lg flex items-center justify-center text-orange-600 font-semibold text-sm">
                                                {{ substr($publication->student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $publication->student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $publication->student->email }}</div>
                                                @if($publication->student->studentProfile)
                                                    <div class="text-xs text-gray-400">{{ $publication->student->studentProfile->nim }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <div class="font-semibold text-gray-900">{{ Str::limit($publication->title, 60) }}</div>
                                            @if($publication->journal_name)
                                                <div class="text-sm text-gray-500">{{ Str::limit($publication->journal_name, 40) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                            {{ $publication->publicationType->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $publication->submission_date->format('d/m/Y') }}
                                            <div class="text-xs text-gray-500">{{ $publication->submission_date->diffForHumans() }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                            <i class="fas fa-clock mr-1"></i>Menunggu Review
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            @if($publication->dosen_status === 'approved' && $publication->admin_status === 'pending')
                                                <a href="{{ route('dashboard.admin-review-detail', $publication->id) }}" 
                                                   class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-orange-600">
                                                    <i class="fas fa-clipboard-check mr-1"></i>
                                                    Review
                                                </a>
                                            @else
                                                <a href="{{ route('dashboard.admin-review-detail', $publication->id) }}" 
                                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-gray-300">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat Detail
                                                </a>
                                            @endif
                                            @if($publication->file_path)
                                                <a href="{{ route('publications.download', $publication->id) }}" 
                                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-gray-300">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Download
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
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-orange-200">
                            <i class="fas fa-check-circle text-3xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Publikasi Menunggu Review</h3>
                        <p class="text-gray-600">Semua publikasi telah direview atau belum ada submission baru.</p>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('input[name="search"]');
    const tableBody = document.querySelector('table tbody');
    if (input && tableBody) {
        let timeout = null;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const val = input.value;
                fetch(`/admin/ajax-search-publications?type=pending&search=${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        if (data.data.length === 0) {
                            html = `<tr><td colspan='6' class='text-center py-8 text-gray-500'>Tidak ada hasil</td></tr>`;
                        } else {
                            data.data.forEach(pub => {
                                html += `<tr class='border-b border-orange-100 hover:bg-orange-50'><td class='px-6 py-4 text-gray-700'>${pub.student_name}</td><td class='px-6 py-4 font-medium text-gray-900'>${pub.title}</td><td class='px-6 py-4 text-gray-700'>${pub.type}</td><td class='px-6 py-4 text-gray-700'>${pub.admin_reviewed_at}</td><td class='px-6 py-4'><span class='inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200'><i class='fas fa-clock mr-1'></i>Menunggu</span></td><td class='px-6 py-4'><a href='${pub.detail_url}' class='bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-orange-600 mr-2'><i class='fas fa-eye mr-1'></i> Detail</a>${pub.download_url ? `<a href='${pub.download_url}' class='bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-gray-300'><i class='fas fa-download mr-1'></i> Download</a>` : ''}</td></tr>`;
                            });
                        }
                        tableBody.innerHTML = html;
                    });
            }, 300);
        });
    }
});
</script>
@endpush 