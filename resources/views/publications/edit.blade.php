@extends('layouts.app')

@section('title', 'Edit Publikasi')

@section('content')
<div class="bg-orange-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('publications.show', $publication) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-orange-600 bg-white border border-gray-300 rounded-lg hover:bg-orange-50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Publikasi</h1>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-orange-200">
                <div class="p-6">
                    <form action="{{ route('publications.update', $publication) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul Publikasi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Publikasi *</label>
                                <input type="text" name="title" value="{{ old('title', $publication->title) }}" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('title') border-red-500 @else border-gray-300 @enderror" 
                                    placeholder="Masukkan judul publikasi">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sumber Artikel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Artikel *</label>
                                <select name="sumber_artikel" id="sumber_artikel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('sumber_artikel') border-red-500 @enderror">
                                    <option value="">Pilih sumber artikel</option>
                                    <option value="Skripsi" {{ old('sumber_artikel', $publication->sumber_artikel) == 'Skripsi' ? 'selected' : '' }}>Skripsi</option>
                                    <option value="Magang" {{ old('sumber_artikel', $publication->sumber_artikel) == 'Magang' ? 'selected' : '' }}>Magang</option>
                                    <option value="Riset" {{ old('sumber_artikel', $publication->sumber_artikel) == 'Riset' ? 'selected' : '' }}>Riset</option>
                                </select>
                                @error('sumber_artikel')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tipe Publikasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Publikasi *</label>
                            <div id="tipe_publikasi_container">
                                <!-- Untuk Skripsi -->
                                <div id="tipe_skripsi" class="hidden">
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-blue-800">Tipe publikasi untuk Skripsi: <strong>Artikel</strong></span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="tipe_publikasi[]" value="Artikel">
                                </div>

                                <!-- Untuk Magang/Riset -->
                                <div id="tipe_magang_riset" class="hidden">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition-all duration-200">
                                            <input type="checkbox" name="tipe_publikasi[]" value="HKI" 
                                                class="w-5 h-5 text-orange-600 bg-white border-gray-300 rounded focus:ring-orange-500 focus:ring-2 mr-3"
                                                {{ in_array('HKI', old('tipe_publikasi', $publication->tipe_publikasi ?? [])) ? 'checked' : '' }}>
                                            <span class="text-gray-900 font-medium select-none">HKI</span>
                                        </label>
                                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition-all duration-200">
                                            <input type="checkbox" name="tipe_publikasi[]" value="Artikel" 
                                                class="w-5 h-5 text-orange-600 bg-white border-gray-300 rounded focus:ring-orange-500 focus:ring-2 mr-3"
                                                {{ in_array('Artikel', old('tipe_publikasi', $publication->tipe_publikasi ?? [])) ? 'checked' : '' }}>
                                            <span class="text-gray-900 font-medium select-none">Artikel</span>
                                        </label>
                                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition-all duration-200">
                                            <input type="checkbox" name="tipe_publikasi[]" value="Buku" 
                                                class="w-5 h-5 text-orange-600 bg-white border-gray-300 rounded focus:ring-orange-500 focus:ring-2 mr-3"
                                                {{ in_array('Buku', old('tipe_publikasi', $publication->tipe_publikasi ?? [])) ? 'checked' : '' }}>
                                            <span class="text-gray-900 font-medium select-none">Buku</span>
                                        </label>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">Pilih satu atau lebih tipe publikasi sesuai kebutuhan</p>
                                </div>
                            </div>
                            @error('tipe_publikasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Publication Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Publikasi *</label>
                            <select name="publication_status" id="publication_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('publication_status') border-red-500 @enderror" required>
                                <option value="">Pilih Status Publikasi</option>
                                <option value="accepted" {{ old('publication_status', $publication->publication_status) == 'accepted' ? 'selected' : '' }}>Accepted (Ada LoA)</option>
                                <option value="published" {{ old('publication_status', $publication->publication_status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('publication_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- LoA Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal LoA</label>
                                <input type="date" name="loa_date" value="{{ old('loa_date', $publication->loa_date ? $publication->loa_date->format('Y-m-d') : '') }}" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('loa_date') border-red-500 @else border-gray-300 @enderror">
                                @error('loa_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor LoA</label>
                                <input type="text" name="loa_number" value="{{ old('loa_number', $publication->loa_number) }}" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('loa_number') border-red-500 @else border-gray-300 @enderror" 
                                    placeholder="LOA-2024-001">
                                @error('loa_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Submit ke Publisher</label>
                                <input type="date" name="submission_date_to_publisher" value="{{ old('submission_date_to_publisher', $publication->submission_date_to_publisher ? $publication->submission_date_to_publisher->format('Y-m-d') : '') }}" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('submission_date_to_publisher') border-red-500 @else border-gray-300 @enderror">
                                @error('submission_date_to_publisher')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Perkiraan Tanggal Publikasi</label>
                                <input type="date" name="expected_publication_date" value="{{ old('expected_publication_date', $publication->expected_publication_date ? $publication->expected_publication_date->format('Y-m-d') : '') }}" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('expected_publication_date') border-red-500 @else border-gray-300 @enderror">
                                @error('expected_publication_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>





                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Publikasi</label>
                            <div class="file-input-container">
                                <input type="file" name="file" id="file" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('file') border-red-500 @else border-gray-300 @enderror" 
                                    accept=".pdf,.doc,.docx">
                                <p class="mt-1 text-sm text-gray-600">Format yang didukung: PDF, DOC, DOCX (Maksimal 10MB)</p>
                            </div>
                            @if($publication->file_path)
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-blue-800">File saat ini: {{ basename($publication->file_path) }}</span>
                                    </div>
                                </div>
                            @endif
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- LoA File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">File LoA (Letter of Acceptance)</label>
                            <div class="file-input-container">
                                <input type="file" name="loa_file_path" id="loa_file_path" 
                                    class="w-full px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 border @error('loa_file_path') border-red-500 @else border-gray-300 @enderror" 
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <p class="mt-1 text-sm text-gray-600">Format yang didukung: PDF, JPG, PNG (Maksimal 5MB)</p>
                            </div>
                            @if($publication->loa_file_path)
                                <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-green-800">File LoA saat ini: {{ basename($publication->loa_file_path) }}</span>
                                    </div>
                                </div>
                            @endif
                            @error('loa_file_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview File -->
                        <div id="file-preview" class="hidden">
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span id="file-name" class="text-green-800"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('publications.show', $publication) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Publikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi -->
            <div class="mt-8">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h3 class="font-bold text-yellow-800">Perhatian</h3>
                            <div class="text-sm mt-2 text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Jika Anda mengubah file, file lama akan dihapus</li>
                                    <li>Publikasi akan kembali ke status pending setelah diedit</li>
                                    <li>Pastikan semua informasi sudah benar sebelum menyimpan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const sumberArtikelSelect = document.getElementById('sumber_artikel');
    const tipeSkripsi = document.getElementById('tipe_skripsi');
    const tipeMagangRiset = document.getElementById('tipe_magang_riset');

    // File preview functionality
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            fileName.textContent = `File terpilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            filePreview.classList.remove('hidden');
        } else {
            filePreview.classList.add('hidden');
        }
    });

    // Dynamic tipe publikasi based on sumber artikel
    sumberArtikelSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        
        // Hide all tipe publikasi containers
        tipeSkripsi.classList.add('hidden');
        tipeMagangRiset.classList.add('hidden');
        
        // Show appropriate container based on selection
        if (selectedValue === 'Skripsi') {
            tipeSkripsi.classList.remove('hidden');
        } else if (selectedValue === 'Magang' || selectedValue === 'Riset') {
            tipeMagangRiset.classList.remove('hidden');
        }
    });

    // Function to check tipe publikasi and show/hide specific fields
    function checkTipePublikasi() {
        const selectedTipePublikasi = [];
        const checkboxes = document.querySelectorAll('input[name="tipe_publikasi[]"]:checked');
        checkboxes.forEach(checkbox => {
            selectedTipePublikasi.push(checkbox.value);
        });

        // Show/hide abstract and keywords based on tipe publikasi
        const abstractField = document.getElementById('abstract_field');
        const keywordsField = document.getElementById('keywords_field');
        
        // Show abstract and keywords only if Artikel is selected
        if (selectedTipePublikasi.includes('Artikel')) {
            if (abstractField) abstractField.classList.remove('hidden');
            if (keywordsField) keywordsField.classList.remove('hidden');
        } else {
            if (abstractField) abstractField.classList.add('hidden');
            if (keywordsField) keywordsField.classList.add('hidden');
        }
    }

    // Enhanced checkbox responsiveness
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Add immediate visual feedback
            if (this.checked) {
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            } else {
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }

            // Check if this is a tipe publikasi checkbox
            if (this.name === 'tipe_publikasi[]') {
                checkTipePublikasi();
            }
        });

        // Add hover effect
        checkbox.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });

        checkbox.addEventListener('mouseleave', function() {
            if (!this.checked) {
                this.style.transform = 'scale(1)';
            }
        });
    });

    // Trigger change event on page load if there's a selected value
    if (sumberArtikelSelect.value) {
        sumberArtikelSelect.dispatchEvent(new Event('change'));
    }

    // Check tipe publikasi on page load if there are checked checkboxes
    checkTipePublikasi();
});
</script>
@endsection 