<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Publication routes for students - using auth middleware only for now
    Route::middleware('auth')->group(function () {
        Route::resource('publications', PublicationController::class);
    });
    // Route download publikasi di luar middleware agar admin bisa akses
    Route::get('publications/{publication}/download', [PublicationController::class, 'download'])->name('publications.download');
    Route::get('publications/{publication}/download-loa', [PublicationController::class, 'downloadLoA'])->name('publications.download-loa');
    
    // Update status routes
    Route::get('publications/{publication}/update-status', [PublicationController::class, 'showUpdateStatus'])->name('publications.update-status-form');
    Route::put('publications/{publication}/update-status', [PublicationController::class, 'updateStatus'])->name('publications.update-status-submit');
    
    // Temporary bypass route untuk testing
    Route::get('/test-update-status/{id}', function($id) {
        $publication = \App\Models\Publication::where('student_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Return enhanced HTML form with app-like design
        return '
        <!DOCTYPE html>
        <html lang="en" class="h-full bg-gray-50">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Update Status Publikasi - SIPUMA</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            <link rel="preconnect" href="https://fonts.bunny.net">
            <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        </head>
        <body class="h-full font-sans antialiased" style="font-family: Inter, ui-sans-serif;">
            <div class="min-h-screen bg-gray-50">
                <!-- Navigation - Fixed -->
                <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-lg border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <!-- Logo -->
                                <div class="flex-shrink-0 flex items-center">
                                    <a href="/dashboard" class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-bold text-gray-900">Sipuma</span>
                                    </a>
                                </div>

                                <!-- Navigation Links -->
                                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                    <a href="/dashboard" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-300">
                                        Dashboard
                                    </a>
                                    <a href="/publications" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700">
                                        Publikasi Saya
                                    </a>
                                </div>
                            </div>

                            <!-- User Menu -->
                            <div class="flex items-center space-x-4">
                                <!-- Profile Dropdown -->
                                <div class="relative">
                                    <button id="user-menu-button" class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" type="button">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">' . substr($user->name, 0, 1) . '</span>
                                        </div>
                                        <span class="text-gray-700">' . $user->name . '</span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div id="user-menu-dropdown" class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu">
                                        <div class="py-1">
                                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                Profile
                                            </a>
                                            <form method="POST" action="/logout">
                                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Breadcrumb -->
                <div class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-4">
                                <li>
                                    <div>
                                        <a href="/dashboard" class="text-gray-400 hover:text-gray-500">
                                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <a href="/dashboard" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-4 text-sm font-medium text-gray-900">Update Status Publikasi</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Page Content - with top padding to account for fixed navigation -->
                <main class="pt-20 pb-8">
                    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Header -->
                        <div class="mb-8">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h1 class="text-2xl font-bold text-orange-600">Update Status Publikasi</h1>
                                    <p class="text-sm text-gray-600">Update status dari LoA menjadi Published</p>
                                </div>
                            </div>
                        </div>

                    <!-- Publication Info Card -->
                    <div class="bg-white shadow rounded-lg mb-6 border border-orange-200">
                        <div class="px-6 py-4 border-b border-orange-200">
                            <h3 class="text-lg font-medium text-orange-600">Informasi Publikasi</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Judul</dt>
                                    <dd class="text-sm text-gray-900">' . $publication->title . '</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Saat Ini</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            LoA (Letter of Acceptance)
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipe Publikasi</dt>
                                    <dd class="text-sm text-gray-900">' . (is_array($publication->tipe_publikasi) ? implode(', ', $publication->tipe_publikasi) : $publication->tipe_publikasi) . '</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Target Status</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Update Form -->
                    <div class="bg-white shadow rounded-lg border border-orange-200">
                        <div class="px-6 py-4 border-b border-orange-200">
                            <h3 class="text-lg font-medium text-orange-600">Informasi Publikasi Terbit</h3>
                            <p class="mt-1 text-sm text-gray-600">Masukkan detail publikasi yang sudah terbit</p>
                        </div>
                        <form action="/test-update-status-submit/' . $publication->id . '" method="POST" class="px-6 py-6 space-y-6">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="PUT">
                            
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="journal_name" class="block text-sm font-medium text-gray-700">Nama Jurnal</label>
                                    <div class="mt-1">
                                        <input type="text" name="journal_name" id="journal_name" required 
                                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               placeholder="Masukkan nama jurnal">
                                    </div>
                                </div>
                                
                                <div class="sm:col-span-2">
                                    <label for="journal_url" class="block text-sm font-medium text-gray-700">URL Artikel</label>
                                    <div class="mt-1">
                                        <input type="url" name="journal_url" id="journal_url" required 
                                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               placeholder="https://example.com/jurnal/artikel-anda">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
                                    <div class="mt-1">
                                        <input type="text" name="publisher" id="publisher" required 
                                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               placeholder="Nama penerbit">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="publication_date" class="block text-sm font-medium text-gray-700">Tanggal Terbit</label>
                                    <div class="mt-1">
                                        <input type="date" name="publication_date" id="publication_date" required 
                                               class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                                

                            </div>
                            
                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-orange-200">
                                <a href="/dashboard" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Batal
                                </a>
                                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Update ke Published
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        
        <script>
        // User menu dropdown functionality
        document.addEventListener("DOMContentLoaded", function() {
            const userMenuButton = document.getElementById("user-menu-button");
            const userMenuDropdown = document.getElementById("user-menu-dropdown");
            
            userMenuButton.addEventListener("click", function() {
                userMenuDropdown.classList.toggle("hidden");
            });
            
            // Close dropdown when clicking outside
            document.addEventListener("click", function(event) {
                if (!userMenuButton.contains(event.target)) {
                    userMenuDropdown.classList.add("hidden");
                }
            });
        });
        </script>
        </body>
        </html>
        ';
    })->name('test.update-status');
    
    // Temporary submit route untuk testing
    Route::put('/test-update-status-submit/{id}', function($id) {
        $publication = \App\Models\Publication::where('student_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);
        
        // Update publication status
        $publication->update([
            'publication_status' => 'published',
            'is_published' => true,
            'admin_status' => 'pending',
            'dosen_status' => 'pending',
            'journal_name' => request('journal_name'),
            'journal_url' => request('journal_url'),
            'publisher' => request('publisher'),
            'publication_date' => request('publication_date'),
        ]);
        
        return redirect('/dashboard')->with('success', 'Status publikasi berhasil diupdate ke Published!');
    })->name('test.update-status-submit');

    // Revision routes
    Route::get('publications/{publication}/revise', [PublicationController::class, 'revise'])->name('publications.revise');
    Route::post('publications/{publication}/revise', [PublicationController::class, 'submitRevision'])->name('publications.submit-revision');



    // Admin review routes
    Route::middleware('role:admin')->group(function () {
        Route::post('publications/{publication}/review', [PublicationController::class, 'review'])->name('publications.review');
        Route::get('/admin/review', [DashboardController::class, 'reviewPublications'])->name('dashboard.admin-review');
        Route::get('/admin/review/{id}', [DashboardController::class, 'reviewDetail'])->name('dashboard.admin-review-detail');
        Route::post('/admin/review/{id}/approve', [DashboardController::class, 'approvePublication'])->name('dashboard.admin-approve');
        Route::post('/admin/review/{id}/reject', [DashboardController::class, 'rejectPublication'])->name('dashboard.admin-reject');
        Route::get('/admin/approved-publications', [DashboardController::class, 'approvedPublications'])->name('dashboard.admin-approved-publications');
        Route::get('/admin/ajax-search-publications', [DashboardController::class, 'ajaxSearchPublications'])->name('dashboard.ajax-search-publications');
        Route::get('/admin/student/{user}/publications', [DashboardController::class, 'publicationsByStudent'])->name('dashboard.student-publications');
        Route::get('/admin/publications', [DashboardController::class, 'adminAllPublications'])->name('dashboard.admin-all-publications');
    });

    // Dosen review routes
    Route::middleware('role:dosen')->group(function () {
        Route::get('/dosen/review', [DashboardController::class, 'dosenReviewPublications'])->name('dashboard.dosen-review');
        Route::get('/dosen/review/{id}', [DashboardController::class, 'dosenReviewDetail'])->name('dashboard.dosen-review-detail');
        Route::post('/dosen/review/{id}/approve', [DashboardController::class, 'dosenApprovePublication'])->name('dashboard.dosen-approve');
        Route::post('/dosen/review/{id}/reject', [DashboardController::class, 'dosenRejectPublication'])->name('dashboard.dosen-reject');
        Route::get('/dosen/student/{user}/publications', [DashboardController::class, 'publicationsByStudent'])->name('dashboard.dosen-student-publications');
        Route::get('/dosen/all-publications', [DashboardController::class, 'dosenAllPublications'])->name('dashboard.dosen-all-publications');
    });

    // Student-Dosen Management routes
    Route::middleware('auth')->group(function () {
        Route::get('/manage-students', [DashboardController::class, 'manageStudents'])->name('dashboard.manage-students');
        Route::post('/assign-supervisor/{studentId}', [DashboardController::class, 'assignSupervisor'])->name('dashboard.assign-supervisor');
        Route::delete('/remove-supervisor/{studentId}', [DashboardController::class, 'removeSupervisor'])->name('dashboard.remove-supervisor');
    });

    // User management routes for admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        
        // Laporan Akreditasi LAM Infokom
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/lam-infokom', [ReportController::class, 'lamInfokomReport'])->name('reports.lam-infokom');
        Route::get('/reports/lam-infokom/export', [ReportController::class, 'exportLamInfokomPDF'])->name('reports.lam-infokom.export');
        Route::get('/reports/certificate/{publication}', [ReportController::class, 'generateCertificate'])->name('reports.certificate');
        Route::get('/reports/stats', [ReportController::class, 'dashboardStats'])->name('reports.stats');
    });
});

