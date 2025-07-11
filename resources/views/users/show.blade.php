@extends('layouts.app')

@section('title', 'Detail User - Sipuma')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
            <p class="text-gray-600">Informasi lengkap user</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit User
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-ghost">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- User Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-20">
                            <span class="text-2xl">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        @foreach($user->roles as $role)
                            <span class="badge badge-{{ $role->name === 'admin' ? 'error' : ($role->name === 'dosen' ? 'warning' : 'info') }} mt-2">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status Email:</span>
                        <span class="text-sm font-medium">
                            @if($user->email_verified_at)
                                <span class="text-success">✓ Verified</span>
                            @else
                                <span class="text-warning">✗ Unverified</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Tanggal Daftar:</span>
                        <span class="text-sm font-medium">{{ $user->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Terakhir Update:</span>
                        <span class="text-sm font-medium">{{ $user->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Publications -->
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Publikasi ({{ $user->publications->count() }})
                    </h2>
                    
                    @if($user->publications->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Sumber</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->publications->take(5) as $publication)
                                    <tr>
                                        <td>
                                            <div class="font-medium">{{ Str::limit($publication->title, 40) }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline">{{ $publication->sumber_artikel }}</span>
                                        </td>
                                        <td>
                                            @if($publication->admin_status === 'pending')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($publication->admin_status === 'approved')
                                                <span class="badge badge-success">Disetujui</span>
                                            @else
                                                <span class="badge badge-error">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-sm">{{ $publication->created_at->format('d M Y') }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($user->publications->count() > 5)
                            <div class="text-center mt-4">
                                <span class="text-sm text-gray-500">
                                    Dan {{ $user->publications->count() - 5 }} publikasi lainnya...
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Belum ada publikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 