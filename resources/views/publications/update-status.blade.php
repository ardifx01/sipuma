<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Status Publikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Update Status Publikasi</h1>
                        <p class="text-gray-600">Ubah status publikasi dari <span class="font-semibold text-orange-600">LoA (Letter of Acceptance)</span> menjadi <span class="font-semibold text-green-600">Published</span></p>
                    </div>

                    <!-- Current Publication Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Informasi Publikasi Saat Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-blue-700">Judul Publikasi:</p>
                                <p class="text-blue-900">{{ $publication->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700">Status Saat Ini:</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    LoA (Letter of Acceptance)
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700">Tanggal LoA:</p>
                                <p class="text-blue-900">{{ $publication->loa_date ? $publication->loa_date->format('d M Y') : 'Tidak ada' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-700">Nomor LoA:</p>
                                <p class="text-blue-900">{{ $publication->loa_number ?: 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Update Form -->
                    <form action="{{ route('publications.update-status-submit', $publication) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Status Update -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-green-900 mb-4">Update ke Status Published</h3>
                            <div class="flex items-center space-x-3">
                                <input type="radio" id="published" name="publication_status" value="published" checked class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500">
                                <label for="published" class="text-green-900 font-medium">Published (Artikel sudah terbit di jurnal)</label>
                            </div>
                        </div>

                        <!-- Journal Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Jurnal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="journal_name" value="Nama Jurnal *" />
                                    <x-text-input id="journal_name" name="journal_name" type="text" class="mt-1 block w-full" 
                                        value="{{ old('journal_name') }}" placeholder="Journal of Computer Science" />
                                    <x-input-error :messages="$errors->get('journal_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="journal_url" value="URL Jurnal *" />
                                    <x-text-input id="journal_url" name="journal_url" type="url" class="mt-1 block w-full" 
                                        value="{{ old('journal_url') }}" placeholder="https://example.com/journal" />
                                    <x-input-error :messages="$errors->get('journal_url')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="publisher" value="Nama Publisher *" />
                                    <x-text-input id="publisher" name="publisher" type="text" class="mt-1 block w-full" 
                                        value="{{ old('publisher') }}" placeholder="Springer, Elsevier, IEEE, dll" />
                                    <x-input-error :messages="$errors->get('publisher')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="publication_date" value="Tanggal Publikasi *" />
                                    <x-text-input id="publication_date" name="publication_date" type="date" class="mt-1 block w-full" 
                                        value="{{ old('publication_date') }}" />
                                    <x-input-error :messages="$errors->get('publication_date')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="doi" value="DOI" />
                                    <x-text-input id="doi" name="doi" type="text" class="mt-1 block w-full" 
                                        value="{{ old('doi') }}" placeholder="10.1000/182" />
                                    <x-input-error :messages="$errors->get('doi')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="issn" value="ISSN" />
                                    <x-text-input id="issn" name="issn" type="text" class="mt-1 block w-full" 
                                        value="{{ old('issn') }}" placeholder="1234-5678" />
                                    <x-input-error :messages="$errors->get('issn')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="volume" value="Volume" />
                                    <x-text-input id="volume" name="volume" type="text" class="mt-1 block w-full" 
                                        value="{{ old('volume') }}" placeholder="Vol. 1" />
                                    <x-input-error :messages="$errors->get('volume')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="issue" value="Issue" />
                                    <x-text-input id="issue" name="issue" type="text" class="mt-1 block w-full" 
                                        value="{{ old('issue') }}" placeholder="No. 1" />
                                    <x-input-error :messages="$errors->get('issue')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="pages" value="Halaman" />
                                    <x-text-input id="pages" name="pages" type="text" class="mt-1 block w-full" 
                                        value="{{ old('pages') }}" placeholder="1-10" />
                                    <x-input-error :messages="$errors->get('pages')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="indexing" value="Indexing" />
                                    <x-text-input id="indexing" name="indexing" type="text" class="mt-1 block w-full" 
                                        value="{{ old('indexing') }}" placeholder="Scopus, Sinta, dll" />
                                    <x-input-error :messages="$errors->get('indexing')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">File Publikasi</h3>
                            <div>
                                <x-input-label for="file_path" value="File Publikasi (PDF/DOC/DOCX) *" />
                                <input id="file_path" name="file_path" type="file" 
                                    class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                                    accept=".pdf,.doc,.docx" />
                                <p class="mt-1 text-sm text-gray-500">Upload file artikel yang sudah terbit (maksimal 10MB)</p>
                                <x-input-error :messages="$errors->get('file_path')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('publications.show', $publication) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update ke Published
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
