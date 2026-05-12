@extends('layouts.dashboard')

@section('title', 'Edit Student')

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
            <div class="top-bar">
                <div class="page-title">
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Edit Student</h2>
                    <p class="text-muted mt-1">Update student information</p>
                </div>
        
            </div>
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            <div class="form-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-user-edit me-2" style="color: #0D5C63;"></i> Student Information</h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('librarian.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $student->full_name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Father Name *</label>
                                <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number *</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $student->phone_number) }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department *</label>
                                <select name="department" class="form-control" required>
                                    <option value="" disabled>Select Department</option>
                                    <option value="Computer Science" {{ ($student->department ?? old('department')) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="Information Technology" {{ ($student->department ?? old('department')) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                    <option value="Software Engineering" {{ ($student->department ?? old('department')) == 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                                    <option value="Mathematics" {{ ($student->department ?? old('department')) == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                    <option value="Physics" {{ ($student->department ?? old('department')) == 'Physics' ? 'selected' : '' }}>Physics</option>
                                    <option value="Chemistry" {{ ($student->department ?? old('department')) == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                                    <option value="Biology" {{ ($student->department ?? old('department')) == 'Biology' ? 'selected' : '' }}>Biology</option>
                                    <option value="English" {{ ($student->department ?? old('department')) == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Urdu" {{ ($student->department ?? old('department')) == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                                    <option value="Economics" {{ ($student->department ?? old('department')) == 'Economics' ? 'selected' : '' }}>Economics</option>
                                    <option value="Business Administration" {{ ($student->department ?? old('department')) == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                                    <option value="Commerce" {{ ($student->department ?? old('department')) == 'Commerce' ? 'selected' : '' }}>Commerce</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Class / Semester *</label>
                                <select name="class" class="form-control" required>
                                    <option value="" disabled>Select Class</option>
                                    <option value="1st Year" {{ $student->class == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ $student->class == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ $student->class == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ $student->class == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    <option value="Masters" {{ $student->class == 'Masters' ? 'selected' : '' }}>Masters</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Roll No *</label>
                                <input type="text" name="roll_no" class="form-control" value="{{ old('roll_no', $student->roll_no) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Registration No</label>
                                <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $student->registration_no ?? '') }}" placeholder="Enter registration number">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $student->address) }}</textarea>
                        </div>
             <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i> Update Student
                            </button>
                            <a href="{{ route('librarian.students.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    min-height: 100vh;
    background: #F5F7FA;
}
.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
}
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
}
.sidebar-header {
    padding: 0 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.sidebar-nav {
    padding: 0 20px;
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
}
.sidebar-link i {
    width: 25px;
    margin-right: 12px;
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
.dashboard-main {
    flex: 1;
    margin-left: 280px;
    padding: 30px 40px;
    background: #F5F7FA;
    min-height: 100vh;
}
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
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
.btn-back {
    background: #6B7280;
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
}
.btn-back:hover {
    background: #4B5563;
    transform: translateY(-2px);
    color: white;
}
.form-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
}
.card-header-custom {
    padding: 18px 25px;
    border-bottom: 1px solid #E2E8F0;
    background: white;
}
.card-header-custom h5 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}
.card-body-custom {
    padding: 25px;
}
.form-label {
    font-weight: 600;
    color: #2D3748;
    margin-bottom: 8px;
    display: block;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    font-size: 14px;
}
.form-control:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 2px rgba(13,92,99,0.1);
}
textarea.form-control {
    resize: vertical;
}
.text-muted-custom {
    font-size: 12px;
    color: #6B7280;
    margin-top: 5px;
    display: block;
}
.form-actions {
    margin-top: 25px;
    padding-top: 10px;
        

}
.btn-save {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 10px;
}
.btn-save:hover {
    background: #084C52;
    transform: translateY(-2px);
}
.btn-cancel {
    background: #E2E8F0;
    color: #4A5568;
    padding: 10px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s ease;
}
.btn-cancel:hover {
    background: #CBD5E0;
    transform: translateY(-2px);
}
.alert {
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}
.alert-danger {
    background: #FEF2F2;
    color: #DC2626;
    border-left: 4px solid #DC2626;
}
.alert-success {
    background: #D1FAE5;
    color: #059669;
    border-left: 4px solid #059669;
}
@media (max-width: 768px) {
    .dashboard-sidebar {
        transform: translateX(-100%);
        position: fixed;
    }
    .dashboard-main {
        margin-left: 0;
        padding: 20px;
    }
    .top-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    .btn-back {
        text-align: center;
    }
    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .btn-save, .btn-cancel {
        width: 100%;
        text-align: center;
        margin-right: 0;
    }
}
</style>
@endsection