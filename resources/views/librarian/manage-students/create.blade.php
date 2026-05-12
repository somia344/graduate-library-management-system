@extends('layouts.dashboard')

@section('title', 'Add Student')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <div class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="text-center">
                    <i class="fas fa-user-shield fa-3x" style="color: #FFFFFF;"></i>
                    <h5 class="mt-2 fw-bold" style="color: white;">Librarian Panel</h5>
                    <p class="small" style="color: rgba(255,255,255,0.8);">Graduate Library</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <nav class="sidebar-nav">
                <a class="sidebar-link {{ request()->routeIs('librarian.dashboard') ? 'active' : '' }}" href="{{ route('librarian.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.books.index') ? 'active' : '' }}" href="{{ route('librarian.books.index') }}">
                    <i class="fas fa-book"></i> Manage Books
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.books.create') ? 'active' : '' }}" href="{{ route('librarian.books.create') }}">
                    <i class="fas fa-plus-circle"></i> Add Book
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.students.index') ? 'active' : '' }}" href="{{ route('librarian.students.index') }}">
                    <i class="fas fa-users"></i> Manage Students
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.students.create') ? 'active' : '' }}" href="{{ route('librarian.students.create') }}">
                    <i class="fas fa-user-plus"></i> Add Student
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.issue-books.index') ? 'active' : '' }}" href="{{ route('librarian.issue-books.index') }}">
                    <i class="fas fa-exchange-alt"></i> Issue Book
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.return-books.index') ? 'active' : '' }}" href="{{ route('librarian.return-books.index') }}">
                    <i class="fas fa-undo-alt"></i> Return Book
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.book-requests.index') ? 'active' : '' }}" href="{{ route('librarian.book-requests.index') }}">
                    <i class="fas fa-question-circle"></i> Book Request
                    @php $pendingCount = \App\Models\BookRequest::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge-notification">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.reservations*') ? 'active' : '' }}" href="{{ route('librarian.reservations.index') }}">
                    <i class="fas fa-bookmark"></i> Book Reservations
                    @php
                        $pendingReservations = \App\Models\BookReservation::where('status', 'pending')->count();
                    @endphp
                    @if($pendingReservations > 0)
                        <span class="badge-notification">{{ $pendingReservations }}</span>
                    @endif
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.contact-messages.index') ? 'active' : '' }}" href="{{ route('librarian.contact-messages.index') }}">
                    <i class="fas fa-envelope"></i> Contact Message
                    @php $unreadCount = \App\Models\ContactMessage::where('status', 'unread')->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="badge-notification">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a class="sidebar-link {{ request()->routeIs('librarian.reports') ? 'active' : '' }}" href="{{ route('librarian.reports') }}">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="dashboard-main">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Add New Student</h2>
                    <p class="text-muted mt-1" style="color: #6B7280;">Register a new student in the library system</p>
                </div>
                <a href="{{ route('librarian.students.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to Students
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    @if(session('generated_password'))
                        <br><strong>Student Password:</strong> {{ session('generated_password') }}
                        <br><small>Please share this password with the student.</small>
                    @endif
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert-custom alert-error-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Add Student Form Card -->
            <div class="form-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-user-graduate me-2" style="color: #0D5C63;"></i> Student Information</h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('librarian.students.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name <span class="required">*</span>
                                </label>
                                <input type="text" name="full_name" class="form-control-custom" required value="{{ old('full_name') }}" placeholder="Enter full name">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-friends me-2"></i>Father Name <span class="required">*</span>
                                </label>
                                <input type="text" name="father_name" class="form-control-custom" required value="{{ old('father_name') }}" placeholder="Enter father's name">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address <span class="required">*</span>
                                </label>
                                <input type="email" name="email" class="form-control-custom" required value="{{ old('email') }}" placeholder="student@example.com">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone me-2"></i>Phone Number <span class="required">*</span>
                                </label>
                                <input type="text" name="phone_number" class="form-control-custom" required value="{{ old('phone_number') }}" placeholder="0300-1234567">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-building me-2"></i>Department <span class="required">*</span>
                                </label>
                                <select name="department" class="form-control-custom" required>
                                    <option value="" disabled selected>Select Department</option>
                                    <option value="BS (Information Technology)" {{ old('department') == 'BS (Information Technology)' ? 'selected' : '' }}>BS (Information Technology)</option>
                                    <option value="BS (Computer Science)" {{ old('department') == 'BS (Computer Science)' ? 'selected' : '' }}>BS (Computer Science)</option>
                                    <option value="BS (Mathematics)" {{ old('department') == 'BS (Mathematics)' ? 'selected' : '' }}>BS (Mathematics)</option>
                                    <option value="BS (English)" {{ old('department') == 'BS (English)' ? 'selected' : '' }}>BS (English)</option>
                                    <option value="BS (Education)" {{ old('department') == 'BS (Education)' ? 'selected' : '' }}>BS (Education)</option>
                                    <option value="BS (Islamiyat)" {{ old('department') == 'BS (Islamiyat)' ? 'selected' : '' }}>BS (Islamiyat)</option>
                                    <option value="BS (Physics)" {{ old('department') == 'BS (Physics)' ? 'selected' : '' }}>BS (Physics)</option>
                                    <option value="BS (Chemistry)" {{ old('department') == 'BS (Chemistry)' ? 'selected' : '' }}>BS (Chemistry)</option>
                                    <option value="BS (Biology)" {{ old('department') == 'BS (Biology)' ? 'selected' : '' }}>BS (Biology)</option>
                                    <option value="BS (Economics)" {{ old('department') == 'BS (Economics)' ? 'selected' : '' }}>BS (Economics)</option>
                                    <option value="BS (Political Science)" {{ old('department') == 'BS (Political Science)' ? 'selected' : '' }}>BS (Political Science)</option>
                                    <option value="BS (Psychology)" {{ old('department') == 'BS (Psychology)' ? 'selected' : '' }}>BS (Psychology)</option>
                                    <option value="BS (Sociology)" {{ old('department') == 'BS (Sociology)' ? 'selected' : '' }}>BS (Sociology)</option>
                                    <option value="BS (Commerce)" {{ old('department') == 'BS (Commerce)' ? 'selected' : '' }}>BS (Commerce)</option>
                                    <option value="BS (Statistics)" {{ old('department') == 'BS (Statistics)' ? 'selected' : '' }}>BS (Statistics)</option>
                                    <option value="BS (Urdu)" {{ old('department') == 'BS (Urdu)' ? 'selected' : '' }}>BS (Urdu)</option>
                                    <option value="BS (Zoology)" {{ old('department') == 'BS (Zoology)' ? 'selected' : '' }}>BS (Zoology)</option>
                                    <option value="BS (Botany)" {{ old('department') == 'BS (Botany)' ? 'selected' : '' }}>BS (Botany)</option>
                                    <option value="BS (Pak Studies)" {{ old('department') == 'BS (Pak Studies)' ? 'selected' : '' }}>BS (Pak Studies)</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-graduation-cap me-2"></i>Class / Semester <span class="required">*</span>
                                </label>
                                <select name="class" class="form-control-custom" required>
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
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card me-2"></i>Roll No <span class="required">*</span>
                                </label>
                                <input type="text" name="roll_no" class="form-control-custom" required value="{{ old('roll_no') }}" placeholder="Enter roll number">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-registered me-2"></i>Registration No
                                </label>
                                <input type="text" name="registration_no" class="form-control-custom" value="{{ old('registration_no') }}" placeholder="Enter registration number (optional)">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Address <span class="required">*</span>
                            </label>
                            <textarea name="address" class="form-control-custom" rows="2" required placeholder="Enter complete address">{{ old('address') }}</textarea>
                        </div>
                        
                        <!-- Password Fields -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password <span class="required">*</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password" name="password" class="form-control-custom" id="password" required placeholder="Enter password (min 8 characters)">
                                    <span class="password-toggle" onclick="togglePassword()">
                                        <i class="far fa-eye-slash" id="toggleIcon"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Password must be at least 8 characters</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle me-2"></i>Confirm Password <span class="required">*</span>
                                </label>
                                <div class="password-wrapper">
                                    <input type="password" name="password_confirmation" class="form-control-custom" id="password_confirmation" required placeholder="Confirm password">
                                    <span class="password-toggle" onclick="togglePasswordConfirm()">
                                        <i class="far fa-eye-slash" id="toggleIconConfirm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>Add Student
                            </button>
                            <a href="{{ route('librarian.students.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Container */
.dashboard-container {
    min-height: 100vh;
    background: #F5F7FA;
}

.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.dashboard-sidebar {
    width: 280px;
    min-width: 280px;
    background: #0D5C63;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    overflow-y: auto;
    padding: 25px 0;
    z-index: 100;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 0 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 0;
}

.sidebar-nav {
    padding: 0 20px;
    flex: 1;
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    margin: 5px 0;
    border-radius: 10px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
    width: 100%;
    background: transparent;
    border: none;
    cursor: pointer;
}

.sidebar-link i {
    width: 25px;
    margin-right: 12px;
    font-size: 1rem;
}

.sidebar-link:hover {
    background: rgba(255,255,255,0.2);
    color: white;
    transform: translateX(5px);
}

.sidebar-link.active {
    background: #FFFFFF;
    color: #0D5C63;
}

.sidebar-link.active i {
    color: #0D5C63;
}

.badge-notification {
    background: #F24B4B;
    color: white;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: auto;
}

/* Main Content */
.dashboard-main {
    flex: 1;
    margin-left: 280px;
    padding: 10px 40px;
    background: #F5F7FA;
    min-height: 100vh;
}

/* Top Bar */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
    padding-bottom: 20px;
    border-bottom: 2px solid #E2E8F0;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #0D5C63;
    margin: 0;
}

