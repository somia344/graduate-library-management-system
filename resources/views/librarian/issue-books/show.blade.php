@extends('layouts.dashboard')

@section('title', 'Issue Book Details')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Issue Book Details</h2>
                    <p class="text-muted mt-1">View complete book issuance information</p>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('librarian.issue-books.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
            </div>
            
            <div class="details-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-info-circle me-2" style="color: #0D5C63;"></i> Issuance Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="details-grid">
                        <!-- Student Name -->
                        <div class="detail-item">
                            <label>Student Name</label>
                            <p><strong>{{ $issueBook->student->full_name ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <!-- Roll No -->
                        <div class="detail-item">
                            <label>Roll No</label>
                            <p>{{ $issueBook->student->roll_no ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Department -->
                        <div class="detail-item">
                            <label>Department</label>
                            <p>{{ $issueBook->student->department ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Class -->
                        <div class="detail-item">
                            <label>Class / Semester</label>
                            <p>{{ $issueBook->student->class ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Book Title -->
                        <div class="detail-item">
                            <label>Book Title</label>
                            <p><strong>{{ $issueBook->book->title ?? 'N/A' }}</strong></p>
                        </div>
                        
                        <!-- Book Author -->
                        <div class="detail-item">
                            <label>Book Author</label>
                            <p>{{ $issueBook->book->author ?? 'N/A' }}</p>
                        </div>
                        
                        <!-- Issue Date -->
                        <div class="detail-item">
                            <label>Issue Date</label>
                            <p>{{ \Carbon\Carbon::parse($issueBook->issue_date)->format('d M Y') }}</p>
                        </div>
                        
                        <!-- Due Date -->
                        <div class="detail-item">
                            <label>Due Date</label>
                            <p>
                                {{ \Carbon\Carbon::parse($issueBook->return_date)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($issueBook->return_date)->isPast() && $issueBook->status == 'issued')
                                    <span class="badge-danger-small">Overdue</span>
                                @endif
                            </p>
                        </div>
                        
                        <!-- Status -->
                        <div class="detail-item">
                            <label>Status</label>
                            <p>
                                @if($issueBook->status == 'issued')
                                    <span class="status-badge status-issued">Issued</span>
                                @else
                                    <span class="status-badge status-returned">Returned</span>
                                @endif
                            </p>
                        </div>
                        
                        <!-- Fine -->
                        <div class="detail-item">
                            <label>Fine Amount</label>
                            <p>
                                @if($issueBook->fine && $issueBook->fine > 0)
                                    <span class="fine-badge">Rs. {{ number_format($issueBook->fine, 2) }}</span>
                                @else
                                    <span class="text-muted">No fine</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="form-actions">
                        @if($issueBook->status == 'issued')
                            <a href="{{ route('librarian.return-books.process', $issueBook->id) }}" class="btn-return" onclick="return confirm('Return this book?')">
                                <i class="fas fa-undo-alt me-2"></i> Return Book
                            </a>
                        @endif
                        <a href="{{ route('librarian.issue-books.edit', $issueBook->id) }}" class="btn-edit">
                            <i class="fas fa-edit me-2"></i> Edit Record
                        </a>
                    </div>
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
.action-buttons {
    display: flex;
    gap: 15px;
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
.details-card {
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
.details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}
.detail-item {
    padding: 5px 0;
}
.detail-item label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.detail-item p {
    font-size: 1rem;
    color: #2D3748;
    margin: 0;
}
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-issued {
    background: #FEF3C7;
    color: #D97706;
}
.status-returned {
    background: #D1FAE5;
    color: #059669;
}
.badge-danger-small {
    display: inline-block;
    background: #FEE2E2;
    color: #DC2626;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
    margin-left: 8px;
}
.fine-badge {
    display: inline-block;
    background: #FEF3C7;
    color: #D97706;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #E2E8F0;
}
.btn-return {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-return:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
}
.btn-edit {
    background: #F2A272;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}
.btn-edit:hover {
    background: #e08a5c;
    transform: translateY(-2px);
    color: white;
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
    .details-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn-return, .btn-edit, .btn-back {
        text-align: center;
    }
}
</style>
@endsection