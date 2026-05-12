@extends('layouts.dashboard')

@section('title', 'Contact Messages')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Contact Messages</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">View and manage customer inquiries</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-envelope me-2"></i>
                    Total: {{ $messages->total() }}
                    @if($unreadCount = \App\Models\ContactMessage::where('status', 'unread')->count())
                        <span class="unread-count">{{ $unreadCount }} Unread</span>
                    @endif
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
            
            <!-- Messages Table Card -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-envelope me-2" style="color: #0D5C63;"></i> All Contact Messages</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                            <tr class="{{ $message->status == 'unread' ? 'unread-row' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="sender-info">
                                        <strong>{{ $message->name }}</strong>
                                        <small>{{ $message->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="subject-info">
                                        <strong>{{ Str::limit($message->subject, 40) }}</strong>
                                        <small class="message-preview">{{ Str::limit($message->message, 50) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info" style="white-space: nowrap;">
                                  {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y') }}
                                </div>
                                </td>
                                <td>
                                    @if($message->status == 'unread')
                                        <span class="status-badge status-unread">
                                            <i class="fas fa-envelope me-1"></i>Unread
                                        </span>
                                    @elseif($message->status == 'read')
                                        <span class="status-badge status-read">
                                            <i class="fas fa-envelope-open me-1"></i>Read
                                        </span>
                                    @else
                                        <span class="status-badge status-replied">
                                            <i class="fas fa-reply-all me-1"></i>Replied
                                        </span>
                                    @endif
                                 </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('librarian.contact-messages.show', $message->id) }}" class="action-btn view-btn" title="View & Reply">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('librarian.contact-messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this message?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                 </td>
                             </tr>
                            @empty
                             <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No messages found</p>
                                    <small class="text-muted">When users send messages, they will appear here</small>
                                </td>
                             </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-wrapper">
                    {{ $messages->links() }}
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

/* Main Content - Increased Size */
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
.stats-badge {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.unread-count {
    background: #F24B4B;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
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

/* Data Card */
.data-card {
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

/* Custom Table */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    
}

.custom-table thead th {
    background: #176d74;
    padding: 18px 20px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #f1f3f7;
    border-bottom: 2px solid #E2E8F0;
    text-align: left;
}

.custom-table tbody td {
    padding: 16px 42px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}

.custom-table tbody tr:hover {
    background: #F8FAFC;
}

.unread-row {
    background: rgba(13, 92, 99, 0.02);
    border-left: 3px solid #0D5C63;
}

/* Sender Info */
.sender-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sender-info strong {
    color: #2D3748;
    font-size: 0.95rem;
}

.sender-info small {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Subject Info */
.subject-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.subject-info strong {
    color: #2D3748;
    font-size: 0.9rem;
}

.message-preview {
    font-size: 0.75rem;
    color: #6B7280;
    font-weight: normal;
}

/* Date Info */
.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    font-size: 0.85rem;
}

.date-info small {
    font-size: 0.7rem;
    color: #6B7280;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
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

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.view-btn {
    background: #E0F2FE;
    color: #0284C7;
}

.view-btn:hover {
    background: #0284C7;
    color: white;
    transform: translateY(-2px);
}

.delete-btn {
    background: #FEE2E2;
    color: #DC2626;
}

.delete-btn:hover {
    background: #DC2626;
    color: white;
    transform: translateY(-2px);
}

/* Pagination Wrapper */
.pagination-wrapper {
    padding: 20px 25px;
    border-top: 1px solid #E2E8F0;
    background: white;
    display: flex;
    justify-content: center;
}

/* Pagination Styling */
.pagination-wrapper nav {
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 5px;
}

.pagination .page-item .page-link {
    border-radius: 8px;
    color: #4A5568;
    border: 1px solid #E2E8F0;
    padding: 8px 12px;
}

.pagination .page-item.active .page-link {
    background: #0D5C63;
    border-color: #0D5C63;
    color: white;
}

.pagination .page-item .page-link:hover {
    background: rgba(13, 92, 99, 0.1);
    color: #0D5C63;
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
}

@media (max-width: 992px) {
    .custom-table thead th,
    .custom-table tbody td {
        padding: 12px 15px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
    
    .action-btn {
        width: 100%;
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
    .stats-badge {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .custom-table {
        font-size: 0.85rem;
    }
    .sender-info strong,
    .subject-info strong {
        font-size: 0.85rem;
    }
    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
    .action-btn {
        width: 100%;
    }
}
</style>
@endsection