// Test routes untuk debugging
Route::get('/test-admin', function () {
    return 'Admin access OK - User: '.Auth::user()->name.' - Roles: '.Auth::user()->roles->pluck('name')->implode(', ');
})->middleware(['auth', 'role:admin']);

Route::get('/test-review', function () {
    $publications = App\Models\Publication::where('admin_status', 'pending')->count();

    return "Pending publications: $publications";
})->middleware(['auth', 'role:admin']);

Route::get('/test-review-page', function () {
    $publications = App\Models\Publication::where('admin_status', 'pending')
        ->with(['student', 'publicationType', 'student.studentProfile'])
        ->latest()
        ->get();

    return view('dashboard.admin-review', compact('publications'));
})->middleware(['auth', 'role:admin']);

// Test route tanpa middleware role
Route::get('/test-review-no-role', function () {
    $publications = App\Models\Publication::where('admin_status', 'pending')
        ->with(['student', 'publicationType', 'student.studentProfile'])
        ->latest()
        ->get();

    return view('dashboard.admin-review', compact('publications'));
})->middleware(['auth']);

// Test route untuk debugging middleware permission
Route::get('/test-permission-view-publications', function () {
    return 'Permission view-publications middleware working! User: '.Auth::user()->name;
})->middleware(['auth', 'permission:view-publications']);

// Test route untuk debugging permission tanpa middleware
Route::get('/test-permission-check', function () {
    $user = Auth::user();

    return [
        'user' => $user->name,
        'roles' => $user->roles->pluck('name'),
        'permissions' => $user->getAllPermissions()->pluck('name'),
        'has_view_publications' => $user->hasPermissionTo('view publications'),
        'has_create_publications' => $user->hasPermissionTo('create publications'),
    ];
})->middleware(['auth']);

// Test route untuk debugging role middleware
Route::get('/test-role-mahasiswa', function () {
    return 'Role mahasiswa middleware working! User: '.Auth::user()->name;
})->middleware(['auth', 'role:mahasiswa']);

// Test route untuk debugging role middleware dengan multiple roles
Route::get('/test-role-mahasiswa-admin', function () {
    return 'Role mahasiswa,admin middleware working! User: '.Auth::user()->name;
})->middleware(['auth', 'role:mahasiswa,admin']);

require __DIR__.'/auth.php';
