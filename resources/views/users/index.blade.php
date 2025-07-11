@extends('layouts.app')

@section('title', 'Publikasi Mahasiswa dan Dosen')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="p-6 space-y-8">
        <!-- Header dengan desain modern -->
        <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-2xl p-8 text-white shadow-xl">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-2">Publikasi Mahasiswa dan Dosen</h1>
                        <p class="text-purple-100 text-lg">Lihat semua publikasi mahasiswa dan dosen dalam sistem</p>
                        <p class="text-purple-200 text-sm mt-1">Akses data publikasi, role, dan akun</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('users.create') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 backdrop-blur-sm border border-white border-opacity-30">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah User
                        </a>
                    </div>
                </div>
            </div>
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600 dark:text-green-400"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-600 dark:text-red-400"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table dengan desain modern -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    Daftar Semua User
                </h2>
            </div>
            <div class="p-6">
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">NIM/NIP</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-semibold text-sm">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-900 dark:text-white">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->hasRole('mahasiswa'))
                                            <span class="font-mono text-blue-700 dark:text-blue-300">{{ $user->studentProfile->nim ?? '-' }}</span>
                                        @elseif($user->hasRole('dosen'))
                                            <span class="font-mono text-orange-700 dark:text-orange-300">{{ $user->dosenProfile->nidn ?? '-' }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            @if($user->hasRole('mahasiswa'))
                                            <a href="{{ route('dashboard.student-publications', $user->id) }}" class="bg-orange-100 hover:bg-orange-200 text-orange-700 font-semibold py-2 px-3 rounded-lg transition-all duration-200 text-xs border border-orange-300">
                                                <i class="fas fa-list mr-1"></i> Lihat Publikasi
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Belum ada user</h3>
                        <p class="text-gray-600 dark:text-gray-400">User akan muncul di sini setelah ditambahkan</p>
                    </div>
                @endif
                
                <!-- Pagination -->
                @if($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 