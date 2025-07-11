@extends('layouts.app')

@section('title', 'Tambah User - Sipuma')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tambah User Baru</h1>
            <p class="text-gray-600">Tambah user baru ke dalam sistem</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-ghost">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nama Lengkap *</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="input input-bordered w-full @error('name') input-error @enderror" 
                            placeholder="Masukkan nama lengkap">
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Email *</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="input input-bordered w-full @error('email') input-error @enderror" 
                            placeholder="user@example.com">
                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Password *</span>
                        </label>
                        <input type="password" name="password" 
                            class="input input-bordered w-full @error('password') input-error @enderror" 
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Konfirmasi Password *</span>
                        </label>
                        <input type="password" name="password_confirmation" 
                            class="input input-bordered w-full" 
                            placeholder="Ulangi password">
                    </div>

                    <!-- Role -->
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Role *</span>
                        </label>
                        <select name="role" class="select select-bordered w-full @error('role') select-error @enderror">
                            <option value="">Pilih role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-4 pt-6">
                    <a href="{{ route('users.index') }}" class="btn btn-ghost">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 