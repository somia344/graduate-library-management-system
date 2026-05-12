@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7" data-aos="fade-up">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent text-center pt-4 pb-0 border-0">
                    <div class="mb-3">
                        <div class="icon-circle">
                            <i class="fas fa-user-plus fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold" style="color: #0D5C63;">Student Registration</h3>
                    <p class="text-muted" style="color: #6B7280;">Create your library account</p>
                </div>
                <div class="card-body p-4">
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
                    
                    <form action="{{ route('student.register.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" required value="{{ old('full_name') }}" placeholder="Enter your full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Father Name *</label>
                                <input type="text" name="father_name" class="form-control" required value="{{ old('father_name') }}" placeholder="Enter father's name">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Email Address *</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="student@example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Phone Number *</label>
                                <input type="text" name="phone_number" class="form-control" required value="{{ old('phone_number') }}" placeholder="0300-1234567">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Department *</label>
                                <select name="department" class="form-select" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <option value="BS (Information Technology)" {{ old('department') == 'BS (Information Technology)' ? 'selected' : '' }}> BS (Information Technology)</option>
                                    <option value="BS (Computer Science)" {{ old('department') == 'BS (Computer Science)' ? 'selected' : '' }}> BS (Computer Science)</option>
                                    <option value="BS (Mathematics)" {{ old('department') == 'BS (Mathematics)' ? 'selected' : '' }}>BS (Mathematics)</option>
                                    <option value="BS (English)" {{ old('department') == 'BS (English)' ? 'selected' : '' }}> BS (English)</option>
                                    <option value="BS (Education)" {{ old('department') == 'BS (Education)' ? 'selected' : '' }}> BS (Education)</option>
                                    <option value="BS (Islamiyat)" {{ old('department') == 'BS (Islamiyat)' ? 'selected' : '' }}> BS (Islamiyat)</option>
                                    <option value="BS (Physics)" {{ old('department') == 'BS (Physics)' ? 'selected' : '' }}> BS (Physics)</option>
                                    <option value="BS (Chemistry)" {{ old('department') == 'BS (Chemistry)' ? 'selected' : '' }}> BS (Chemistry)</option>
                                    <option value="BS (Biology)" {{ old('department') == 'BS (Biology)' ? 'selected' : '' }}> BS (Biology)</option>
                                    <option value="BS (Economics)" {{ old('department') == 'BS (Economics)' ? 'selected' : '' }}> BS (Economics)</option>
                                    <option value="BS (Political Science)" {{ old('department') == 'BS (Political Science)' ? 'selected' : '' }}> BS (Political Science)</option>
                                    <option value="BS (Psychology)" {{ old('department') == 'BS (Psychology)' ? 'selected' : '' }}> BS (Psychology)</option>
                                    <option value="BS (Sociology)" {{ old('department') == 'BS (Sociology)' ? 'selected' : '' }}> BS (Sociology)</option>
                                    <option value="BS (Commerce)" {{ old('department') == 'BS (Commerce)' ? 'selected' : '' }}> BS (Commerce)</option>
                                    <option value="BS (Statistics)" {{ old('department') == 'BS (Statistics)' ? 'selected' : '' }}> BS (Statistics)</option>
                                    <option value="BS (Urdu)" {{ old('department') == 'BS (Urdu)' ? 'selected' : '' }}>BS (Urdu)</option>
                                    <option value="BS (Zoology)" {{ old('department') == 'BS (Zoology)' ? 'selected' : '' }}> BS (Zoology)</option>
                                    <option value="BS (Botany)" {{ old('department') == 'BS (Botany)' ? 'selected' : '' }}> BS (Botany)</option>
                                    <option value="BS (Pak Studies)" {{ old('department') == 'BS (Pak Studies)' ? 'selected' : '' }}> BS (Pak Studies)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Class / Semester *</label>
                                <select name="class" class="form-select" required>
                                    <option value="" disabled selected>Select Class</option>
                                    <option value="1st Year" {{ old('class') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('class') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="BS 1st Semester" {{ old('class') == 'BS 1st Semester' ? 'selected' : '' }}>BS 1st Semester</option>
                                    <option value="BS 2nd Semester" {{ old('class') == 'BS 2nd Semester' ? 'selected' : '' }}>BS 2nd Semester</option>
                                    <option value="BS 3rd Semester" {{ old('class') == 'BS 3rd Semester' ? 'selected' : '' }}>BS 3rd Semester</option>
                                    <option value="BS 4th Semester" {{ old('class') == 'BS 4th Semester' ? 'selected' : '' }}>BS 4th Semester</option>
                                    <option value="BS 5th Semester" {{ old('class') == 'BS 5th Semester' ? 'selected' : '' }}>BS 5th Semester</option>
                                    <option value="BS 6th Semester" {{ old('class') == 'BS 6th Semester' ? 'selected' : '' }}>BS 6th Semester</option>
                                    <option value="BS 7th Semester" {{ old('class') == 'BS 7th Semester' ? 'selected' : '' }}>BS 7th Semester</option>
                                    <option value="BS 8th Semester" {{ old('class') == 'BS 8th Semester' ? 'selected' : '' }}>BS 8th Semester</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Roll No *</label>
                                <input type="text" name="roll_no" class="form-control" required value="{{ old('roll_no') }}" placeholder="Enter your roll number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Registration No </label>
                                <input type="text" name="registration_no" class="form-control" placeholder="Enter registration number">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #2D3748;">Address *</label>
                            <textarea name="address" class="form-control" rows="2" required placeholder="Enter your complete address">{{ old('address') }}</textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-lock" style="color: #0D5C63;"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control border-start-0" required id="password" placeholder="Enter password">
                                    <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword()">
                                        <i class="far fa-eye-slash" id="toggleIcon" style="color: #6B7280;"></i>
                                    </span>
                                </div>
                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #2D3748;">Confirm Password *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-check-circle" style="color: #0D5C63;"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" class="form-control border-start-0" required id="confirm_password" placeholder="Confirm password">
                                </div>
                            </div>
                        </div>
                        
                       

                        <button type="submit" class="btn-gradient w-100 py-3 rounded-pill fw-semibold">
                            <i class="fas fa-user-check me-2"></i>Create Account
                        </button>
                        
                        <div class="text-center mt-4 pt-2 d-flex justify-content-center align-items-center gap-2 flex-wrap">
                            <span style="color: #4A5568;">Already have an account?</span>
                            <a href="{{ route('student.login') }}" class="btn-login-small">
                                Login Here
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

.form-control, .form-select {
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
    outline: none;
}

/* Input Group Specific */
.input-group .form-control {
    border-radius: 0 12px 12px 0;
    border-left: none;
}

.input-group .input-group-text:first-child {
    border-radius: 12px 0 0 12px;
}

.input-group .input-group-text:last-child {
    border-radius: 0 12px 12px 0;
    cursor: pointer;
}

/* Alert Styling */
.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border-left: 4px solid #DC2626;
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

/* Small Login Button */
.btn-login-small {
    background: transparent;
    color: #0D5C63;
    border: 1.5px solid #0D5C63;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 6px 16px;
    border-radius: 50px;
}

.btn-login-small:hover {
    background: #0D5C63;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(13, 92, 99, 0.2);
}

/* Password Toggle */
.cursor-pointer {
    cursor: pointer;
}

/* Form Check */
.form-check-input:checked {
    background-color: #0D5C63;
    border-color: #0D5C63;
}

.form-check-input:focus {
    box-shadow: 0 0 0 2px rgba(13, 92, 99, 0.25);
    border-color: #0D5C63;
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