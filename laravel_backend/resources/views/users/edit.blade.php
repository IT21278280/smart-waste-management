@extends('layouts.app')

@section('title', 'Edit User - Smart Waste Management')
@section('page-title', 'Edit User')
@section('page-description', 'Update user information and settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header text-white" style="background-color: var(--primary-color); border-bottom: 1px solid var(--gray-200);">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-user-edit me-2"></i>Edit User: {{ $user->name }}</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') ?? $user->name }}" 
                               placeholder="Enter user's full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') ?? $user->email }}" 
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-4">
                        <label for="phone" class="form-label fw-semibold">Phone Number</label>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') ?? $user->phone }}" 
                               placeholder="Enter phone number (optional)">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Leave empty to keep current password">
                        <div class="form-text">Only fill this if you want to change the password</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Confirm new password">
                    </div>

                    <!-- User Settings -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">User Settings</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" 
                                   {{ (old('email_verified') ?? $user->email_verified_at) ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verified">
                                Email Verified
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" 
                                   {{ (old('is_admin') ?? $user->is_admin) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_admin">
                                Admin User
                            </label>
                        </div>
                    </div>

                    <!-- User Statistics -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">User Statistics</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $user->reports_count ?? 0 }}</h5>
                                        <p class="card-text">Total Reports</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $user->created_at->format('M d, Y') }}</h5>
                                        <p class="card-text">Member Since</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info">
                            <i class="fas fa-eye me-2"></i>View Details
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
