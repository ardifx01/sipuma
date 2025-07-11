@extends('layouts.app')

@section('title', 'Detail Review Publikasi - Dosen Dashboard')

@section('content')
<div class="min-h-screen bg-orange-50 p-6 space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 shadow border border-orange-200 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-orange-600">Detail Review Publikasi</h1>
            <p class="text-gray-700 mt-1">Review detail publikasi mahasiswa bimbingan</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('dashboard.dosen-review') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar
            </a>
            @if($publication->file_path)
                <a href="{{ route('publications.download', $publication->id) }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center text-sm">
                    <i class="fas fa-download mr-2"></i>
                    Download File
                </a>
            @endif
        </div>
    </div>

    <!-- Informasi Mahasiswa -->
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h2 class="text-lg font-bold text-orange-600 mb-4 flex items-center">
            <i class="fas fa-user-graduate mr-2"></i>
            Informasi Mahasiswa
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-xl font-bold">{{ $publication->student->name }}</h3>
                    <p class="text-gray-700">{{ $publication->student->email }}</p>
                </div>
                @if($publication->student->studentProfile)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">NIM</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->nim }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Program Studi</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->program_studi }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Fakultas</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->fakultas }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Angkatan</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->angkatan }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal Submit</label>
                    <p class="text-gray-900">{{ $publication->submission_date->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Status Admin</label>
                    @if($publication->admin_status === 'pending')
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu</span>
                    @elseif($publication->admin_status === 'approved')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                    @elseif($publication->admin_status === 'rejected')
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">-</span>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Status Dosen</label>
                    @if($publication->dosen_status === 'pending')
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Menunggu</span>
                    @elseif($publication->dosen_status === 'approved')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Disetujui</span>
                    @elseif($publication->dosen_status === 'rejected')
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Ditolak</span>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">-</span>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Tipe Publikasi</label>
                    <span class="border border-orange-300 text-orange-600 px-2 py-1 rounded text-xs">{{ $publication->publicationType->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Publikasi -->
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h2 class="text-lg font-bold text-orange-600 mb-6 flex items-center">
            <i class="fas fa-file-alt mr-2"></i>
            Detail Publikasi
        </h2>
        <div class="space-y-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Judul Publikasi</label>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $publication->title }}</p>
            </div>
            @if($publication->abstract)
            <div>
                <label class="text-sm font-medium text-gray-500">Abstract</label>
                <div class="mt-2 p-4 bg-orange-50 rounded-lg">
                    <p class="text-gray-900">{{ $publication->abstract }}</p>
                </div>
            </div>
            @endif
            @if($publication->keywords)
            <div>
                <label class="text-sm font-medium text-gray-500">Keywords</label>
                <p class="text-gray-900 mt-1">{{ $publication->keywords }}</p>
            </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($publication->sumber_artikel)
                <div>
                    <label class="text-sm font-medium text-gray-500">Sumber Artikel</label>
                    <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->sumber_artikel }}</p>
                </div>
                @endif
                @if($publication->tipe_publikasi)
                <div>
                    <label class="text-sm font-medium text-gray-500">Tipe Publikasi</label>
                    <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ is_array($publication->tipe_publikasi) ? implode(', ', $publication->tipe_publikasi) : $publication->tipe_publikasi }}</p>
                </div>
                @endif
            </div>
            @if($publication->journal_name || $publication->doi || $publication->issn)
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold text-orange-600 mb-4">Informasi Jurnal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($publication->journal_name)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Jurnal</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->journal_name }}</p>
                    </div>
                    @endif
                    @if($publication->journal_url)
                    <div>
                        <label class="text-sm font-medium text-gray-500">URL Jurnal</label>
                        <div class="bg-orange-50 px-3 py-2 rounded-lg">
                            <a href="{{ $publication->journal_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                {{ Str::limit($publication->journal_url, 50) }}
                            </a>
                        </div>
                    </div>
                    @endif
                    @if($publication->indexing)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Indexing</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->indexing }}</p>
                    </div>
                    @endif
                    @if($publication->doi)
                    <div>
                        <label class="text-sm font-medium text-gray-500">DOI</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg font-mono">{{ $publication->doi }}</p>
                    </div>
                    @endif
                    @if($publication->issn)
                    <div>
                        <label class="text-sm font-medium text-gray-500">ISSN</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg font-mono">{{ $publication->issn }}</p>
                    </div>
                    @endif
                    @if($publication->publisher)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Publisher</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->publisher }}</p>
                    </div>
                    @endif
                    @if($publication->publication_date)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Publikasi</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->publication_date->format('d F Y') }}</p>
                    </div>
                    @endif
                    @if($publication->volume)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Volume</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->volume }}</p>
                    </div>
                    @endif
                    @if($publication->issue)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Issue</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->issue }}</p>
                    </div>
                    @endif
                    @if($publication->pages)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Halaman</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->pages }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @if($publication->hki_creator || $publication->hki_certificate)
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold text-orange-600 mb-4">Informasi HKI</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($publication->hki_creator)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Creator</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->hki_creator }}</p>
                    </div>
                    @endif
                    @if($publication->hki_publication_date)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Publikasi HKI</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->hki_publication_date->format('d F Y') }}</p>
                    </div>
                    @endif
                    @if($publication->hki_certificate)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sertifikat</label>
                        <p class="text-gray-700 bg-orange-50 px-3 py-2 rounded-lg">{{ $publication->hki_certificate }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 