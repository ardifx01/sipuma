@extends('layouts.app')

@section('title', 'Detail Publikasi')

@section('content')
<div class="min-h-screen bg-orange-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('publications.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-orange-600 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Publikasi</h1>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    @if($publication->file_path)
                    <a href="{{ route('publications.download', $publication) }}" class="inline-flex items-center px-4 py-2 bg-white hover:bg-orange-50 text-orange-600 border border-orange-300 hover:border-orange-400 text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download File
                    </a>
                    @endif
                    @if(!Auth::user()->hasRole('admin'))
                    <a href="{{ route('publications.edit', $publication) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Publication Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Publication Header -->
                <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $publication->title }}</h2>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $publication->publicationType->name }}
                                    </span>
                                    @if(Auth::user()->hasRole('admin'))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Mahasiswa: {{ $publication->student->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Sumber Artikel</label>
                                        <p class="text-gray-900 font-medium">{{ $publication->sumber_artikel }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tipe Publikasi</label>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @if(is_array($publication->tipe_publikasi))
                                                @foreach($publication->tipe_publikasi as $tipe)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">{{ $tipe }}</span>
                                                @endforeach
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">{{ $publication->tipe_publikasi }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Abstract</label>
                                        <p class="text-gray-700 leading-relaxed">{{ $publication->abstract }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Keywords</label>
                                        <p class="text-gray-700">{{ $publication->keywords }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Journal Info -->
                            @if($publication->journal_name || $publication->journal_url || $publication->indexing || $publication->doi || $publication->issn || $publication->publisher || $publication->publication_date || $publication->volume || $publication->issue || $publication->pages)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Jurnal</h3>
                                <div class="space-y-3">
                                    @if($publication->journal_name)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Nama Jurnal</label>
                                        <p class="text-gray-900">{{ $publication->journal_name }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($publication->journal_url)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">URL Jurnal</label>
                                        <a href="{{ $publication->journal_url }}" target="_blank" class="text-orange-600 hover:text-orange-800">
                                            {{ $publication->journal_url }}
                                        </a>
                                    </div>
                                    @endif
                                    
                                    @if($publication->indexing)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Indexing</label>
                                        <p class="text-gray-900">{{ $publication->indexing }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($publication->doi)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">DOI</label>
                                        <p class="text-gray-900">{{ $publication->doi }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($publication->issn)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">ISSN</label>
                                        <p class="text-gray-900">{{ $publication->issn }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($publication->publisher)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Publisher</label>
                                        <p class="text-gray-900">{{ $publication->publisher }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($publication->publication_date)
                                    <div>
                                        <label class="text-sm font-medium text-gray-600">Tanggal Publikasi</label>
                                        <p class="text-gray-900">{{ $publication->publication_date->format('d M Y') }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($publication->volume || $publication->issue || $publication->pages)
                                    <div class="grid grid-cols-3 gap-3">
                                        @if($publication->volume)
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Volume</label>
                                            <p class="text-gray-900">{{ $publication->volume }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($publication->issue)
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Issue</label>
                                            <p class="text-gray-900">{{ $publication->issue }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($publication->pages)
                                        <div>
                                            <label class="text-sm font-medium text-gray-600">Pages</label>
                                            <p class="text-gray-900">{{ $publication->pages }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- HKI Information -->
                @if((is_array($publication->tipe_publikasi) && in_array('HKI', $publication->tipe_publikasi)) || $publication->tipe_publikasi === 'HKI')
                @if($publication->hki_publication_date || $publication->hki_creator || $publication->hki_certificate)
                <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi HKI</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($publication->hki_publication_date)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tanggal Terbit HKI</label>
                                <p class="text-gray-900">{{ $publication->hki_publication_date->format('d M Y') }}</p>
                            </div>
                            @endif
                            
                            @if($publication->hki_creator)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Nama Pencipta</label>
                                <p class="text-gray-900">{{ $publication->hki_creator }}</p>
                            </div>
                            @endif
                            
                            @if($publication->hki_certificate)
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-600">Sertifikat HKI</label>
                                <a href="{{ Storage::url($publication->hki_certificate) }}" target="_blank" class="inline-flex items-center px-3 py-2 mt-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Sertifikat
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endif

                <!-- Book Information -->
                @if((is_array($publication->tipe_publikasi) && in_array('Buku', $publication->tipe_publikasi)) || $publication->tipe_publikasi === 'Buku')
                @if($publication->book_title || $publication->book_publisher || $publication->book_year || $publication->book_edition || $publication->book_editor || $publication->book_isbn || $publication->book_pdf)
                <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Buku</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($publication->book_title)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Judul Buku</label>
                                <p class="text-gray-900">{{ $publication->book_title }}</p>
                            </div>
                            @endif
                            
                            @if($publication->book_publisher)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Penerbit</label>
                                <p class="text-gray-900">{{ $publication->book_publisher }}</p>
                            </div>
                            @endif
                            
                            @if($publication->book_year)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Tahun Terbit</label>
                                <p class="text-gray-900">{{ $publication->book_year }}</p>
                            </div>
                            @endif
                            
                            @if($publication->book_edition)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Edisi</label>
                                <p class="text-gray-900">{{ $publication->book_edition }}</p>
                            </div>
                            @endif
                            
                            @if($publication->book_editor)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Editor</label>
                                <p class="text-gray-900">{{ $publication->book_editor }}</p>
                            </div>
                            @endif
                            
                            @if($publication->book_isbn)
                            <div>
                                <label class="text-sm font-medium text-gray-600">ISBN</label>
                                <p class="text-gray-900">{{ $publication->book_isbn }}</p>
                            </div>
                            @endif
                            
                            @if($publication->book_pdf)
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-600">File PDF Buku</label>
                                <a href="{{ Storage::url($publication->book_pdf) }}" target="_blank" class="inline-flex items-center px-3 py-2 mt-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download PDF Buku
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>

                            <!-- Right Column - Status & Reviews -->
                <div class="space-y-6">
                    <!-- Publication Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Publikasi</h3>
                            <div class="space-y-4">
                                <!-- Publication Status -->
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status Publikasi</label>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $publication->getStatusBadgeClass() }}">
                                            {{ $publication->getStatusLabel() }}
                                        </span>
                                    </div>
                                </div>

                                @if($publication->hasLoA())
                                <div>
                                    <label class="text-sm font-medium text-gray-600">LoA (Letter of Acceptance)</label>
                                    <div class="mt-2 space-y-2">
                                        @if($publication->loa_date)
                                        <p class="text-sm text-gray-900">Tanggal: {{ $publication->loa_date->format('d M Y') }}</p>
                                        @endif
                                        @if($publication->loa_number)
                                        <p class="text-sm text-gray-900">Nomor: {{ $publication->loa_number }}</p>
                                        @endif
                                        @if($publication->loa_file_path)
                                        <a href="{{ Storage::url($publication->loa_file_path) }}" target="_blank" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download LoA
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($publication->submission_date_to_publisher)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Submit ke Publisher</label>
                                    <p class="text-gray-900 mt-1">{{ $publication->submission_date_to_publisher->format('d M Y') }}</p>
                                </div>
                                @endif

                                @if($publication->expected_publication_date)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Perkiraan Tanggal Publikasi</label>
                                    <p class="text-gray-900 mt-1">{{ $publication->expected_publication_date->format('d M Y') }}</p>
                                </div>
                                @endif

                                @if($publication->publisher_name || $publication->journal_name_expected)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Informasi Publisher & Jurnal</label>
                                    <div class="mt-2 space-y-2">
                                        @if($publication->publisher_name)
                                        <div>
                                            <p class="text-sm text-gray-700">Publisher:</p>
                                            <p class="text-sm text-gray-900">{{ $publication->publisher_name }}</p>
                                        </div>
                                        @endif
                                        @if($publication->journal_name_expected)
                                        <div>
                                            <p class="text-sm text-gray-700">Jurnal yang Diharapkan:</p>
                                            <p class="text-sm text-gray-900">{{ $publication->journal_name_expected }}</p>
                                        </div>
                                        @endif
                                        @if($publication->publication_agreement_notes)
                                        <div>
                                            <p class="text-sm text-gray-700">Catatan Perjanjian:</p>
                                            <p class="text-sm text-gray-900">{{ $publication->publication_agreement_notes }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($publication->publication_notes)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Catatan Publikasi</label>
                                    <p class="text-gray-900 mt-1 text-sm">{{ $publication->publication_notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Admin/Dosen Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Review</h3>
                            <div class="space-y-4">
                                <!-- Admin Status -->
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status Admin</label>
                                    <div class="mt-2">
                                        @if($publication->admin_status === 'pending')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-yellow-800">Menunggu Review</span>
                                            </div>
                                        @elseif($publication->admin_status === 'approved')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-green-800">Disetujui</span>
                                            </div>
                                        @elseif($publication->admin_status === 'rejected')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-red-800">Ditolak</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Dosen Status -->
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Status Dosen</label>
                                    <div class="mt-2">
                                        @if($publication->dosen_status === 'pending')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-yellow-800">Menunggu Review</span>
                                            </div>
                                        @elseif($publication->dosen_status === 'approved')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-green-800">Disetujui</span>
                                            </div>
                                        @elseif($publication->dosen_status === 'rejected')
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-red-800">Ditolak</span>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                                <span class="text-sm font-medium text-gray-600">Belum Direview</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Submission Date -->
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Upload</label>
                                    <p class="text-gray-900 mt-1">{{ $publication->submission_date->format('d M Y H:i') }}</p>
                                </div>

                                @if($publication->admin_reviewed_at)
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tanggal Review Admin</label>
                                    <p class="text-gray-900 mt-1">{{ $publication->admin_reviewed_at->format('d M Y H:i') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                <!-- Feedback Card -->
                @if($publication->admin_feedback || $publication->dosen_feedback)
                <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Feedback</h3>
                        <div class="space-y-4">
                            @if($publication->admin_feedback)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Feedback Admin</label>
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-700 text-sm">{{ $publication->admin_feedback }}</p>
                                </div>
                            </div>
                            @endif

                            @if($publication->dosen_feedback)
                            <div>
                                <label class="text-sm font-medium text-gray-600">Feedback Dosen</label>
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-700 text-sm">{{ $publication->dosen_feedback }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Reviews Card -->
                @if($publication->reviews->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Review History</h3>
                        <div class="space-y-3">
                            @foreach($publication->reviews as $review)
                            <div class="border-l-4 border-orange-200 pl-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $review->reviewer->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $review->feedback }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 