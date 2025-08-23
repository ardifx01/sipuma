@extends('layouts.app')

@section('title', 'Laporan Akreditasi - SIPUMA')

@section('content')
<div class="min-h-screen bg-orange-50 p-6 space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 shadow border border-orange-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-orange-600">Laporan Akreditasi</h1>
                <p class="text-gray-700 mt-1">Generate dokumen untuk keperluan akreditasi LAM Infokom</p>
            </div>
            <div class="text-right">
                <a href="{{ route('dashboard') }}" class="bg-white text-orange-600 border border-orange-500 px-4 py-2 rounded hover:bg-orange-50 flex items-center text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Menu Laporan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Laporan LAM Infokom -->
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Laporan LAM Infokom</h3>
                    <p class="text-sm text-gray-600">Kriteria 9 - Publikasi</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Generate laporan statistik publikasi untuk keperluan akreditasi LAM Infokom Kriteria 9.</p>
            <div class="space-y-2">
                <a href="{{ route('reports.lam-infokom') }}" class="w-full bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 flex items-center justify-center text-sm">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Lihat Laporan
                </a>
                <a href="{{ route('reports.lam-infokom.export') }}" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center justify-center text-sm">
                    <i class="fas fa-download mr-2"></i>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Sertifikat Publikasi -->
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Sertifikat Publikasi</h3>
                    <p class="text-sm text-gray-600">Bukti Publikasi Mahasiswa</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Generate sertifikat publikasi untuk mahasiswa yang sudah berhasil mempublikasikan artikel.</p>
            <a href="{{ route('publications.index') }}" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center justify-center text-sm">
                <i class="fas fa-certificate mr-2"></i>
                Lihat Publikasi
            </a>
        </div>

        <!-- Statistik Dashboard -->
        <div class="bg-white rounded-xl shadow border border-orange-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Statistik Real-time</h3>
                    <p class="text-sm text-gray-600">Dashboard Statistik</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Lihat statistik publikasi real-time untuk monitoring perkembangan.</p>
            <div id="stats-container" class="space-y-2">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="bg-orange-50 p-2 rounded">
                        <div class="font-bold text-orange-600" id="total-pubs">-</div>
                        <div class="text-xs text-gray-600">Total Publikasi</div>
                    </div>
                    <div class="bg-green-50 p-2 rounded">
                        <div class="font-bold text-green-600" id="published-this-year">-</div>
                        <div class="text-xs text-gray-600">Published {{ date('Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Akreditasi -->
    <div class="bg-white rounded-xl shadow border border-orange-200 p-6">
        <h2 class="text-xl font-bold text-orange-600 mb-4">Informasi Akreditasi LAM Infokom</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Kriteria 9 - Publikasi</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Jumlah publikasi mahasiswa per tahun</li>
                    <li>• Jenis publikasi (Jurnal, Konferensi, Buku, HKI)</li>
                    <li>• Kualitas jurnal (indexing, impact factor)</li>
                    <li>• Distribusi publikasi per program studi</li>
                    <li>• Bukti publikasi (DOI, URL, ISSN)</li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Dokumen yang Dihasilkan</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Laporan statistik publikasi tahunan</li>
                    <li>• Daftar lengkap publikasi mahasiswa</li>
                    <li>• Rekap per program studi</li>
                    <li>• Sertifikat publikasi mahasiswa</li>
                    <li>• Bukti review dan approval</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Load real-time stats
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("reports.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-pubs').textContent = data.total_publications;
            document.getElementById('published-this-year').textContent = data.published_this_year;
        })
        .catch(error => console.error('Error loading stats:', error));
});
</script>
@endsection
