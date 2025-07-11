@extends('layouts.app')

@section('title', 'Semua Publikasi Mahasiswa - Admin')

@section('content')
<div class="min-h-screen bg-orange-50 p-6 space-y-8">
    <div class="bg-white rounded-xl p-6 shadow border border-orange-200 flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-orange-600">Semua Publikasi Mahasiswa</h1>
            <p class="text-gray-700 mt-1">Daftar seluruh publikasi mahasiswa, lengkap dengan status admin dan dosen.</p>
        </div>
        <div class="text-right">
            <a href="{{ route('dashboard') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <form method="GET" action="" class="mb-4 flex flex-col md:flex-row md:items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, nama, atau email mahasiswa..." class="border border-orange-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 w-full md:w-64" />
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold">
                <i class="fas fa-search mr-1"></i> Cari
            </button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-orange-200 rounded-lg">
                <thead class="bg-orange-500 text-white">
                    <tr>
                        <th class="py-2 px-4">Mahasiswa</th>
                        <th class="py-2 px-4">NIM</th>
                        <th class="py-2 px-4">Judul</th>
                        <th class="py-2 px-4">Tipe</th>
                        <th class="py-2 px-4">Tanggal Submit</th>
                        <th class="py-2 px-4">Status Admin</th>
                        <th class="py-2 px-4">Status Dosen</th>
                        <th class="py-2 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publications as $publication)
                    <tr class="even:bg-orange-50 odd:bg-white hover:bg-orange-100">
                        <td class="py-2 px-4">
                            <div class="font-bold">{{ $publication->student->name }}</div>
                            <div class="text-sm text-gray-500">{{ $publication->student->email }}</div>
                        </td>
                        <td class="py-2 px-4">
                            {{ $publication->student->studentProfile->nim ?? '-' }}
                        </td>
                        <td class="py-2 px-4">
                            <div class="font-semibold">{{ Str::limit($publication->title, 60) }}</div>
                        </td>
                        <td class="py-2 px-4">
                            <span class="border border-orange-300 text-orange-600 px-2 py-1 rounded text-xs">{{ $publication->publicationType->name }}</span>
                        </td>
                        <td class="py-2 px-4">
                            <div class="text-sm">{{ $publication->submission_date ? $publication->submission_date->format('d/m/Y') : '-' }}</div>
                        </td>
                        <td class="py-2 px-4">
                            @if($publication->admin_status === 'pending')
                                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu</span>
                            @elseif($publication->admin_status === 'approved')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                            @elseif($publication->admin_status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">-</span>
                            @endif
                        </td>
                        <td class="py-2 px-4">
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
                        <td class="py-2 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('dashboard.admin-review-detail', $publication->id) }}" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 flex items-center text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a>
                                @if($publication->file_path)
                                    <a href="{{ route('publications.download', $publication->id) }}" class="bg-white text-orange-600 border border-orange-500 px-3 py-1 rounded hover:bg-orange-50 flex items-center text-sm">
                                        <i class="fas fa-download mr-1"></i>
                                        Download
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">Belum ada publikasi mahasiswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $publications->links() }}
        </div>
    </div>
</div>
@endsection 