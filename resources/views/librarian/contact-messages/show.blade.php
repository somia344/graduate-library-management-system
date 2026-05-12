@extends('layouts.dashboard')

@section('title', 'Message Details')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Message Details</h2>
                    <p class="text-muted mt-1" style="color: #6B7280;">View and reply to customer messages</p>
                </div>
                <a href="{{ route('librarian.contact-messages.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to Messages
                </a>
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
             <!-- Reply Form - Bottom -->
            <div class="reply-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-reply me-2" style="color: #0D5C63;"></i> Reply to Message</h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('librarian.contact-messages.reply', $message->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-edit me-2"></i>Your Reply <span class="required">*</span>
                            </label>
                            <textarea name="reply" class="form-control-custom" rows="6" required placeholder="Type your reply here..."></textarea>
                        </div>
                        
                        <div class="email-preview">
                            <div class="preview-header">
                                <i class="fas fa-envelope-open-text"></i> Email Preview
                            </div>
                            <div class="preview-content">
                                <div class="preview-row">
                                    <span class="preview-label">To:</span>
                                    <span class="preview-value">{{ $message->email }}</span>
                                </div>
                                <div class="preview-row">
                                    <span class="preview-label">Subject:</span>
                                    <span class="preview-value">Re: {{ $message->subject }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-send-reply">
                            <i class="fas fa-paper-plane me-2"></i>Send Reply
                        </button>
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
    padding: 30px 40px;
    background: #F5F7FA;
    min-height: 100vh;
    width: calc(100% - 280px);
}

/* Top Bar */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
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

/* Reply Card - Bottom */
.reply-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header-custom {
    padding: 18px 25px;
    border-bottom: 1px solid #E2E8F0;
    background: #F8FAFC;
}

.card-header-custom h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #1E293B;
    margin: 0;
}

.card-body-custom {
    padding: 25px;
}

/* Horizontal Info Grid */
.info-grid-horizontal {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.info-section-horizontal {
    flex: 1;
    min-width: 250px;
}

.message-section {
    flex: 1;
    min-width: 250px;
}

.info-row-detail {
    display: flex;
    padding: 8px 0;
    border-bottom: 1px solid #F1F5F9;
}

.info-row-detail:last-child {
    border-bottom: none;
}

.info-label-detail {
    width: 80px;
    font-weight: 600;
    color: #64748B;
    font-size: 0.8rem;
}

.info-label-detail i {
    width: 20px;
    margin-right: 8px;
    color: #0D5C63;
}

.info-value-detail {
    flex: 1;
    color: #1E293B;
    font-size: 0.85rem;
}

.info-value-detail small {
    font-size: 0.7rem;
    color: #94A3B8;
    margin-left: 8px;
}

.email-link {
    color: #0D5C63;
    text-decoration: none;
    font-weight: 500;
}

.email-link:hover {
    text-decoration: underline;
}

/* Message Section */
.message-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 10px;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.message-label i {
    margin-right: 8px;
    color: #0D5C63;
}

.message-text {
    background: #F8FAFC;
    padding: 15px;
    border-radius: 12px;
    line-height: 1.5;
    color: #1E293B;
    font-size: 0.9rem;
    border: 1px solid #E2E8F0;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.status-unread {
    background: #FEE2E2;
    color: #DC2626;
}

.status-read {
    background: #E0F2FE;
    color: #0284C7;
}

.status-replied {
    background: #D1FAE5;
    color: #059669;
}

/* Previous Reply */
.previous-reply-section {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #E2E8F0;
}

.reply-bubble {
    background: #F8FAFC;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #E2E8F0;
}

.reply-bubble-header {
    background: #E2E8F0;
    padding: 10px 15px;
    font-weight: 600;
    color: #1E293B;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 0.75rem;
}

.reply-bubble-header i {
    color: #0D5C63;
    margin-right: 6px;
}

.reply-bubble-header small {
    font-weight: normal;
    color: #64748B;
    font-size: 0.7rem;
}

.reply-bubble-content {
    padding: 15px;
    line-height: 1.5;
    color: #1E293B;
    font-size: 0.85rem;
}

/* Form */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1E293B;
    font-size: 0.8rem;
}

.required {
    color: #DC2626;
}

.form-control-custom {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control-custom:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
}

textarea.form-control-custom {
    resize: vertical;
}

/* Email Preview */
.email-preview {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #E2E8F0;
}

.preview-header {
    font-weight: 600;
    color: #0D5C63;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #E2E8F0;
    font-size: 0.8rem;
}

.preview-header i {
    margin-right: 6px;
}

.preview-row {
    display: flex;
    padding: 5px 0;
}

.preview-label {
    width: 50px;
    font-weight: 600;
    color: #64748B;
    font-size: 0.75rem;
}

.preview-value {
    flex: 1;
    color: #1E293B;
    font-size: 0.8rem;
}

/* Send Reply Button */
.btn-send-reply {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
}

.btn-send-reply:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
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
    .dashboard-main {
        padding: 25px 30px;
        width: 100%;
        margin-left: 0;
    }
    
    .dashboard-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .dashboard-sidebar.show {
        transform: translateX(0);
    }
    
    .info-grid-horizontal {
        flex-direction: column;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .dashboard-main {
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
        font-size: 1.5rem;
    }
    
    .info-row-detail {
        flex-direction: column;
    }
    
    .info-label-detail {
        width: auto;
        margin-bottom: 5px;
    }
    
    .reply-bubble-header {
        flex-direction: column;
        text-align: center;
    }
    
    .card-header-custom {
        padding: 15px 20px;
    }
    
    .card-body-custom {
        padding: 20px;
    }
    
    .message-text {
        padding: 12px;
        font-size: 0.85rem;
    }
}
</style>
@endsection