@extends('layouts.app')

@section('title', 'Review Detail - Sipuma')

@section('content')
<div class="min-h-screen bg-orange-50">
    <div class="p-6 space-y-8">
        <!-- Header sederhana -->
        <div class="bg-white rounded-xl p-8 text-gray-900 shadow border border-orange-200 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-orange-600">Review Detail Publikasi</h1>
                <p class="text-gray-700 text-base">{{ $publication->title }}</p>
                <p class="text-gray-500 text-sm mt-1">Review dan approve publikasi mahasiswa</p>
            </div>
            <div class="text-right">
                <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                    <p class="text-gray-500 text-xs">Status</p>
                    @if($publication->admin_status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 border border-orange-200">
                            <i class="fas fa-clock mr-1"></i>Menunggu Review
                        </span>
                    @elseif($publication->admin_status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-200 text-orange-900 border border-orange-300">
                            <i class="fas fa-check mr-1"></i>Disetujui
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-700 border border-gray-300">
                            <i class="fas fa-times mr-1"></i>Ditolak
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informasi Mahasiswa -->
        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i>
                    Informasi Mahasiswa
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Nama Mahasiswa</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $publication->student->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Dosen Pembimbing</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $publication->student->supervisor ? $publication->student->supervisor->name : 'Belum diassign' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Tipe Publikasi</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $publication->publicationType->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Tanggal Submit</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $publication->created_at->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Publikasi -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Detail Publikasi
                </h2>
            </div>
            <div class="p-8 space-y-8">
                <!-- Judul dengan card khusus -->
                <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Judul Publikasi</label>
                    <p class="text-xl font-bold text-gray-900 leading-relaxed">{{ $publication->title }}</p>
                </div>

                <!-- Abstract dengan styling khusus -->
                @if($publication->abstract)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Abstract</label>
                    <p class="text-gray-800 leading-relaxed text-base">{{ $publication->abstract }}</p>
                </div>
                @endif

                <!-- Keywords dengan tag styling -->
                @if($publication->keywords)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Keywords</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $publication->keywords) as $keyword)
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium border border-gray-200">
                                {{ trim($keyword) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Informasi Umum dengan grid yang lebih menarik -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Informasi Umum</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($publication->sumber_artikel)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sumber Artikel</label>
                            <p class="text-gray-900 font-medium">{{ $publication->sumber_artikel }}</p>
                        </div>
                        @endif
                        
                        @if($publication->tipe_publikasi)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tipe Publikasi</label>
                            <p class="text-gray-900 font-medium">{{ is_array($publication->tipe_publikasi) ? implode(', ', $publication->tipe_publikasi) : $publication->tipe_publikasi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Jurnal (jika ada) -->
                @if($publication->journal_name || $publication->doi || $publication->issn)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center">
                        <i class="fas fa-book mr-2 text-gray-600"></i>
                        Informasi Jurnal
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($publication->journal_name)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Jurnal</label>
                            <p class="text-gray-900 font-medium">{{ $publication->journal_name }}</p>
                        </div>
                        @endif
                        
                        @if($publication->journal_url)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">URL Jurnal</label>
                            <a href="{{ $publication->journal_url }}" target="_blank" class="text-orange-600 hover:text-orange-800 break-all font-medium">
                                {{ Str::limit($publication->journal_url, 50) }}
                            </a>
                        </div>
                        @endif
                        
                        @if($publication->indexing)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Indexing</label>
                            <p class="text-gray-900 font-medium">{{ $publication->indexing }}</p>
                        </div>
                        @endif
                        
                        @if($publication->doi)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">DOI</label>
                            <p class="text-gray-900 font-mono font-medium">{{ $publication->doi }}</p>
                        </div>
                        @endif
                        
                        @if($publication->issn)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">ISSN</label>
                            <p class="text-gray-900 font-mono font-medium">{{ $publication->issn }}</p>
                        </div>
                        @endif
                        
                        @if($publication->publisher)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Publisher</label>
                            <p class="text-gray-900 font-medium">{{ $publication->publisher }}</p>
                        </div>
                        @endif
                        
                        @if($publication->publication_date)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal Publikasi</label>
                            <p class="text-gray-900 font-medium">{{ $publication->publication_date->format('d F Y') }}</p>
                        </div>
                        @endif
                        
                        @if($publication->volume)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Volume</label>
                            <p class="text-gray-900 font-medium">{{ $publication->volume }}</p>
                        </div>
                        @endif
                        
                        @if($publication->issue)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Issue</label>
                            <p class="text-gray-900 font-medium">{{ $publication->issue }}</p>
                        </div>
                        @endif
                        
                        @if($publication->pages)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Halaman</label>
                            <p class="text-gray-900 font-medium">{{ $publication->pages }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Informasi HKI (jika ada) -->
                @if($publication->hki_creator || $publication->hki_certificate)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center">
                        <i class="fas fa-certificate mr-2 text-gray-600"></i>
                        Informasi HKI
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($publication->hki_creator)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Creator</label>
                            <p class="text-gray-900 font-medium">{{ $publication->hki_creator }}</p>
                        </div>
                        @endif
                        
                        @if($publication->hki_publication_date)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal Publikasi HKI</label>
                            <p class="text-gray-900 font-medium">{{ $publication->hki_publication_date->format('d F Y') }}</p>
                        </div>
                        @endif
                        
                        @if($publication->hki_certificate)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Sertifikat HKI</label>
                            <a href="{{ asset('storage/' . $publication->hki_certificate) }}" target="_blank" class="text-orange-600 hover:text-orange-800 font-medium">
                                <i class="fas fa-file-pdf mr-1"></i>Lihat Sertifikat
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Informasi Buku (jika ada) -->
                @if($publication->book_title || $publication->book_publisher)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center">
                        <i class="fas fa-book-open mr-2 text-gray-600"></i>
                        Informasi Buku
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($publication->book_title)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Judul Buku</label>
                            <p class="text-gray-900 font-medium">{{ $publication->book_title }}</p>
                        </div>
                        @endif
                        
                        @if($publication->book_publisher)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Publisher</label>
                            <p class="text-gray-900 font-medium">{{ $publication->book_publisher }}</p>
                        </div>
                        @endif
                        
                        @if($publication->book_year)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tahun</label>
                            <p class="text-gray-900 font-medium">{{ $publication->book_year }}</p>
                        </div>
                        @endif
                        
                        @if($publication->book_edition)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Edisi</label>
                            <p class="text-gray-900 font-medium">{{ $publication->book_edition }}</p>
                        </div>
                        @endif
                        
                        @if($publication->book_editor)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Editor</label>
                            <p class="text-gray-900 font-medium">{{ $publication->book_editor }}</p>
                        </div>
                        @endif
                        
                        @if($publication->book_isbn)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">ISBN</label>
                            <p class="text-gray-900 font-mono font-medium">{{ $publication->book_isbn }}</p>
                        </div>
                        @endif
                        
                        @if($publication->book_pdf)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">File Buku</label>
                            <a href="{{ asset('storage/' . $publication->book_pdf) }}" target="_blank" class="text-orange-600 hover:text-orange-800 font-medium">
                                <i class="fas fa-file-pdf mr-1"></i>Lihat File
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- File Upload -->
        @if($publication->file_path)
        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-file-upload mr-2"></i>
                    File Publikasi
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-100">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-orange-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">File Publikasi</p>
                            <p class="text-lg font-semibold text-gray-900">{{ basename($publication->file_path) }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('publications.download', $publication->id) }}" 
                           class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-all border border-orange-600">
                            <i class="fas fa-download mr-1"></i>
                            Download
                        </a>
                        <a href="{{ asset('storage/' . $publication->file_path) }}" target="_blank"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-all border border-gray-300">
                            <i class="fas fa-eye mr-1"></i>
                            Preview
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Review Actions -->
        @if($publication->dosen_status === 'approved' && $publication->admin_status === 'pending')
        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Review & Approval
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('dashboard.admin-approve', $publication->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="feedback" class="text-sm font-medium text-gray-500">Feedback (Opsional)</label>
                        <textarea id="feedback" name="feedback" rows="4" 
                                  class="w-full px-4 py-3 border border-orange-200 rounded-xl bg-white text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                  placeholder="Berikan feedback untuk mahasiswa..."></textarea>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition-all flex items-center justify-center border border-orange-600">
                            <i class="fas fa-check mr-2"></i>
                            Approve Publikasi
                        </button>
                        
                        <a href="{{ route('dashboard.admin-review') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all border border-gray-300 flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </form>
                
                <div class="mt-6 pt-6 border-t border-orange-200">
                    <form action="{{ route('dashboard.admin-reject', $publication->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="reject_reason" class="text-sm font-medium text-gray-500">Alasan Penolakan</label>
                            <textarea id="reject_reason" name="feedback" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white text-gray-900 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Berikan alasan penolakan..." required></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-all flex items-center justify-center border border-gray-600">
                            <i class="fas fa-times mr-2"></i>
                            Tolak Publikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <!-- Status Info -->
        <div class="bg-white rounded-xl shadow border border-orange-200 overflow-hidden">
            <div class="bg-orange-100 px-6 py-4">
                <h2 class="text-lg font-bold text-orange-600 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Status Review
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-100">
                    <div class="flex items-center space-x-4">
                        @if($publication->admin_status === 'approved')
                            <div class="w-12 h-12 bg-orange-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="text-lg font-semibold text-orange-600">Disetujui</p>
                            </div>
                        @elseif($publication->admin_status === 'rejected')
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times text-gray-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="text-lg font-semibold text-gray-600">Ditolak</p>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-yellow-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="text-lg font-semibold text-yellow-600">Menunggu Review Dosen</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($publication->admin_feedback)
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Feedback</p>
                        <p class="text-sm text-gray-900">{{ $publication->admin_feedback }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard.admin-review') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all border border-gray-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Review
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection 