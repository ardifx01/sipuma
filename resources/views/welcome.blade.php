<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <!-- Navigation - Fixed -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="ml-2 text-xl font-bold text-gray-900">Sipuma</span>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="flex items-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content with top padding -->
        <div class="pt-16">
            <!-- Hero Section -->
            <div class="relative bg-gradient-to-br from-pink-500 via-purple-500 to-blue-600 overflow-hidden">
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-gradient-to-r from-pink-600/20 to-purple-600/20"></div>
                </div>
                
                <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <!-- Logo -->
                        <div class="flex justify-center mb-8">
                            <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-2xl">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>

                        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">
                            <span class="block">Selamat Datang di</span>
                            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Sipuma</span>
                        </h1>
                        
                        <p class="mt-6 max-w-2xl mx-auto text-xl text-white/90">
                            Sistem Informasi Publikasi Mahasiswa - Platform terdepan untuk mengelola dan mempublikasikan karya akademik mahasiswa dengan mudah dan efisien.
                        </p>
                        
                        <div class="mt-10 flex justify-center space-x-4">
                            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-blue-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition duration-150 ease-in-out shadow-lg">
                                Mulai Sekarang
                                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Fitur Unggulan
                        </h2>
                        <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                            Nikmati berbagai fitur canggih yang memudahkan proses publikasi akademik Anda.
                        </p>
                    </div>

                    <div class="mt-20">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Feature 1 -->
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative p-6 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none flex items-top justify-start space-x-6">
                                    <div class="space-y-2">
                                        <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-purple-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Publikasi Multi-Tipe</h3>
                                        <p class="text-gray-600">Dukung berbagai jenis publikasi: Artikel, HKI, Buku, dan lainnya dengan format yang fleksibel.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative p-6 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none flex items-top justify-start space-x-6">
                                    <div class="space-y-2">
                                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Manajemen File</h3>
                                        <p class="text-gray-600">Upload, preview, dan download dokumen dengan sistem manajemen file yang aman dan terorganisir.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-green-600 rounded-lg blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative p-6 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none flex items-top justify-start space-x-6">
                                    <div class="space-y-2">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-green-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Otomatis</h3>
                                        <p class="text-gray-600">Sistem verifikasi cerdas dengan validasi otomatis untuk memastikan kualitas publikasi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="bg-gray-50 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">500+</div>
                            <div class="text-gray-600">Publikasi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">1000+</div>
                            <div class="text-gray-600">Mahasiswa</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">50+</div>
                            <div class="text-gray-600">Dosen</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-900">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div class="flex justify-center mb-4">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="ml-2 text-xl font-bold text-white">Sipuma</span>
                        </div>
                        <p class="text-gray-400">© 2024 Sipuma. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