.page-title p {
    font-size: 0.95rem;
    color: #6B7280;
    margin-top: 8px;
}

/* Back Button */
.btn-back {
    background: #6B7280;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-back:hover {
    background: #4B5563;
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Alert Messages */
.alert-custom {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    animation: slideDown 0.3s ease;
}

.alert-success-custom {
    background: #D1FAE5;
    color: #059669;
    border-left: 4px solid #059669;
}

.alert-error-custom {
    background: #FEE2E2;
    color: #DC2626;
    border-left: 4px solid #DC2626;
}

.alert-error-custom ul {
    padding-left: 20px;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Card */
.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header-custom {
    padding: 22px 28px;
    border-bottom: 1px solid #E2E8F0;
    background: white;
}

.card-header-custom h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}

.card-body-custom {
    padding: 28px;
}

/* Form Layout */
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2D3748;
    font-size: 0.9rem;
}

.required {
    color: #DC2626;
}

.form-control-custom {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control-custom:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
}

textarea.form-control-custom {
    resize: vertical;
    font-family: inherit;
}

select.form-control-custom {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234A5568' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
}

/* Password Wrapper */
.password-wrapper {
    position: relative;
}

.password-wrapper .form-control-custom {
    padding-right: 45px;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6B7280;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #0D5C63;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.btn-submit {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
}

.btn-cancel {
    background: #F3F4F6;
    color: #4B5563;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-cancel:hover {
    background: #E5E7EB;
    color: #1F2937;
    text-decoration: none;
    transform: translateY(-2px);
}

/* Scrollbar */
.dashboard-sidebar::-webkit-scrollbar {
    width: 5px;
}

.dashboard-sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
}

.dashboard-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 5px;
}

/* Responsive */
@media (max-width: 992px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .dashboard-main {
        padding: 25px 30px;
    }
}

@media (max-width: 768px) {
    .dashboard-sidebar {
        transform: translateX(-100%);
        transition: 0.3s;
        width: 260px;
        min-width: 260px;
    }
    .dashboard-sidebar.show {
        transform: translateX(0);
    }
    .dashboard-main {
        margin-left: 0;
        padding: 20px;
    }
    .top-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-back {
        width: 100%;
        justify-content: center;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn-submit, .btn-cancel {
        width: 100%;
        justify-content: center;
    }
    .card-body-custom {
        padding: 20px;
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

function togglePasswordConfirm() {
    const passwordInput = document.getElementById('password_confirmation');
    const toggleIcon = document.getElementById('toggleIconConfirm');
    
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