@extends('layouts.app')

@section('title', 'Detail Publikasi')

@section('content')
<style>
/* Clean and modern styles */
.page-container {
    background: #f8fafc;
    min-height: 100vh;
}
.header-section {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.content-section {
    background: transparent;
}
.main-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}
.main-card:hover {
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}
.info-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 24px;
    transition: all 0.2s ease;
}
.info-card:hover {
    border-color: #ea580c;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-badge.accepted {
    background: #10b981;
    color: white;
}
.status-badge.published {
    background: #3b82f6;
    color: white;
}
.status-badge.pending {
    background: #f59e0b;
    color: white;
}
.btn-primary {
    background: #ea580c;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.btn-primary:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}
.btn-secondary {
    background: white;
    color: #ea580c;
    border: 2px solid #ea580c;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s ease;
}
.btn-secondary:hover {
    background: #ea580c;
    color: white;
    transform: translateY(-1px);
}
.btn-outline {
    background: white;
    color: #64748b;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
}
.btn-outline:hover {
    border-color: #ea580c;
    color: #ea580c;
    background: #fef7f0;
}
.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}
.section-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 24px;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.info-item {
    background: #f8fafc;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #e2e8f0;
}
.info-label {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}
.info-value {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.5;
}
.tag {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    background: #fef3c7;
    color: #92400e;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    margin: 2px;
    border: 1px solid #f59e0b;
}
.file-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}
.file-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}
.file-icon {
    width: 40px;
    height: 40px;
    background: #ea580c;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}
.file-info {
    flex: 1;
}
.file-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}
.file-desc {
    font-size: 12px;
    color: #64748b;
}
.timeline {
    position: relative;
    padding-left: 24px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #ea580c;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #ea580c;
}
.timeline-content {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
}
.timeline-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}
.timeline-date {
    font-size: 12px;
    color: #64748b;
}
.feedback-item {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 16px;
    border-left: 4px solid #ea580c;
    border: 1px solid #e2e8f0;
}
.feedback-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}
.feedback-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ea580c;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}
.feedback-title {
    font-weight: 600;
    color: #1e293b;
}
.feedback-content {
    color: #475569;
    line-height: 1.6;
}
</style>

