@extends('layouts.app')

@section('title', 'Laporan LAM Infokom - SIPUMA')

@section('content')
<div class="min-h-screen bg-orange-50 p-6 space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 shadow border border-orange-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-orange-600">Laporan LAM Infokom</h1>
                <p class="text-gray-700 mt-1">Kriteria 9 - Publikasi Mahasiswa</p>
                <p class="text-sm text-gray-500">Tahun {{ $year }}</p>
            </div>
            <div class="flex space-x-2">
                <form method="GET" class="flex items-center space-x-2">
                    <select name="year" class="border border-gray-300 rounded px-3 py-2 text-sm">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 text-sm">
                        Filter
                    </button>
                </form>
                <a href="{{ route('reports.lam-infokom.export', ['year' => $year]) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center text-sm">
                    <i class="fas fa-download mr-2"></i>
                    Export PDF
                </a>
                <a href="{{ route('reports.index') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Publikasi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publishedPublications->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-green-200 p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Published {{ $year }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publishedPublications->where('publication_status', 'published')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-blue-200 p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Mahasiswa Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publishedPublications->unique('student_id')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border border-purple-200 p-6">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Program Studi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $publicationsByStudyProgram->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Statistik -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistik per Jenis Publikasi -->
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
            <h3 class="text-lg font-bold text-orange-600 mb-4">Publikasi per Jenis</h3>
            <div class="space-y-3">
                @foreach($publicationsByType as $type)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium text-gray-900">{{ $type->type }}</p>
                        <p class="text-sm text-gray-600">{{ $type->published }} published</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-orange-600">{{ $type->total }}</p>
                        <p class="text-xs text-gray-500">total</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Statistik per Program Studi -->
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
            <h3 class="text-lg font-bold text-orange-600 mb-4">Publikasi per Program Studi</h3>
            <div class="space-y-3">
                @foreach($publicationsByStudyProgram as $program)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium text-gray-900">{{ $program->study_program ?? 'Belum diisi' }}</p>
                        <p class="text-sm text-gray-600">{{ $program->published }} published</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-orange-600">{{ $program->total }}</p>
                        <p class="text-xs text-gray-500">total</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Daftar Publikasi Published -->
    <div class="bg-white rounded-xl shadow border border-orange-200">
        <div class="p-6 border-b border-orange-200">
            <h3 class="text-lg font-bold text-orange-600">Daftar Publikasi Published {{ $year }}</h3>
            <p class="text-sm text-gray-600">Publikasi yang sudah terbit di jurnal</p>
        </div>
        <div class="p-6">
            @if($publishedPublications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-orange-200 rounded-lg">
                        <thead class="bg-orange-500 text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">Mahasiswa</th>
                                <th class="py-3 px-4 text-left">Judul Publikasi</th>
                                <th class="py-3 px-4 text-left">Jurnal</th>
                                <th class="py-3 px-4 text-left">Tanggal Terbit</th>
                                <th class="py-3 px-4 text-left">DOI/URL</th>
                                <th class="py-3 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($publishedPublications as $publication)
                            <tr class="even:bg-orange-50 odd:bg-white hover:bg-orange-100">
                                <td class="py-3 px-4">
                                    <div class="font-bold">{{ $publication->student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $publication->student->studentProfile->nim ?? 'NIM belum diisi' }}</div>
                                    <div class="text-xs text-gray-400">{{ $publication->student->studentProfile->major ?? 'Program studi belum diisi' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold">{{ Str::limit($publication->title, 60) }}</div>
                                    <div class="text-sm text-gray-500">{{ $publication->publicationType->name ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium">{{ $publication->journal_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $publication->publisher ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm">{{ $publication->publication_date ? $publication->publication_date->format('d/m/Y') : 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($publication->doi)
                                        <div class="text-sm font-mono text-blue-600">{{ $publication->doi }}</div>
                                    @endif
                                    @if($publication->journal_url)
                                        <a href="{{ $publication->journal_url }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                            Lihat Artikel →
                                        </a>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('reports.certificate', $publication->id) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 flex items-center text-sm">
                                        <i class="fas fa-certificate mr-1"></i>
                                        Sertifikat
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Publikasi Published</h3>
                    <p class="text-gray-500">Tidak ada publikasi yang sudah terbit di tahun {{ $year }}.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistik per Dosen Pembimbing -->
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h3 class="text-lg font-bold text-orange-600 mb-4">Statistik per Dosen Pembimbing</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($publicationsBySupervisor as $supervisor)
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-gray-900">{{ $supervisor->supervisor_name }}</h4>
                    <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">{{ $supervisor->total }} total</span>
                </div>
                <div class="text-sm text-gray-600">
                    <p>Published: {{ $supervisor->published }}</p>
                    <p>Pending: {{ $supervisor->total - $supervisor->published }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
