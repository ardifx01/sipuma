@extends('layouts.app')

@section('title', 'Dashboard Dosen - Sipuma')

@section('content')
<div class="bg-orange-50">
    <div class="p-6 space-y-8">
        <!-- Header -->
        <div class="bg-white rounded-xl p-8 text-gray-900 shadow border border-orange-200 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-orange-600">Dashboard Dosen</h1>
                <p class="text-gray-700 text-base">Selamat datang, {{ auth()->user()->name }}!</p>
                <p class="text-gray-500 text-sm mt-1">Kelola dan review publikasi mahasiswa bimbingan</p>
            </div>
            <div class="text-right">
                <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                    <p class="text-gray-500 text-xs">NIDN</p>
                    <p class="text-orange-600 font-semibold">{{ auth()->user()->dosenProfile->nidn ?? 'Belum diisi' }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-dashboard-card title="Mahasiswa Bimbingan">{{ $totalStudents }}</x-dashboard-card>
            <x-dashboard-card title="Menunggu Review">{{ $pendingReviews }}</x-dashboard-card>
            <x-dashboard-card title="Sudah Direview">{{ $reviewedPublications }}</x-dashboard-card>
            <x-dashboard-card title="Total Publikasi">{{ $totalPublications }}</x-dashboard-card>
        </div>
        <div class="flex justify-end mt-2">
            <a href="{{ route('dashboard.dosen-all-publications') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center text-sm">
                <i class="fas fa-list mr-2"></i>
                Publikasi Saya
            </a>
        </div>

        <!-- Diagnosis Statistik -->
        <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded">
            <div class="font-bold mb-2 text-orange-600">Diagnosis Statistik:</div>
            <ul class="text-sm text-gray-700">
                <li>Admin Status Pending: <span class="font-semibold">{{ $pendingAdmin }}</span></li>
                <li>Admin Status Rejected: <span class="font-semibold">{{ $rejectedAdmin }}</span></li>
                <li>Dosen Status Lain: <span class="font-semibold">{{ $otherDosenStatus }}</span></li>
            </ul>
        </div>

        <!-- Publications to Review -->
        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Publikasi yang Perlu Direview
                </h2>
                <a href="{{ route('dashboard.dosen-review') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold flex items-center text-sm">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Review Publikasi
                </a>
            </div>
            <div class="p-6">
                @if($pendingPublications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-orange-200 rounded-lg">
                            <thead class="bg-orange-500 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status Admin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status Dosen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal Submit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPublications as $publication)
                                <tr class="even:bg-orange-50 odd:bg-white hover:bg-orange-100">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium">{{ $publication->student->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $publication->student->studentProfile->nim ?? 'NIM belum diisi' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">{{ Str::limit($publication->title, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($publication->abstract ?? '', 60) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="border border-orange-300 text-orange-600 px-2 py-1 rounded text-xs">{{ $publication->publicationType->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $publication->submission_date ? $publication->submission_date->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('publications.show', $publication) }}" class="text-orange-600 hover:underline" title="Lihat"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('dashboard.dosen-review-detail', $publication->id) }}" class="bg-orange-500 text-white px-2 py-1 rounded text-xs hover:bg-orange-600 flex items-center"><i class="fas fa-edit mr-1"></i>Review</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada publikasi yang perlu direview</h3>
                        <p class="text-gray-500">Semua publikasi mahasiswa bimbingan sudah direview</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- My Students & Recent Reviews -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Students List -->
            <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-orange-600 text-lg font-bold flex items-center">
                        <i class="fas fa-user-graduate mr-2"></i>
                        Mahasiswa Bimbingan
                    </h2>
                    <a href="{{ route('dashboard.manage-students') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($students->take(3) as $student)
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $student->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $student->nim }} • {{ $student->major }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium">{{ $student->publications->count() }} publikasi</div>
                            <div class="text-xs text-gray-500">{{ $student->publications->where('dosen_status', 'pending')->count() }} menunggu review</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-user-graduate text-2xl mb-2"></i>
                        <p>Belum ada mahasiswa bimbingan</p>
                    </div>
                    @endforelse
                    
                    @if($students->count() > 3)
                    <div class="text-center py-3">
                        <span class="text-sm text-gray-500">+{{ $students->count() - 3 }} mahasiswa lainnya</span>
                        <br>
                        <a href="{{ route('dashboard.manage-students') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                            Lihat Semua Mahasiswa
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            <!-- Recent Reviews -->
            <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
                <h2 class="text-orange-600 text-lg font-bold flex items-center mb-4">
                    <i class="fas fa-history mr-2"></i>
                    Review Terbaru
                </h2>
                <div class="space-y-3">
                    @forelse($recentReviews as $review)
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-semibold text-sm">{{ Str::limit($review->publication->title, 40) }}</h3>
                            <p class="text-xs text-gray-500">{{ $review->publication->student->name }} • {{ $review->review_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($review->status === 'approved')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                            @elseif($review->status === 'rejected')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                            @else
                                <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-history text-2xl mb-2"></i>
                        <p>Belum ada review</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 