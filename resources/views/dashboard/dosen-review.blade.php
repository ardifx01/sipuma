@extends('layouts.app')

@section('title', 'Review Publikasi - Dosen Dashboard')

@section('content')
<div class="min-h-screen bg-orange-50 p-6 space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 shadow border border-orange-200 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-orange-600">Review Publikasi Mahasiswa</h1>
            <p class="text-gray-700 mt-1">Review dan verifikasi publikasi mahasiswa bimbingan</p>
        </div>
        <div class="text-right">
            <a href="{{ route('dashboard') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Daftar Publikasi -->
    <div class="bg-white rounded-xl shadow border border-orange-200">
        <div class="p-6">
            <h2 class="text-lg font-bold text-orange-600 mb-6 flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>
                Publikasi yang Menunggu Review Dosen
            </h2>
            @if($publications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-orange-200 rounded-lg">
                        <thead class="bg-orange-500 text-white">
                            <tr>
                                <th class="py-2 px-4">Mahasiswa</th>
                                <th class="py-2 px-4">Judul Publikasi</th>
                                <th class="py-2 px-4">Tipe</th>
                                <th class="py-2 px-4">Tanggal Submit</th>
                                <th class="py-2 px-4">Status Admin</th>
                                <th class="py-2 px-4">Status Dosen</th>
                                <th class="py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($publications as $publication)
                            <tr class="even:bg-orange-50 odd:bg-white hover:bg-orange-100">
                                <td class="py-2 px-4">
                                    <div class="font-bold">{{ $publication->student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $publication->student->email }}</div>
                                    @if($publication->student->studentProfile)
                                        <div class="text-xs text-gray-500">{{ $publication->student->studentProfile->nim }}</div>
                                    @endif
                                </td>
                                <td class="py-2 px-4">
                                    <div class="font-semibold">{{ Str::limit($publication->title, 60) }}</div>
                                    @if($publication->journal_name)
                                        <div class="text-sm text-gray-500">{{ Str::limit($publication->journal_name, 40) }}</div>
                                    @endif
                                </td>
                                <td class="py-2 px-4">
                                    <span class="border border-orange-300 text-orange-600 px-2 py-1 rounded text-xs">{{ $publication->publicationType->name }}</span>
                                </td>
                                <td class="py-2 px-4">
                                    <div class="text-sm">{{ $publication->submission_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $publication->submission_date->diffForHumans() }}</div>
                                </td>
                                <td class="py-2 px-4">
                                    @if($publication->admin_status === 'pending')
                                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu Admin</span>
                                    @elseif($publication->admin_status === 'approved')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui Admin</span>
                                    @elseif($publication->admin_status === 'rejected')
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak Admin</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4">
                                    @if($publication->dosen_status === 'pending')
                                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu Review</span>
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
                                        <a href="{{ route('dashboard.dosen-review-detail', $publication->id) }}" class="bg-orange-500 text-white px-3 py-1 rounded hover:bg-orange-600 flex items-center text-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            Review
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🎉</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Publikasi Menunggu Review</h3>
                    <p class="text-gray-500">Semua publikasi mahasiswa bimbingan telah direview atau belum ada yang disetujui admin.</p>
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
@endsection 