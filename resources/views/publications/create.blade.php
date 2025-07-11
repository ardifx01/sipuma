@extends('layouts.app')

@section('title', 'Upload Publikasi Baru')

@section('content')
<div class="min-h-screen bg-orange-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('publications.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-orange-600 rounded-lg hover:bg-orange-100 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Upload Publikasi Baru</h1>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                <div class="p-8">
                    <form action="{{ route('publications.store') }}" method="POST" enctype="multipart/form-data" id="publicationForm">
                        @csrf
                        
                        <!-- Progress Steps -->
                        <div class="mb-8">
                            <div class="flex items-center justify-center space-x-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                                    <span class="ml-2 text-sm font-medium text-gray-900">Informasi Dasar</span>
                                </div>
                                <div class="w-16 h-0.5 bg-gray-300"></div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">2</div>
                                    <span class="ml-2 text-sm font-medium text-gray-500">Detail Publikasi</span>
                                </div>
                                <div class="w-16 h-0.5 bg-gray-300"></div>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                                    <span class="ml-2 text-sm font-medium text-gray-500">Upload File</span>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Basic Information -->
                        <div id="step1" class="space-y-6">
                            <div class="border-b border-orange-200 pb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Sumber Artikel -->
                                    <div>
                                        <label for="sumber_artikel" class="block text-sm font-medium text-gray-700 mb-2">
                                            Sumber Artikel <span class="text-red-500">*</span>
                                        </label>
                                        <select name="sumber_artikel" id="sumber_artikel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                            <option value="">Pilih Sumber Artikel</option>
                                            <option value="Skripsi" {{ old('sumber_artikel') == 'Skripsi' ? 'selected' : '' }}>Skripsi</option>
                                            <option value="Magang" {{ old('sumber_artikel') == 'Magang' ? 'selected' : '' }}>Magang</option>
                                            <option value="Riset" {{ old('sumber_artikel') == 'Riset' ? 'selected' : '' }}>Riset</option>
                                        </select>
                                        @error('sumber_artikel')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Judul Publikasi -->
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Judul Publikasi <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan judul publikasi" required>
                                        @error('title')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tipe Publikasi -->
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Tipe Publikasi <span class="text-red-500">*</span>
                                    </label>
                                    <div id="tipe_publikasi_container" class="space-y-3">
                                        <!-- Checkboxes will be populated by JavaScript -->
                                    </div>
                                    @error('tipe_publikasi')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="flex justify-end">
                                <button type="button" onclick="nextStep()" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Selanjutnya
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Publication Details -->
                        <div id="step2" class="space-y-6 hidden">
                            <div class="border-b border-orange-200 pb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Publikasi</h2>
                                
                                <!-- Journal Information -->
                                <div id="journal_fields" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="journal_name" class="block text-sm font-medium text-gray-700 mb-2">
                                                Nama Jurnal
                                            </label>
                                            <input type="text" name="journal_name" id="journal_name" value="{{ old('journal_name') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Masukkan nama jurnal">
                                            @error('journal_name')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="journal_url" class="block text-sm font-medium text-gray-700 mb-2">
                                                URL Jurnal
                                            </label>
                                            <input type="url" name="journal_url" id="journal_url" value="{{ old('journal_url') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="https://example.com">
                                            @error('journal_url')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="indexing" class="block text-sm font-medium text-gray-700 mb-2">
                                                Indexing
                                            </label>
                                            <input type="text" name="indexing" id="indexing" value="{{ old('indexing') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="SCOPUS, Sinta, dll">
                                            @error('indexing')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="doi" class="block text-sm font-medium text-gray-700 mb-2">
                                                DOI
                                            </label>
                                            <input type="text" name="doi" id="doi" value="{{ old('doi') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="10.1000/xyz123">
                                            @error('doi')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="issn" class="block text-sm font-medium text-gray-700 mb-2">
                                                ISSN
                                            </label>
                                            <input type="text" name="issn" id="issn" value="{{ old('issn') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="1234-5678">
                                            @error('issn')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">
                                                Publisher
                                            </label>
                                            <input type="text" name="publisher" id="publisher" value="{{ old('publisher') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Nama publisher">
                                            @error('publisher')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="publication_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                Tanggal Publikasi
                                            </label>
                                            <input type="date" name="publication_date" id="publication_date" value="{{ old('publication_date') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            @error('publication_date')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="volume" class="block text-sm font-medium text-gray-700 mb-2">
                                                Volume
                                            </label>
                                            <input type="text" name="volume" id="volume" value="{{ old('volume') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Volume">
                                            @error('volume')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="issue" class="block text-sm font-medium text-gray-700 mb-2">
                                                Issue
                                            </label>
                                            <input type="text" name="issue" id="issue" value="{{ old('issue') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Issue">
                                            @error('issue')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="pages" class="block text-sm font-medium text-gray-700 mb-2">
                                                Pages
                                            </label>
                                            <input type="text" name="pages" id="pages" value="{{ old('pages') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="1-10">
                                            @error('pages')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Publication Status and LoA Fields -->
                                <div id="publication_status_fields" class="space-y-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Publikasi & LoA</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="publication_status" class="block text-sm font-medium text-gray-700 mb-2">
                                                Status Publikasi <span class="text-red-500">*</span>
                                            </label>
                                            <select name="publication_status" id="publication_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                                                <option value="">Pilih Status Publikasi</option>
                                                <option value="draft" {{ old('publication_status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="submitted" {{ old('publication_status') == 'submitted' ? 'selected' : '' }}>Submitted ke Publisher</option>
                                                <option value="accepted" {{ old('publication_status') == 'accepted' ? 'selected' : '' }}>Accepted (Ada LoA)</option>
                                                <option value="published" {{ old('publication_status') == 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                            @error('publication_status')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="loa_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                Tanggal LoA
                                            </label>
                                            <input type="date" name="loa_date" id="loa_date" value="{{ old('loa_date') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            @error('loa_date')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="loa_number" class="block text-sm font-medium text-gray-700 mb-2">
                                                Nomor LoA
                                            </label>
                                            <input type="text" name="loa_number" id="loa_number" value="{{ old('loa_number') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="LOA-2024-001">
                                            @error('loa_number')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="submission_date_to_publisher" class="block text-sm font-medium text-gray-700 mb-2">
                                                Tanggal Submit ke Publisher
                                            </label>
                                            <input type="date" name="submission_date_to_publisher" id="submission_date_to_publisher" value="{{ old('submission_date_to_publisher') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            @error('submission_date_to_publisher')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="expected_publication_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                Perkiraan Tanggal Publikasi
                                            </label>
                                            <input type="date" name="expected_publication_date" id="expected_publication_date" value="{{ old('expected_publication_date') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            @error('expected_publication_date')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="publisher_name" class="block text-sm font-medium text-gray-700 mb-2">
                                                Nama Publisher
                                            </label>
                                            <input type="text" name="publisher_name" id="publisher_name" value="{{ old('publisher_name') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Springer, Elsevier, IEEE, dll">
                                            @error('publisher_name')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="journal_name_expected" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Jurnal yang Diharapkan
                                        </label>
                                        <input type="text" name="journal_name_expected" id="journal_name_expected" value="{{ old('journal_name_expected') }}" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Journal of Computer Science">
                                        @error('journal_name_expected')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="publication_agreement_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Catatan Perjanjian Publikasi
                                        </label>
                                        <textarea name="publication_agreement_notes" id="publication_agreement_notes" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                                                  placeholder="Catatan tentang perjanjian dengan publisher, syarat publikasi, dll...">{{ old('publication_agreement_notes') }}</textarea>
                                        @error('publication_agreement_notes')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="publication_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Catatan Publikasi
                                        </label>
                                        <textarea name="publication_notes" id="publication_notes" rows="3" 
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                                                  placeholder="Catatan tambahan tentang proses publikasi...">{{ old('publication_notes') }}</textarea>
                                        @error('publication_notes')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- HKI Fields -->
                                <div id="hki_fields" class="space-y-6 hidden">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi HKI</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="hki_publication_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                Tanggal Terbit HKI
                                            </label>
                                            <input type="date" name="hki_publication_date" id="hki_publication_date" value="{{ old('hki_publication_date') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                            @error('hki_publication_date')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="hki_creator" class="block text-sm font-medium text-gray-700 mb-2">
                                                Nama Pencipta
                                            </label>
                                            <input type="text" name="hki_creator" id="hki_creator" value="{{ old('hki_creator') }}" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Nama pencipta HKI">
                                            @error('hki_creator')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="hki_certificate" class="block text-sm font-medium text-gray-700 mb-2">
                                            Sertifikat HKI
                                        </label>
                                        <input type="file" name="hki_certificate" id="hki_certificate" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" accept=".pdf,.jpg,.jpeg,.png">
                                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG (Max: 5MB)</p>
                                        @error('hki_certificate')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Book Fields -->
                                <div id="book_fields" class="space-y-6 hidden">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Buku</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="book_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Judul Buku
                                            </label>
                                            <input type="text" name="book_title" id="book_title" value="{{ old('book_title') }}" 
                                                   class="input input-bordered w-full" placeholder="Judul buku">
                                            @error('book_title')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="book_publisher" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Penerbit
                                            </label>
                                            <input type="text" name="book_publisher" id="book_publisher" value="{{ old('book_publisher') }}" 
                                                   class="input input-bordered w-full" placeholder="Nama penerbit">
                                            @error('book_publisher')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="book_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tahun Terbit
                                            </label>
                                            <input type="number" name="book_year" id="book_year" value="{{ old('book_year') }}" 
                                                   class="input input-bordered w-full" placeholder="2024" min="1900" max="2030">
                                            @error('book_year')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="book_edition" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Edisi
                                            </label>
                                            <input type="text" name="book_edition" id="book_edition" value="{{ old('book_edition') }}" 
                                                   class="input input-bordered w-full" placeholder="1st Edition">
                                            @error('book_edition')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="book_editor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Editor
                                            </label>
                                            <input type="text" name="book_editor" id="book_editor" value="{{ old('book_editor') }}" 
                                                   class="input input-bordered w-full" placeholder="Nama editor">
                                            @error('book_editor')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="book_isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                ISBN
                                            </label>
                                            <input type="text" name="book_isbn" id="book_isbn" value="{{ old('book_isbn') }}" 
                                                   class="input input-bordered w-full" placeholder="978-0-123456-47-2">
                                            @error('book_isbn')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="book_pdf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                File PDF Buku
                                            </label>
                                            <input type="file" name="book_pdf" id="book_pdf" 
                                                   class="file-input file-input-bordered w-full" accept=".pdf">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: PDF (Max: 10MB)</p>
                                            @error('book_pdf')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="flex justify-between">
                                <button type="button" onclick="prevStep()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Sebelumnya
                                </button>
                                <button type="button" onclick="nextStep()" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Selanjutnya
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: File Upload -->
                        <div id="step3" class="space-y-6 hidden">
                            <div class="border-b border-orange-200 pb-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload File</h2>
                                
                                <div class="space-y-6">
                                    <div>
                                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-2">
                                            File Publikasi <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" name="file_path" id="file_path" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" accept=".pdf,.doc,.docx" required>
                                        <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX (Max: 10MB)</p>
                                        @error('file_path')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="loa_file_path" class="block text-sm font-medium text-gray-700 mb-2">
                                            File LoA (Letter of Acceptance)
                                        </label>
                                        <input type="file" name="loa_file_path" id="loa_file_path" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" accept=".pdf,.jpg,.jpeg,.png">
                                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG (Max: 5MB)</p>
                                        @error('loa_file_path')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-orange-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <h4 class="text-sm font-medium text-orange-800 mb-1">Tips Upload File</h4>
                                                <ul class="text-sm text-orange-700 space-y-1">
                                                    <li>• Pastikan file dalam format PDF, DOC, atau DOCX</li>
                                                    <li>• Ukuran file maksimal 10MB</li>
                                                    <li>• Pastikan file tidak rusak dan dapat dibuka</li>
                                                    <li>• Untuk HKI, sertifikat dapat diupload terpisah</li>
                                                    <li>• Untuk Buku, file PDF buku dapat diupload terpisah</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="flex justify-between">
                                <button type="button" onclick="prevStep()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Sebelumnya
                                </button>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Upload Publikasi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;

// Tipe publikasi options
const tipeOptions = {
    'Skripsi': ['Artikel'],
    'Magang': ['Artikel', 'HKI', 'Buku'],
    'Riset': ['Artikel', 'HKI', 'Buku']
};

function updateTipePublikasi() {
    const sumberArtikel = document.getElementById('sumber_artikel').value;
    const container = document.getElementById('tipe_publikasi_container');
    
    container.innerHTML = '';
    
    if (sumberArtikel && tipeOptions[sumberArtikel]) {
        tipeOptions[sumberArtikel].forEach(tipe => {
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors';
            
            div.innerHTML = `
                <input type="checkbox" name="tipe_publikasi[]" value="${tipe}" id="tipe_${tipe}" 
                       class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500" onchange="toggleFields()">
                <label for="tipe_${tipe}" class="text-sm font-medium text-gray-900 cursor-pointer flex-1">
                    ${tipe}
                </label>
            `;
            
            container.appendChild(div);
        });
    }
}

function toggleFields() {
    const selectedTipes = Array.from(document.querySelectorAll('input[name="tipe_publikasi[]"]:checked'))
        .map(cb => cb.value);
    
    // Show/hide HKI fields
    const hkiFields = document.getElementById('hki_fields');
    if (selectedTipes.includes('HKI')) {
        hkiFields.classList.remove('hidden');
    } else {
        hkiFields.classList.add('hidden');
    }
    
    // Show/hide Book fields
    const bookFields = document.getElementById('book_fields');
    if (selectedTipes.includes('Buku')) {
        bookFields.classList.remove('hidden');
    } else {
        bookFields.classList.add('hidden');
    }
}

function nextStep() {
    if (currentStep < totalSteps) {
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep++;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');
        updateProgressSteps();
    }
}

function prevStep() {
    if (currentStep > 1) {
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep--;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');
        updateProgressSteps();
    }
}

function updateProgressSteps() {
    const steps = document.querySelectorAll('[id^="step"]');
    steps.forEach((step, index) => {
        const stepNumber = index + 1;
        const progressCircle = step.querySelector('.w-8.h-8');
        const progressText = step.querySelector('.text-sm.font-medium');
        
        if (stepNumber < currentStep) {
            progressCircle.className = 'w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-medium';
            progressText.className = 'ml-2 text-sm font-medium text-green-600 dark:text-green-400';
        } else if (stepNumber === currentStep) {
            progressCircle.className = 'w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center text-sm font-medium';
            progressText.className = 'ml-2 text-sm font-medium text-gray-900';
        } else {
            progressCircle.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium';
            progressText.className = 'ml-2 text-sm font-medium text-gray-500';
        }
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const sumberArtikelSelect = document.getElementById('sumber_artikel');
    sumberArtikelSelect.addEventListener('change', updateTipePublikasi);
    
    // Initialize
    updateTipePublikasi();
    updateProgressSteps();
});
</script>
@endsection 