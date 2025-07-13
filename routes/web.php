<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
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
    });
});

// Test routes untuk debugging
Route::get('/test-admin', function() {
    return 'Admin access OK - User: ' . Auth::user()->name . ' - Roles: ' . Auth::user()->roles->pluck('name')->implode(', ');
})->middleware(['auth', 'role:admin']);

Route::get('/test-review', function() {
    $publications = App\Models\Publication::where('admin_status', 'pending')->count();
    return "Pending publications: $publications";
})->middleware(['auth', 'role:admin']);

Route::get('/test-review-page', function() {
    $publications = App\Models\Publication::where('admin_status', 'pending')
        ->with(['student', 'publicationType', 'student.studentProfile'])
        ->latest()
        ->get();
    return view('dashboard.admin-review', compact('publications'));
})->middleware(['auth', 'role:admin']);

// Test route tanpa middleware role
Route::get('/test-review-no-role', function() {
    $publications = App\Models\Publication::where('admin_status', 'pending')
        ->with(['student', 'publicationType', 'student.studentProfile'])
        ->latest()
        ->get();
    return view('dashboard.admin-review', compact('publications'));
})->middleware(['auth']);

// Test route untuk debugging middleware permission
Route::get('/test-permission-view-publications', function() {
    return 'Permission view-publications middleware working! User: ' . Auth::user()->name;
})->middleware(['auth', 'permission:view-publications']);

// Test route untuk debugging permission tanpa middleware
Route::get('/test-permission-check', function() {
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
Route::get('/test-role-mahasiswa', function() {
    return 'Role mahasiswa middleware working! User: ' . Auth::user()->name;
})->middleware(['auth', 'role:mahasiswa']);

// Test route untuk debugging role middleware dengan multiple roles
Route::get('/test-role-mahasiswa-admin', function() {
    return 'Role mahasiswa,admin middleware working! User: ' . Auth::user()->name;
})->middleware(['auth', 'role:mahasiswa,admin']);

require __DIR__.'/auth.php';
