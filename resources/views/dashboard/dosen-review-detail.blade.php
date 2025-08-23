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
                        <p class="text-gray-900">{{ $publication->student->studentProfile->nim ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Program Studi</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->major ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Fakultas</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->faculty ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Angkatan</label>
                        <p class="text-gray-900">{{ $publication->student->studentProfile->year ?? 'Belum diisi' }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal Submit</label>
                    <p class="text-gray-900">{{ $publication->submission_date ? $publication->submission_date->format('d F Y H:i') : 'N/A' }}</p>
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
                    <label class="text-sm font-medium text-gray-500">Status Publikasi</label>
                    @if($publication->publication_status === 'accepted')
                        <div class="space-y-1">
                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs font-medium">LoA (Letter of Acceptance)</span>
                            <p class="text-xs text-gray-600">Publikasi telah diterima jurnal, menunggu terbit</p>
                        </div>
                    @elseif($publication->publication_status === 'published')
                        <div class="space-y-1">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Published</span>
                            <p class="text-xs text-gray-600">Publikasi sudah terbit di jurnal</p>
                        </div>
                    @elseif($publication->publication_status === 'submitted')
                        <div class="space-y-1">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Submitted</span>
                            <p class="text-xs text-gray-600">Publikasi telah disubmit ke jurnal</p>
                        </div>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">-</span>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Tipe Publikasi</label>
                    <span class="border border-orange-300 text-orange-600 px-2 py-1 rounded text-xs">{{ $publication->publicationType->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Publikasi -->
    @if($publication->publication_status === 'published')
    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
        <div class="flex items-center mb-4">
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-green-600">Publikasi Sudah Terbit</h2>
                <p class="text-green-700">Artikel ini sudah dipublikasikan di jurnal</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($publication->journal_name)
            <div class="bg-white p-3 rounded border border-green-200">
                <p class="text-sm font-medium text-gray-500">Jurnal</p>
                <p class="text-green-700 font-semibold">{{ $publication->journal_name }}</p>
            </div>
            @endif
            @if($publication->publication_date)
            <div class="bg-white p-3 rounded border border-green-200">
                <p class="text-sm font-medium text-gray-500">Tanggal Terbit</p>
                <p class="text-green-700 font-semibold">{{ $publication->publication_date->format('d F Y') }}</p>
            </div>
            @endif
            @if($publication->journal_url)
            <div class="bg-white p-3 rounded border border-green-200">
                <p class="text-sm font-medium text-gray-500">URL Artikel</p>
                <a href="{{ $publication->journal_url }}" target="_blank" class="text-green-600 hover:text-green-800 font-semibold">
                    Lihat Artikel →
                </a>
            </div>
            @endif
        </div>
    </div>
    @elseif($publication->publication_status === 'accepted')
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
        <div class="flex items-center mb-4">
            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-orange-600">Letter of Acceptance (LoA)</h2>
                <p class="text-orange-700">Artikel telah diterima jurnal, menunggu proses publikasi</p>
            </div>
        </div>
        @if($publication->loa_date)
        <div class="bg-white p-3 rounded border border-orange-200">
            <p class="text-sm font-medium text-gray-500">Tanggal LoA</p>
            <p class="text-orange-700 font-semibold">{{ $publication->loa_date->format('d F Y') }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Detail Publikasi -->
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h2 class="text-lg font-bold text-orange-600 mb-6 flex items-center">
            <i class="fas fa-file-alt mr-2"></i>
            Detail Publikasi
        </h2>
        
        <!-- File Uploads -->
        <div class="mb-6 p-4 bg-orange-50 rounded-lg">
            <h3 class="text-md font-semibold text-orange-600 mb-3">File Upload</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($publication->file_path)
                <div class="bg-white p-3 rounded border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">File Publikasi</p>
                            <p class="text-sm text-gray-500">{{ basename($publication->file_path) }}</p>
                        </div>
                        <a href="{{ Storage::url($publication->file_path) }}" target="_blank" class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                    </div>
                </div>
                @endif
                
                @if($publication->loa_file_path)
                <div class="bg-white p-3 rounded border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">File LoA</p>
                            <p class="text-sm text-gray-500">{{ basename($publication->loa_file_path) }}</p>
                        </div>
                        <a href="{{ Storage::url($publication->loa_file_path) }}" target="_blank" class="bg-orange-500 text-white px-3 py-1 rounded text-sm hover:bg-orange-600">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- LoA Information -->
        @if($publication->loa_date || $publication->loa_number)
        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
            <h3 class="text-md font-semibold text-green-600 mb-3">Letter of Acceptance (LoA)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($publication->loa_date)
                <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal LoA</label>
                    <p class="text-gray-900 font-medium">{{ $publication->loa_date->format('d F Y') }}</p>
                </div>
                @endif
                @if($publication->loa_number)
                <div>
                    <label class="text-sm font-medium text-gray-500">Nomor LoA</label>
                    <p class="text-gray-900 font-medium">{{ $publication->loa_number }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
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

    <!-- Action Buttons -->
    @if($publication->dosen_status === 'pending')
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h2 class="text-lg font-bold text-orange-600 mb-4 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            Review Publikasi
        </h2>
        <p class="text-gray-700 mb-6">Silakan review publikasi mahasiswa dan berikan keputusan:</p>
        
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Form Approve -->
                <div class="bg-white border-2 border-green-300 rounded-lg p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-green-700 mb-4 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Setujui Publikasi
                    </h3>
                    <form action="{{ route('dashboard.dosen-approve', $publication->id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Approval (Opsional)</label>
                                <textarea name="feedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Berikan catatan positif atau saran perbaikan..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 flex items-center justify-center font-bold text-lg shadow-md">
                                <i class="fas fa-check mr-2"></i>
                                SETUJUI PUBLIKASI
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Form Reject -->
                <div class="bg-white border-2 border-red-300 rounded-lg p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-red-700 mb-4 flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        Tolak Publikasi
                    </h3>
                    <form action="{{ route('dashboard.dosen-reject', $publication->id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan (Opsional)</label>
                                <textarea name="feedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Berikan alasan penolakan atau saran perbaikan..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white px-6 py-4 rounded-lg hover:bg-red-700 flex items-center justify-center font-bold text-lg shadow-md">
                                <i class="fas fa-times mr-2"></i>
                                TOLAK PUBLIKASI
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h2 class="text-lg font-bold text-orange-600 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Status Review
        </h2>
        <div class="flex items-center space-x-4">
            @if($publication->dosen_status === 'approved')
                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-check mr-2"></i>
                    Publikasi Telah Disetujui
                </span>
            @elseif($publication->dosen_status === 'rejected')
                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Publikasi Telah Ditolak
                </span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection 