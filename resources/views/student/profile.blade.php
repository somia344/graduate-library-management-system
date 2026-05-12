@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <div class="dashboard-sidebar">
    <div class="sidebar-header">
        <div class="text-center">
            <i class="fas fa-user-graduate fa-3x" style="color: #FFFFFF;"></i>
            <h5 class="mt-2 fw-bold" style="color: white;">Student Panel</h5>
            <p class="small" style="color: rgba(255,255,255,0.8);">Graduate Library</p>
        </div>
    </div>
    <hr style="border-color: rgba(255,255,255,0.1);">
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a class="sidebar-link {{ request()->routeIs('student.dashboard*') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        
        <!-- My Issued Books -->
        <a class="sidebar-link {{ request()->routeIs('student.my-issued-books*') ? 'active' : '' }}" href="{{ route('student.my-issued-books') }}">
            <i class="fas fa-book-reader"></i> My Issued Books
        </a>
            <!-- Search Books - New Books Notification -->
<a class="sidebar-link {{ request()->routeIs('student.search-books*') ? 'active' : '' }}" href="{{ route('student.search-books') }}">
    <i class="fas fa-search"></i> Search Books
    @php
        $lastVisit = session('last_search_visit', now());
        // Sirf last visit ke BAAD add hui books count karo
        $newBooksCount = \App\Models\Book::where('created_at', '>', $lastVisit)->count();
    @endphp
    @if($newBooksCount > 0)
        <span class="badge-notification">📚 {{ $newBooksCount }}</span>
    @endif
</a>
        
        <!-- Book Requests - Response Notification -->
        <a class="sidebar-link {{ request()->routeIs('student.request-books.index*') ? 'active' : '' }}" href="{{ route('student.request-books.index') }}">
            <i class="fas fa-question-circle"></i> Book Requests
            @php
                $responseCount = \App\Models\BookRequest::where('student_id', auth()->guard('student')->id())
                    ->whereIn('status', ['approved', 'rejected'])
                    ->where('is_seen', 0)
                    ->count();
            @endphp
            @if($responseCount > 0)
                <span class="badge-notification">{{ $responseCount }}</span>
            @endif
        </a>
        
        <!-- Messages - Reply Notification -->
        <a class="sidebar-link {{ request()->routeIs('student.contact-reply*') ? 'active' : '' }}" href="{{ route('student.contact-reply') }}">
            <i class="fas fa-envelope"></i> Messages
            @php
                $student = auth()->guard('student')->user();
                $messageCount = \App\Models\ContactMessage::where('email', $student->email)
                    ->where('status', 'replied')
                    ->count();
            @endphp
            @if($messageCount > 0)
                <span class="badge-notification">{{ $messageCount }}</span>
            @endif
        </a>

        <!-- My Reservations - Book Available Notification -->
        <a class="sidebar-link {{ request()->routeIs('student.my-reservations*') ? 'active' : '' }}" href="{{ route('student.my-reservations') }}">
            <i class="fas fa-bookmark"></i> My Reservations
            @php
                $availableCount = \App\Models\BookReservation::where('student_id', auth()->guard('student')->id())
                    ->where('status', 'active')
                    ->where('notified', 0)
                    ->count();
            @endphp
            @if($availableCount > 0)
                <span class="badge-notification">{{ $availableCount }}</span>
            @endif
        </a>
        
        <!-- My Profile -->
        <a class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}" href="{{ route('student.profile') }}">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
    </nav>
</div>
        
        <!-- Main Content -->
        <div class="dashboard-main">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">My Profile</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Manage your account information</p> -->
                </div>
                <div class="top-buttons">
                    <div class="student-badge">
                        <i class="fas fa-id-card me-2"></i>
                        {{ $student->roll_no ?? 'Student ID' }}
                    </div>
                    <button type="button" class="btn-change-password-top" onclick="togglePasswordForm()">
                        <i class="fas fa-key me-2"></i>Change Password
                    </button>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert-custom alert-error-custom">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif
            
            <!-- Change Password Modal -->
            <div id="changePasswordModal" class="password-modal" style="display: none;">
                <div class="password-modal-content">
                    <div class="password-modal-header">
                        <h5><i class="fas fa-key me-2" style="color: #0D5C63;"></i> Change Password</h5>
                        <button type="button" class="modal-close" onclick="togglePasswordForm()">&times;</button>
                    </div>
                    <div class="password-modal-body">
                        <form action="{{ route('student.profile.change-password') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label class="form-label">Current Password <span class="required">*</span></label>
                                <div class="password-wrapper">
                                    <input type="password" name="current_password" class="form-control-custom" id="current_password" required>
                                    <span class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="far fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">New Password <span class="required">*</span></label>
                                <div class="password-wrapper">
                                    <input type="password" name="new_password" class="form-control-custom" id="new_password" required>
                                    <span class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="far fa-eye-slash"></i>
                                    </span>
                                </div>
                                <div class="password-requirements">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Password must contain at least 8 characters, 1 uppercase letter, 1 number, and 1 special character
                                    </small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Confirm New Password <span class="required">*</span></label>
                                <div class="password-wrapper">
                                    <input type="password" name="new_password_confirmation" class="form-control-custom" id="confirm_password" required>
                                    <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                        <i class="far fa-eye-slash"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-actions-modal">
                                <button type="button" class="btn-cancel-modal" onclick="togglePasswordForm()">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn-submit-modal">
                                    <i class="fas fa-save me-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Profile Information Card (Only Editable Fields) -->
            <div class="profile-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-user-edit me-2" style="color: #0D5C63;"></i> Edit Profile Information</h5>
                    <p class="text-muted mt-1" style="font-size: 0.8rem;">You can update your personal information below</p>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('student.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Row 1: Full Name + Father Name -->
                        <div class="form-row-horizontal">
                            <div class="form-group-horizontal">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="full_name" class="form-control-custom" value="{{ $student->full_name }}" required>
                            </div>
                            
                            <div class="form-group-horizontal">
                                <label class="form-label">Father Name <span class="required">*</span></label>
                                <input type="text" name="father_name" class="form-control-custom" value="{{ $student->father_name }}" required>
                            </div>
                        </div>
                        
                       <!-- Row 2: Phone Number + Department -->
<div class="form-row-horizontal">
    <div class="form-group-horizontal">
        <label class="form-label">Phone Number <span class="required">*</span></label>
        <input type="text" name="phone_number" class="form-control-custom" value="{{ $student->phone_number }}" required>
    </div>
    
    <div class="form-group-horizontal">
        <label class="form-label">Department</label>
        <select name="department" class="form-control-custom">
            <option value="">Select Department</option>
            <option value="BS (Information Technology)" {{ $student->department == 'BS (Information Technology)' ? 'selected' : '' }}>BS (Information Technology)</option>
            <option value="BS (Computer Science)" {{ $student->department == 'BS (Computer Science)' ? 'selected' : '' }}>BS (Computer Science)</option>
            <option value="BS (Mathematics)" {{ $student->department == 'BS (Mathematics)' ? 'selected' : '' }}>BS (Mathematics)</option>
            <option value="BS (English)" {{ $student->department == 'BS (English)' ? 'selected' : '' }}>BS (English)</option>
            <option value="BS (Education)" {{ $student->department == 'BS (Education)' ? 'selected' : '' }}>BS (Education)</option>
            <option value="BS (Islamiyat)" {{ $student->department == 'BS (Islamiyat)' ? 'selected' : '' }}>BS (Islamiyat)</option>
            <option value="BS (Physics)" {{ $student->department == 'BS (Physics)' ? 'selected' : '' }}>BS (Physics)</option>
            <option value="BS (Chemistry)" {{ $student->department == 'BS (Chemistry)' ? 'selected' : '' }}>BS (Chemistry)</option>
            <option value="BS (Biology)" {{ $student->department == 'BS (Biology)' ? 'selected' : '' }}>BS (Biology)</option>
            <option value="BS (Economics)" {{ $student->department == 'BS (Economics)' ? 'selected' : '' }}>BS (Economics)</option>
            <option value="BS (Political Science)" {{ $student->department == 'BS (Political Science)' ? 'selected' : '' }}>BS (Political Science)</option>
            <option value="BS (Psychology)" {{ $student->department == 'BS (Psychology)' ? 'selected' : '' }}>BS (Psychology)</option>
            <option value="BS (Sociology)" {{ $student->department == 'BS (Sociology)' ? 'selected' : '' }}>BS (Sociology)</option>
            <option value="BS (Commerce)" {{ $student->department == 'BS (Commerce)' ? 'selected' : '' }}>BS (Commerce)</option>
            <option value="BS (Statistics)" {{ $student->department == 'BS (Statistics)' ? 'selected' : '' }}>BS (Statistics)</option>
            <option value="BS (Urdu)" {{ $student->department == 'BS (Urdu)' ? 'selected' : '' }}>BS (Urdu)</option>
            <option value="BS (Zoology)" {{ $student->department == 'BS (Zoology)' ? 'selected' : '' }}>BS (Zoology)</option>
            <option value="BS (Botany)" {{ $student->department == 'BS (Botany)' ? 'selected' : '' }}>BS (Botany)</option>
            <option value="BS (Pak Studies)" {{ $student->department == 'BS (Pak Studies)' ? 'selected' : '' }}>BS (Pak Studies)</option>
        </select>
    </div>
</div>

<!-- Row 3: Registration No + Address -->
<div class="form-row-horizontal">
    <div class="form-group-horizontal">
        <label class="form-label">Registration No</label>
        <input type="text" name="registration_no" class="form-control-custom" value="{{ $student->registration_no }}">
    </div>
    
    <div class="form-group-horizontal">
        <!-- Empty or can add something else -->
    </div>
</div>

<!-- Row 4: Address (Full Width) -->
<div class="form-group-full">
    <label class="form-label">Address <span class="required">*</span></label>
    <textarea name="address" class="form-control-custom" rows="3" required>{{ $student->address }}</textarea>
</div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Read Only Information Card -->
            <div class="info-card-full">
                <div class="card-header-custom">
                    <h5><i class="fas fa-info-circle me-2" style="color: #0D5C63;"></i> Account Information (Read Only)</h5>
                </div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Email Address</span>
                        <span class="info-value">{{ $student->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Class / Semester</span>
                        <span class="info-value">{{ $student->class }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Roll Number</span>
                        <span class="info-value">{{ $student->roll_no }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department</span>
                        <span class="info-value">{{ $student->department ?? 'Not specified' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Registration No</span>
                        <span class="info-value">{{ $student->registration_no ?? 'Not specified' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($student->created_at)->format('d F, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Status</span>
                        <span class="info-value"><span class="status-active">Active</span></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Books Issued</span>
                        <span class="info-value">{{ $student->issuedBooks->count() ?? 0 }}</span>
                    </div>
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
    padding: 18px 40px;
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
    font-size: 1.8rem;
    font-weight: 700;
    color: #0D5C63;
    margin: 0;
}

.page-title p {
    font-size: 0.95rem;
    color: #6B7280;
    margin-top: 8px;
}

.top-buttons {
    display: flex;
    align-items: center;
    gap: 15px;
}

.student-badge {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.btn-change-password-top {
    background: #F24B4B;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.btn-change-password-top:hover {
    background: #D93636;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(242, 162, 114, 0.3);
}

/* Alert Messages */
.alert-custom {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    animation: slideDown 0.3s ease;
    display: flex;
    align-items: center;
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

/* Password Modal */
.password-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.password-modal-content {
    background: white;
    border-radius: 20px;
    width: 500px;
    max-width: 90%;
    animation: modalFadeIn 0.3s ease;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.password-modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.password-modal-header h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.8rem;
    cursor: pointer;
    color: #6B7280;
    transition: color 0.3s ease;
}

.modal-close:hover {
    color: #DC2626;
}

.password-modal-body {
    padding: 25px;
}

.form-actions-modal {
    display: flex;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #E2E8F0;
}

.btn-submit-modal {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    flex: 1;
}

.btn-submit-modal:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
}

.btn-cancel-modal {
    background: #F3F4F6;
    color: #4A5568;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    flex: 1;
}

.btn-cancel-modal:hover {
    background: #E5E7EB;
    transform: translateY(-2px);
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 25px;
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

/* Horizontal Form Layout */
.form-row-horizontal {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-group-horizontal {
    margin-bottom: 0;
}

.form-group-full {
    margin-bottom: 20px;
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

.password-requirements {
    margin-top: 8px;
}

/* Form Actions */
.form-actions {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #E2E8F0;
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
    justify-content: center;
    width: 100%;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
}

/* Read Only Info Card */
.info-card-full {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 25px 28px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #2D3748;
}

.status-active {
    background: #D1FAE5;
    color: #059669;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    display: inline-block;
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
@media (max-width: 1200px) {
    .dashboard-main {
        padding: 25px 30px;
    }
    
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .form-row-horizontal {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .top-buttons {
        flex-wrap: wrap;
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
    .top-buttons {
        justify-content: space-between;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    .card-header-custom {
        padding: 18px 20px;
    }
    .card-body-custom {
        padding: 20px;
    }
    .password-modal-content {
        width: 95%;
    }
}
</style>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = passwordInput.parentElement.querySelector('.password-toggle i');
    
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

function togglePasswordForm() {
    const modal = document.getElementById('changePasswordModal');
    if (modal.style.display === 'none') {
        modal.style.display = 'flex';
    } else {
        modal.style.display = 'none';
    }
}
</script>
@endsection