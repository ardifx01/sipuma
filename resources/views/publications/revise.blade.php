@extends('layouts.app')

@section('title', 'Revisi Publikasi')

@section('content')
<div class="bg-orange-50">
    <div class="container mx-auto px-4 py-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Revisi Publikasi</h1>
                        <p class="text-gray-600">Revisi #{{ $publication->revision_number }} - {{ $publication->title }}</p>
                    </div>
                    <a href="{{ route('publications.show', $publication->id) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <!-- Revision Info -->
            <div class="bg-orange-100 border border-orange-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-orange-800">Informasi Revisi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-orange-700">Status Sebelumnya:</p>
                        <p class="text-orange-800">
                            @if($publication->admin_status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Ditolak Admin</span>
                            @elseif($publication->dosen_status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Ditolak Dosen</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-orange-700">Alasan Penolakan:</p>
                        <p class="text-orange-800">{{ $publication->rejection_reason ?? 'Tidak ada alasan spesifik' }}</p>
                    </div>
                </div>
            </div>

            <!-- Revision Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form action="{{ route('publications.submit-revision', $publication->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Publikasi *</label>
                        <input type="text" name="title" id="title" 
                               value="{{ old('title', $publication->title) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Publikasi -->
                    <div class="mb-6">
                        <label for="publication_status" class="block text-sm font-medium text-gray-700 mb-2">Status Publikasi *</label>
                        <select name="publication_status" id="publication_status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                required>
                            <option value="accepted" {{ old('publication_status', $publication->publication_status) === 'accepted' ? 'selected' : '' }}>Accepted (LoA)</option>
                            <option value="published" {{ old('publication_status', $publication->publication_status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('publication_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Abstract & Keywords (untuk published) -->
                    <div id="abstract-keywords-section" class="mb-6" style="display: none;">
                        <div class="mb-4">
                            <label for="abstract" class="block text-sm font-medium text-gray-700 mb-2">Abstract</label>
                            <textarea name="abstract" id="abstract" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">{{ old('abstract', $publication->abstract) }}</textarea>
                            @error('abstract')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                            <input type="text" name="keywords" id="keywords" 
                                   value="{{ old('keywords', $publication->keywords) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @error('keywords')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="file_path" class="block text-sm font-medium text-gray-700 mb-2">File Publikasi</label>
                        <input type="file" name="file_path" id="file_path" 
                               accept=".pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Format: PDF, DOC, DOCX (Max 10MB)</p>
                        @error('file_path')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- LoA Fields -->
                    <div id="loa-section" class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi LoA</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="loa_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal LoA</label>
                                <input type="date" name="loa_date" id="loa_date" 
                                       value="{{ old('loa_date', $publication->loa_date ? $publication->loa_date->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                @error('loa_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="loa_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor LoA</label>
                                <input type="text" name="loa_number" id="loa_number" 
                                       value="{{ old('loa_number', $publication->loa_number) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                @error('loa_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="submission_date_to_publisher" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Submit ke Publisher</label>
                                <input type="date" name="submission_date_to_publisher" id="submission_date_to_publisher" 
                                       value="{{ old('submission_date_to_publisher', $publication->submission_date_to_publisher ? $publication->submission_date_to_publisher->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                @error('submission_date_to_publisher')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="expected_publication_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi yang Diharapkan</label>
                                <input type="date" name="expected_publication_date" id="expected_publication_date" 
                                       value="{{ old('expected_publication_date', $publication->expected_publication_date ? $publication->expected_publication_date->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                @error('expected_publication_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="loa_file_path" class="block text-sm font-medium text-gray-700 mb-2">File LoA</label>
                            <input type="file" name="loa_file_path" id="loa_file_path" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <p class="text-sm text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max 5MB)</p>
                            @error('loa_file_path')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('publications.show', $publication->id) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Submit Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('publication_status');
    const abstractSection = document.getElementById('abstract-keywords-section');
    const loaSection = document.getElementById('loa-section');
    
    function toggleSections() {
        if (statusSelect.value === 'published') {
            abstractSection.style.display = 'block';
            loaSection.style.display = 'none';
        } else {
            abstractSection.style.display = 'none';
            loaSection.style.display = 'block';
        }
    }
    
    statusSelect.addEventListener('change', toggleSections);
    toggleSections(); // Initial call
});
</script>
@endsection 