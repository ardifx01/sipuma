@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa - Sipuma')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
@endsection 