@extends('layouts.app')

@section('title', 'Reset Password - Librarian')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent text-center pt-4">
                    <i class="fas fa-lock fa-3x" style="color: #0D5C63;"></i>
                    <h3 class="mt-2">Reset Password</h3>
                    <p class="text-muted">Librarian - Enter your new password</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    
                    <form action="{{ route('librarian.password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        
                        <div class="mb-3">
                            <label class="form-label">New Password *</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Min 8 characters</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <button type="submit" class="btn-primary-custom w-100 py-2">
                            <i class="fas fa-save me-2"></i>Reset Password
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('librarian.login') }}">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection