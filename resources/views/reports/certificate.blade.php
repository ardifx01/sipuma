@extends('layouts.app')

@section('title', 'Sertifikat Publikasi - SIPUMA')

@section('content')
<div class="min-h-screen bg-orange-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl p-6 shadow border border-orange-200 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-orange-600">Sertifikat Publikasi</h1>
                    <p class="text-gray-700 mt-1">Bukti publikasi mahasiswa</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="window.print()" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center text-sm">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </button>
                    <a href="{{ route('reports.index') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Sertifikat -->
        <div class="bg-white rounded-xl shadow-lg border-4 border-orange-200 p-8 print:p-12 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-0 left-0 w-32 h-32 bg-orange-500 rounded-full -translate-x-16 -translate-y-16"></div>
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-400 rounded-full translate-x-12 -translate-y-12"></div>
                <div class="absolute bottom-0 left-0 w-20 h-20 bg-orange-300 rounded-full -translate-x-10 translate-y-10"></div>
                <div class="absolute bottom-0 right-0 w-28 h-28 bg-orange-600 rounded-full translate-x-14 translate-y-14"></div>
            </div>

            <!-- Header Sertifikat -->
            <div class="text-center mb-12 relative z-10">
                <!-- Logo dan Header -->
                <div class="flex items-center justify-center mb-6">
                    <!-- Logo UNIMA -->
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center mr-6 shadow-lg">
                        <div class="text-white text-center">
                            <div class="text-xs font-bold leading-tight">UNIMA</div>
                            <div class="text-xs leading-tight">MANADO</div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="w-px h-16 bg-gradient-to-b from-transparent via-orange-300 to-transparent mx-6"></div>
                    
                    <!-- SIPUMA -->
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-gray-900 mb-1">SIPUMA</h1>
                        <p class="text-gray-600 text-sm">Sistem Informasi Publikasi Mahasiswa</p>
                    </div>
                </div>

                <!-- Judul Sertifikat -->
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-orange-600 mb-2">SERTIFIKAT PUBLIKASI</h2>
                    <div class="w-32 h-1 bg-gradient-to-r from-orange-400 to-orange-600 mx-auto mb-3"></div>
                    <p class="text-gray-600 font-medium">Nomor: {{ strtoupper(substr(md5($publication->id), 0, 8)) }}/{{ $publication->publication_date->format('Y') }}</p>
                </div>
            </div>

            <!-- Konten Sertifikat -->
            <div class="text-center mb-10 relative z-10">
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg p-8 border border-orange-200 mb-8">
                    <p class="text-lg text-gray-700 mb-6 font-medium">
                        Diberikan kepada:
                    </p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-3">{{ $publication->student->name }}</h3>
                    <div class="bg-white rounded-lg p-4 inline-block shadow-sm">
                        <p class="text-gray-600 text-sm">
                            <span class="font-semibold">NIM:</span> {{ $publication->student->studentProfile->nim ?? 'N/A' }}<br>
                            <span class="font-semibold">Program Studi:</span> {{ $publication->student->studentProfile->major ?? 'N/A' }}<br>
                            <span class="font-semibold">Fakultas:</span> {{ $publication->student->studentProfile->faculty ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <p class="text-lg text-gray-700 mb-6 font-medium">
                    Atas keberhasilannya mempublikasikan artikel ilmiah:
                </p>
                <div class="bg-white border-2 border-orange-200 rounded-lg p-6 shadow-sm">
                    <h4 class="text-xl font-bold text-gray-900 italic leading-relaxed">
                        "{{ $publication->title }}"
                    </h4>
                </div>
            </div>

            <!-- Detail Publikasi -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 mb-8 border border-blue-200 relative z-10">
                <h5 class="font-bold text-gray-900 mb-4 text-center text-lg">Detail Publikasi</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <h6 class="font-semibold text-blue-600 mb-3">Informasi Jurnal</h6>
                        <div class="space-y-2">
                            <p><span class="font-semibold text-gray-700">Jurnal:</span> <span class="text-gray-600">{{ $publication->journal_name ?? 'N/A' }}</span></p>
                            <p><span class="font-semibold text-gray-700">Publisher:</span> <span class="text-gray-600">{{ $publication->publisher ?? 'N/A' }}</span></p>
                            <p><span class="font-semibold text-gray-700">Tanggal Terbit:</span> <span class="text-gray-600">{{ $publication->publication_date ? $publication->publication_date->format('d F Y') : 'N/A' }}</span></p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <h6 class="font-semibold text-blue-600 mb-3">Identifikasi</h6>
                        <div class="space-y-2">
                            <p><span class="font-semibold text-gray-700">DOI:</span> <span class="text-gray-600 font-mono text-xs">{{ $publication->doi ?? 'N/A' }}</span></p>
                            <p><span class="font-semibold text-gray-700">ISSN:</span> <span class="text-gray-600 font-mono text-xs">{{ $publication->issn ?? 'N/A' }}</span></p>
                            <p><span class="font-semibold text-gray-700">Volume/Issue:</span> <span class="text-gray-600">{{ $publication->volume ?? 'N/A' }}/{{ $publication->issue ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                </div>
                @if($publication->journal_url)
                <div class="mt-4 bg-white rounded-lg p-4 shadow-sm">
                    <h6 class="font-semibold text-blue-600 mb-2">Link Artikel</h6>
                    <a href="{{ $publication->journal_url }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all">
                        {{ $publication->journal_url }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Tanda Tangan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 relative z-10">
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Dosen Pembimbing</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ $publication->student->studentProfile->supervisor->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">NIDN: {{ $publication->student->studentProfile->supervisor->dosenProfile->nidn ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Admin</p>
                        <p class="font-semibold text-gray-900 text-sm">Administrator</p>
                        <p class="text-xs text-gray-500">SIPUMA System</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Tanggal</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ now()->format('d F Y') }}</p>
                        <p class="text-xs text-gray-500">Sertifikat ini berlaku selamanya</p>
                    </div>
                </div>
            </div>

            <!-- QR Code dan Validasi -->
            <div class="text-center mt-8 pt-6 border-t border-gray-200 relative z-10">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border border-gray-200 inline-block">
                    <div class="flex items-center justify-center mb-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">Validasi Sertifikat</span>
                    </div>
                    <div class="bg-white rounded p-3 border border-gray-300">
                        <div class="text-xs font-mono text-gray-700 break-all">
                            {{ route('reports.certificate', $publication->id) }}
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Scan atau kunjungi URL di atas untuk validasi</p>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6 mt-6 print:hidden">
            <h3 class="text-lg font-bold text-orange-600 mb-4">Informasi Sertifikat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Cara Validasi:</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Scan QR code atau kunjungi URL validasi</li>
                        <li>• Masukkan nomor sertifikat</li>
                        <li>• Sistem akan memverifikasi keabsahan</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Kegunaan:</h4>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Bukti publikasi untuk keperluan akademik</li>
                        <li>• Lampiran CV dan portofolio</li>
                        <li>• Syarat kelulusan dan wisuda</li>
                        <li>• Dokumen akreditasi program studi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print\:p-12 {
        padding: 3rem;
    }
    .print\:hidden {
        display: none;
    }
    body {
        background: white;
    }
    .bg-orange-50 {
        background: white;
    }
}
</style>
@endsection
