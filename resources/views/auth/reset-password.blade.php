@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5" data-aos="fade-up">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent text-center pt-4 pb-0 border-0">
                    <div class="mb-3">
                        <div class="icon-circle">
                            <i class="fas fa-lock fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold" style="color: #0D5C63;">Reset Password</h3>
                    <p class="text-muted" style="color: #6B7280;">{{ ucfirst($role ?? 'student') }} - Enter your new password</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <span>{{ $error }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ route(($role ?? 'student') . '.password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="role" value="{{ $role ?? 'student' }}">
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #2D3748;">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-lock" style="color: #0D5C63;"></i>
                                </span>
                                <input type="password" name="password" class="form-control border-start-0 ps-2" 
                                       placeholder="Enter new password" required id="password">
                                <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword()">
                                    <i class="far fa-eye-slash" id="toggleIcon" style="color: #6B7280; cursor: pointer;"></i>
                                </span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted" style="color: #6B7280;">
                                    <i class="fas fa-info-circle me-1"></i>Password must contain 1 uppercase, 1 number, 1 special character, and at least 8 characters
                                </small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #2D3748;">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-check-circle" style="color: #0D5C63;"></i>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0 ps-2" 
                                       placeholder="Confirm your new password" required id="confirm_password">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-gradient w-100 py-3 rounded-pill fw-semibold">
                            <i class="fas fa-save me-2"></i>Reset Password
                        </button>
                        
                        <div class="text-center mt-4">
                            <a href="{{ route(($role ?? 'student') . '.login') }}" class="text-decoration-none" style="color: #0D5C63;">
                                <i class="fas fa-arrow-left me-1"></i>Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Icon Circle */
.icon-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(13, 92, 99, 0.1) 0%, rgba(13, 92, 99, 0.05) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.icon-circle i {
    color: #0D5C63;
}

/* Input Group Styling */
.input-group-text {
    border: 1px solid #E2E8F0;
    border-radius: 12px 0 0 12px;
    background: white;
}

.form-control {
    border: 1px solid #E2E8F0;
    border-radius: 0 12px 12px 0;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0D5C63;
    box-shadow: none;
    outline: none;
}

.form-control:focus + .input-group-text,
.input-group:focus-within .input-group-text {
    border-color: #0D5C63;
}

/* Alert Styling */
.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border-left: 4px solid #DC2626;
    border-radius: 12px;
}

/* Card Hover Effect */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

/* Button Styling */
.btn-gradient {
    background: #0D5C63;
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    background: #084C52;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 92, 99, 0.3);
}

/* Password Toggle Button */
.cursor-pointer {
    cursor: pointer;
    border-radius: 0 12px 12px 0;
}

.input-group .form-control {
    position: relative;
    z-index: 1;
}

.input-group .input-group-text:last-child {
    border-radius: 0 12px 12px 0;
}

/* Form Label */
.form-label {
    font-weight: 600;
    margin-bottom: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
    }
    
    .icon-circle i {
        font-size: 2rem;
    }
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    }
}
</script>
@endsection