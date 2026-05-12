@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5" data-aos="fade-up">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent text-center pt-4 pb-0 border-0">
                    <div class="mb-3">
                        <div class="icon-circle">
                            <i class="fas fa-key fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold" style="color: #0D5C63;">Forgot Password?</h3>
                    <p class="text-muted" style="color: #6B7280;">Enter your email to receive reset instructions</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <span>{{ $error }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ url($role . '/reset-password') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: #2D3748;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-envelope" style="color: #0D5C63;"></i>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 ps-2" 
                                       placeholder="Enter your email address" required>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted" style="color: #6B7280;">
                                    <i class="fas fa-info-circle me-1"></i>We'll send a password reset link to this email address
                                </small>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-gradient w-100 py-3 rounded-pill fw-semibold">
                            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                        </button>
                        
                        <div class="text-center mt-4">
                            <a href="{{ url($role . '/login') }}" class="text-decoration-none" style="color: #0D5C63;">
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
.alert-success {
    background: #D1FAE5;
    color: #059669;
    border-left: 4px solid #059669;
    border-radius: 12px;
}

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
@endsection