@extends('layouts.dashboard')

@section('title', 'Edit Issue Record')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Edit Issue Record</h2>
                    <p class="text-muted mt-1">Update book issuance information</p>
                </div>
                <a href="{{ route('librarian.issue-books.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
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
            
            <div class="form-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-edit me-2" style="color: #0D5C63;"></i> Edit Issuance Information</h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('librarian.issue-books.update', $issueBook->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Student Information (Read Only) -->
                        <div class="info-section">
                            <h6 class="section-title"><i class="fas fa-user-graduate me-2"></i> Student Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Student Name</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->student->full_name ?? 'N/A' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Roll No</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->student->roll_no ?? 'N/A' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->student->department ?? 'N/A' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Class / Semester</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->student->class ?? 'N/A' }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Book Information (Read Only) -->
                        <div class="info-section">
                            <h6 class="section-title"><i class="fas fa-book me-2"></i> Book Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Book Title</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->book->title ?? 'N/A' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Author</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->book->author ?? 'N/A' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" value="{{ $issueBook->book->category ?? 'N/A' }}" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Issue Details (Editable) -->
                        <div class="info-section">
                            <h6 class="section-title"><i class="fas fa-calendar-alt me-2"></i> Issue Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Issue Date *</label>
                                    <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $issueBook->issue_date) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Due Date / Return Date *</label>
                                    <input type="date" name="return_date" class="form-control" value="{{ old('return_date', $issueBook->return_date) }}" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="status" class="form-control" required>
                                        <option value="issued" {{ $issueBook->status == 'issued' ? 'selected' : '' }}>📖 Issued</option>
                                        <option value="returned" {{ $issueBook->status == 'returned' ? 'selected' : '' }}>✅ Returned</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fine Amount</label>
                                    <input type="text" class="form-control" value="Rs. {{ $issueBook->fine ?? 0 }}" disabled>
                                    <small class="text-muted">Fine will be calculated automatically on return</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i> Update Record
                            </button>
                            <a href="{{ route('librarian.issue-books.index') }}" class="btn-cancel">
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
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #E2E8F0;
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
.btn-back {
    background: #6B7280;
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-back:hover {
    background: #4B5563;
    transform: translateY(-2px);
    color: white;
}

/* Form Card */
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

/* Info Sections */
.info-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #E2E8F0;
}
.info-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #0D5C63;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #0D5C63;
    display: inline-block;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #2D3748;
    margin-bottom: 8px;
    display: block;
    font-size: 0.85rem;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    font-size: 14px;
    background: white;
}
.form-control:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 2px rgba(13,92,99,0.1);
}
.form-control[disabled] {
    background: #F8FAFC;
    color: #4A5568;
    cursor: not-allowed;
}

/* Alert */
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

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #E2E8F0;
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
    transition: all 0.3s ease;
}
.btn-cancel:hover {
    background: #CBD5E0;
    transform: translateY(-2px);
}

.text-muted {
    font-size: 12px;
    color: #6B7280;
    margin-top: 5px;
    display: block;
}

/* Responsive */
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
    .form-actions {
        flex-direction: column;
    }
    .btn-save, .btn-cancel, .btn-back {
        width: 100%;
        text-align: center;
    }
}
</style>
@endsection