<div class="page-container">
    <!-- Header Section -->
    <div class="header-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $publication->title }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="status-badge {{ $publication->publication_status }}">
                            {{ ucfirst($publication->publication_status) }}
                        </span>
                        <span class="text-sm text-gray-500">ID: #{{ $publication->id }}</span>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3 justify-end">
                    @if($publication->file_path)
                    <a href="{{ Storage::url($publication->file_path) }}" target="_blank" class="btn-primary inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download File
                    </a>
                    @endif
                    @if($publication->loa_file_path)
                    <a href="{{ Storage::url($publication->loa_file_path) }}" target="_blank" class="btn-secondary inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download LoA
                    </a>
                    @endif
                    @if(!Auth::user()->hasRole('admin'))
                    <a href="{{ route('publications.edit', $publication) }}" class="btn-outline inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    
                                <!-- Update Status Button - hanya muncul jika status adalah 'accepted' (LoA) -->
            @if($publication->publication_status === 'accepted')
            <a href="{{ route('test.update-status', $publication->id) }}" class="btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Update ke Published
            </a>
            @endif
            @if($publication->publication_status === 'published')
            <a href="{{ route('reports.certificate', $publication->id) }}" class="btn-primary inline-flex items-center bg-green-500 hover:bg-green-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Generate Sertifikat
            </a>
            @endif
                    @endif
                    <a href="{{ route('publications.index') }}" class="btn-outline inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="content-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-8">
                    <!-- Publication Information -->
                    <div class="main-card p-8">
                        <div class="mb-8">
                            <h2 class="section-title">Informasi Publikasi</h2>
                            <p class="section-subtitle">Detail lengkap publikasi dan metadata</p>
                        </div>
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Sumber Artikel</div>
                                <div class="info-value">{{ $publication->sumber_artikel }}</div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Tipe Publikasi</div>
                                <div class="info-value">
                                    @if(is_array($publication->tipe_publikasi))
                                        @foreach($publication->tipe_publikasi as $tipe)
                                            <span class="tag">{{ $tipe }}</span>
                                        @endforeach
                                    @else
                                        <span class="tag">{{ $publication->tipe_publikasi }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Tanggal Upload</div>
                                <div class="info-value">
                                    {{ $publication->submission_date ? $publication->submission_date->format('d M Y H:i') : 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">Status Review</div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Publikasi:</span>
                                        <span class="status-badge {{ $publication->publication_status }}">
                                            {{ ucfirst($publication->publication_status) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Admin:</span>
                                        <span class="status-badge pending">
                                            {{ ucfirst($publication->admin_status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Dosen:</span>
                                        <span class="status-badge pending">
                                            {{ ucfirst($publication->dosen_status ?? 'pending') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                    </div>

                    <!-- LoA Information -->
                    @if($publication->loa_file_path || $publication->loa_date || $publication->loa_number)
                    <div class="main-card p-8">
                        <div class="mb-8">
                            <h2 class="section-title">Letter of Acceptance (LoA)</h2>
                            <p class="section-subtitle">Informasi dokumen Letter of Acceptance</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                @if($publication->loa_date)
                                <div class="info-item">
                                    <div class="info-label">Tanggal LoA</div>
                                    <div class="info-value">{{ $publication->loa_date->format('d M Y') }}</div>
                                </div>
                                @endif
                                
                                @if($publication->loa_number)
                                <div class="info-item">
                                    <div class="info-label">Nomor LoA</div>
                                    <div class="info-value">{{ $publication->loa_number }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-center">
                                @if($publication->loa_file_path)
                                <a href="{{ Storage::url($publication->loa_file_path) }}" target="_blank" class="btn-primary inline-flex items-center px-8 py-4 text-lg font-bold">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download LoA
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Book Information -->
                    @if($publication->book_title || $publication->book_publisher || $publication->book_year || $publication->book_isbn || $publication->book_pdf)
                    <div class="main-card p-8">
                        <div class="mb-8">
                            <h2 class="section-title">Informasi Buku</h2>
                            <p class="section-subtitle">Detail publikasi buku</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                @if($publication->book_title)
                                <div class="info-item">
                                    <div class="info-label">Judul Buku</div>
                                    <div class="info-value">{{ $publication->book_title }}</div>
                                </div>
                                @endif
                                
                                @if($publication->book_publisher)
                                <div class="info-item">
                                    <div class="info-label">Publisher</div>
                                    <div class="info-value">{{ $publication->book_publisher }}</div>
                                </div>
                                @endif
                                
                                @if($publication->book_year)
                                <div class="info-item">
                                    <div class="info-label">Tahun Terbit</div>
                                    <div class="info-value">{{ $publication->book_year }}</div>
                                </div>
                                @endif
                                
                                @if($publication->book_isbn)
                                <div class="info-item">
                                    <div class="info-label">ISBN</div>
                                    <div class="info-value">{{ $publication->book_isbn }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-center">
                                @if($publication->book_pdf)
                                <a href="{{ Storage::url($publication->book_pdf) }}" target="_blank" class="btn-primary inline-flex items-center px-8 py-4 text-lg font-bold">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Download Buku
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- HKI Information -->
                    @if($publication->hki_publication_date || $publication->hki_creator || $publication->hki_certificate)
                    <div class="main-card p-8">
                        <div class="mb-8">
                            <h2 class="section-title">Informasi HKI</h2>
                            <p class="section-subtitle">Detail Hak Kekayaan Intelektual</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                @if($publication->hki_publication_date)
                                <div class="info-item">
                                    <div class="info-label">Tanggal Terbit HKI</div>
                                    <div class="info-value">{{ $publication->hki_publication_date->format('d M Y') }}</div>
                                </div>
                                @endif
                                
                                @if($publication->hki_creator)
                                <div class="info-item">
                                    <div class="info-label">Nama Pencipta</div>
                                    <div class="info-value">{{ $publication->hki_creator }}</div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-center">
                                @if($publication->hki_certificate)
                                <a href="{{ Storage::url($publication->hki_certificate) }}" target="_blank" class="btn-primary inline-flex items-center px-8 py-4 text-lg font-bold">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Sertifikat HKI
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Journal Information -->
                    @if($publication->journal_name || $publication->journal_url || $publication->indexing || $publication->doi || $publication->issn || $publication->publisher || $publication->publication_date || $publication->volume || $publication->issue || $publication->pages)
                    <div class="main-card p-8">
                        <div class="mb-8">
                            <h2 class="section-title">Informasi Jurnal</h2>
                            <p class="section-subtitle">Detail publikasi jurnal dan indexing</p>
                        </div>
                        
                        <div class="info-grid">
                            @if($publication->journal_name)
                            <div class="info-item">
                                <div class="info-label">Nama Jurnal</div>
                                <div class="info-value">{{ $publication->journal_name }}</div>
                            </div>
                            @endif
                            @if($publication->journal_url)
                            <div class="info-item">
                                <div class="info-label">URL Artikel</div>
                                <div class="info-value">
                                    <a href="{{ $publication->journal_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $publication->journal_url }}</a>
                                </div>
                            </div>
                            @endif
                            @if($publication->indexing)
                            <div class="info-item">
                                <div class="info-label">Indexing</div>
                                <div class="info-value">{{ $publication->indexing }}</div>
                            </div>
                            @endif
                            @if($publication->doi)
                            <div class="info-item">
                                <div class="info-label">DOI</div>
                                <div class="info-value">{{ $publication->doi }}</div>
                            </div>
                            @endif
                            @if($publication->issn)
                            <div class="info-item">
                                <div class="info-label">ISSN</div>
                                <div class="info-value">{{ $publication->issn }}</div>
                            </div>
                            @endif
                            @if($publication->publisher)
                            <div class="info-item">
                                <div class="info-label">Publisher</div>
                                <div class="info-value">{{ $publication->publisher }}</div>
                            </div>
                            @endif
                            @if($publication->publication_date)
                            <div class="info-item">
                                <div class="info-label">Tanggal Publikasi</div>
                                <div class="info-value">{{ $publication->publication_date->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($publication->volume)
                            <div class="info-item">
                                <div class="info-label">Volume</div>
                                <div class="info-value">{{ $publication->volume }}</div>
                            </div>
                            @endif
                            @if($publication->issue)
                            <div class="info-item">
                                <div class="info-label">Issue</div>
                                <div class="info-value">{{ $publication->issue }}</div>
                            </div>
                            @endif
                            @if($publication->pages)
                            <div class="info-item">
                                <div class="info-label">Pages</div>
                                <div class="info-value">{{ $publication->pages }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Feedback & Review -->
                    @if($publication->admin_feedback || $publication->dosen_feedback)
                    <div class="main-card p-8">
                        <div class="mb-8">
                            <h2 class="section-title">Feedback & Review</h2>
                            <p class="section-subtitle">Komentar dan evaluasi dari reviewer</p>
                        </div>
                        
                        <div class="space-y-6">
                            @if($publication->admin_feedback)
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <div class="feedback-avatar">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="feedback-title">Feedback Admin</div>
                                </div>
                                <div class="feedback-content">{{ $publication->admin_feedback }}</div>
                            </div>
                            @endif
                            @if($publication->dosen_feedback)
                            <div class="feedback-item">
                                <div class="feedback-header">
                                    <div class="feedback-avatar">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="feedback-title">Feedback Dosen</div>
                                </div>
                                <div class="feedback-content">{{ $publication->dosen_feedback }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Files -->
                    <div class="main-card p-6">
                        <div class="mb-6">
                            <h3 class="section-title text-xl">File & Dokumen</h3>
                            <p class="section-subtitle">Dokumen terkait publikasi</p>
                        </div>
                        
                        <div class="space-y-4">
                            @if($publication->file_path)
                            <div class="file-item">
                                <div class="file-header">
                                    <div class="file-icon">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">File Publikasi</div>
                                        <div class="file-desc">{{ basename($publication->file_path) }}</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($publication->file_path) }}" target="_blank" class="btn-primary w-full inline-flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download File
                                </a>
                            </div>
                            @endif
                            
                            @if($publication->loa_file_path)
                            <div class="file-item">
                                <div class="file-header">
                                    <div class="file-icon">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">File LoA</div>
                                        <div class="file-desc">{{ basename($publication->loa_file_path) }}</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($publication->loa_file_path) }}" target="_blank" class="btn-secondary w-full inline-flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download LoA
                                </a>
                            </div>
                            @endif
                            
                            @if($publication->hki_certificate)
                            <div class="file-item">
                                <div class="file-header">
                                    <div class="file-icon">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">Sertifikat HKI</div>
                                        <div class="file-desc">{{ basename($publication->hki_certificate) }}</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($publication->hki_certificate) }}" target="_blank" class="btn-secondary w-full inline-flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download HKI
                                </a>
                            </div>
                            @endif
                            
                            @if($publication->book_pdf)
                            <div class="file-item">
                                <div class="file-header">
                                    <div class="file-icon">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name">File PDF Buku</div>
                                        <div class="file-desc">{{ basename($publication->book_pdf) }}</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($publication->book_pdf) }}" target="_blank" class="btn-secondary w-full inline-flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Buku
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="main-card p-6">
                        <div class="mb-6">
                            <h3 class="section-title text-xl">Timeline</h3>
                            <p class="section-subtitle">Progres publikasi</p>
                        </div>
                        
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-title">Upload Publikasi</div>
                                    <div class="timeline-date">{{ $publication->submission_date ? $publication->submission_date->format('d M Y H:i') : 'N/A' }}</div>
                                </div>
                            </div>
                            
                            @if($publication->loa_date)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-title">LoA Diterima</div>
                                    <div class="timeline-date">{{ $publication->loa_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            @endif
                            
                            @if($publication->publication_date)
                            <div class="timeline-item">
                                <div class="timeline-content">
                                    <div class="timeline-title">Publikasi</div>
                                    <div class="timeline-date">{{ $publication->publication_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revision History Timeline -->
    @if($publication->revision_history && count($publication->revision_history) > 0)
    <div class="main-card mt-8">
        <div class="p-6">
            <div class="flex items-center mb-6">
                <i class="fas fa-history text-orange-500 text-xl mr-3"></i>
                <h2 class="section-title">Timeline Revisi</h2>
            </div>
            
            <div class="timeline">
                @foreach($publication->revision_history as $index => $revision)
                <div class="timeline-item mb-6">
                    <div class="timeline-marker">
                        <div class="w-4 h-4 bg-orange-500 rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                    <div class="timeline-content ml-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        Revisi #{{ $revision['revision_number'] }}
                                    </span>
                                    <span class="text-gray-500 text-sm ml-3">
                                        {{ \Carbon\Carbon::parse($revision['timestamp'])->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                                    {{ $revision['status_before'] === 'rejected' ? 'Ditolak' : 'Status Lama' }}
                                </span>
                            </div>
                            
                            @if($revision['reason'])
                            <div class="mb-3">
                                <p class="text-sm font-medium text-gray-700 mb-1">Alasan Penolakan:</p>
                                <p class="text-sm text-red-600 bg-red-50 p-3 rounded border-l-4 border-red-400">
                                    {{ $revision['reason'] }}
                                </p>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="font-medium text-gray-700">Judul Sebelumnya:</p>
                                    <p class="text-gray-600">{{ $revision['old_data']['title'] ?? 'Tidak ada' }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">File Sebelumnya:</p>
                                    <p class="text-gray-600">{{ $revision['old_data']['file_path'] ? 'Ada file' : 'Tidak ada file' }}</p>
                                </div>
                                @if($revision['old_data']['loa_file_path'])
                                <div>
                                    <p class="font-medium text-gray-700">LoA Sebelumnya:</p>
                                    <p class="text-gray-600">Ada file LoA</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Current Version -->
                <div class="timeline-item">
                    <div class="timeline-marker">
                        <div class="w-4 h-4 bg-green-500 rounded-full border-2 border-white shadow-sm"></div>
                    </div>
                    <div class="timeline-content ml-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                        Versi Saat Ini (Revisi #{{ $publication->revision_number }})
                                    </span>
                                    <span class="text-gray-500 text-sm ml-3">
                                        {{ $publication->updated_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                    {{ $publication->admin_status === 'pending' ? 'Menunggu Review' : ucfirst($publication->admin_status) }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="font-medium text-gray-700">Judul Saat Ini:</p>
                                    <p class="text-gray-600">{{ $publication->title }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-700">Status:</p>
                                    <p class="text-gray-600">{{ ucfirst($publication->admin_status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 