@extends('layouts.app')

@section('title', 'Daftar Publikasi Mahasiswa')

@section('content')
<div class="min-h-screen bg-orange-50">
    <div class="p-6 space-y-8">
        <div class="bg-white rounded-xl p-8 text-gray-900 shadow border border-orange-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-orange-600 mb-1">Daftar Publikasi Mahasiswa</h1>
                <div class="text-gray-700 text-lg font-semibold">{{ $user->name }}</div>
                <div class="text-gray-500 text-sm">NIM: {{ $user->studentProfile->nim ?? '-' }}</div>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg transition-all border border-orange-600">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Publikasi {{ $user->name }}
                </h2>
                <form method="GET" action="" class="flex items-center gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, kata kunci, atau abstrak..." class="border border-orange-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 w-full md:w-64" />
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
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Judul</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">
                                        <a href="?sort=type{{ request('search') ? '&search='.urlencode(request('search')) : '' }}" class="flex items-center group">
                                            Tipe
                                            <svg class="w-4 h-4 ml-1 {{ request('sort') === 'type' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status Admin</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status Dosen</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tanggal Submit</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publications as $publication)
                                <tr class="border-b border-orange-100 hover:bg-orange-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ Str::limit($publication->title, 60) }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $publication->publicationType->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($publication->admin_status === 'pending')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($publication->admin_status === 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-error">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($publication->dosen_status === 'pending')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($publication->dosen_status === 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($publication->dosen_status === 'rejected')
                                            <span class="badge badge-error">Ditolak</span>
                                        @else
                                            <span class="badge badge-ghost">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $publication->submission_date ? $publication->submission_date->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if(auth()->user()->hasRole('admin'))
                                            @if($publication->admin_status === 'pending')
                                                <a href="{{ route('dashboard.admin-review-detail', $publication->id) }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-orange-600 mr-2">
                                                    <i class="fas fa-clipboard-check mr-1"></i> Review
                                                </a>
                                            @else
                                                <a href="{{ route('dashboard.admin-review-detail', $publication->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-gray-300 mr-2">
                                                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('publications.show', $publication) }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-orange-600 mr-2">
                                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        @endif
                                        @if($publication->file_path)
                                            <a href="{{ route('publications.download', $publication) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-all text-sm border border-gray-300">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $publications->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-orange-200">
                            <i class="fas fa-file-alt text-3xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Publikasi</h3>
                        <p class="text-gray-600">Publikasi milik mahasiswa ini akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 