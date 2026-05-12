@extends('layouts.dashboard')

@section('title', 'My Messages')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">My Messages</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">View all your messages and replies from the librarian</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-envelope me-2"></i>
                    Total: {{ $messages->total() }}
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert-custom alert-success-custom">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            <!-- Messages Table Card -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-envelope me-2" style="color: #0D5C63;"></i> Message History</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Sent Date</th>
                                <th>Reply</th>
                                <th>Reply Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                            <tr class="{{ $message->status == 'unread' ? 'unread-row' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ Str::limit($message->subject, 40) }}</strong>
                                </td>
                                <td>
                                    <div class="message-preview">
                                        {{ Str::limit($message->message, 70) }}
                                    </div>
                                </td>
                                <td>
                                   <div class="sent-date">{{ \Carbon\Carbon::parse($message->created_at)->format('d M Y') }}</div>
{{-- <div class="sent-time">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</div>--}}                                </td>
                                <td>
                                    @if($message->admin_reply)
                                        <div class="reply-content">
                                            {{ Str::limit($message->admin_reply, 70) }}
                                        </div>
                                    @else
                                        <span class="no-reply">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($message->admin_reply)
                                        <div class="reply-date">{{ \Carbon\Carbon::parse($message->updated_at)->format('d M Y') }}</div>
{{-- <div class="reply-time">{{ \Carbon\Carbon::parse($message->updated_at)->format('h:i A') }}</div> --}}
                                    @else
                                        <span class="no-reply">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($message->status == 'unread')
                                        <span class="status-badge status-unread">Unread</span>
                                    @elseif($message->status == 'read')
                                        <span class="status-badge status-read">Read</span>
                                    @else
                                        <span class="status-badge status-replied">Replied</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No messages found</p>
                                    <small class="text-muted">When you contact the librarian, your messages will appear here</small>
                                    <div class="mt-3">
                                        <a href="{{ route('contact') }}" class="btn-contact-now">
                                            <i class="fas fa-paper-plane me-2"></i>Send New Message
                                        </a>
                                    </div>
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
            
            <!-- Quick Contact Card -->
            <div class="quick-contact-card">
                <div class="quick-contact-content">
                    <div class="quick-contact-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="quick-contact-text">
                        <h6>Need Help?</h6>
                        <p>Have a question or need assistance? Contact the librarian directly.</p>
                    </div>
                    <a href="{{ route('contact') }}" class="quick-contact-btn">
                        <i class="fas fa-paper-plane me-2"></i>Send New Message
                    </a>
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

.stats-badge {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
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

/* Custom Table */
.custom-table {
    width: 100%;
    border-collapse: collapse;
     min-width: 900px;
   /* table-layout: fixed; */
    
}

.custom-table thead th {
    background: #176d74;
    padding: 10px 8px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #f1f3f7;
    border-bottom: 2px solid #E2E8F0;
    text-align: left;
    text-align:center;


}

.custom-table tbody td {
    padding: 16px 11px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
    word-wrap: break-word;  /* ← YEH ADD KARO */
    /* word-break: break-word;   */
    white-space: normal;
      vertical-align: top;
}


.custom-table tbody tr:hover {
    background: #F8FAFC;
}

.unread-row {
    background: rgba(13, 92, 99, 0.02);
    border-left: 3px solid #0D5C63;
}

/* Date & Time Separate Styles */
.sent-date, .reply-date {
    font-weight: 500;
    color: #2D3748;
    font-size: 0.85rem;
}

.sent-time, .reply-time {
    font-size: 0.7rem;
    color: #6B7280;
    margin-top: 2px;
}

/* Message Preview */
.message-preview {
    color: #4A5568;
    line-height: 1.5;
    font-size: 0.85rem;
    max-width: 250px;
}

/* Reply Content */
.reply-content {
    color: #059669;
    line-height: 1.5;
    font-size: 0.85rem;
    background: #ECFDF5;
    padding: 8px 12px;
    border-radius: 10px;
    display: inline-block;
    max-width: 250px;
}

.no-reply {
    color: #A0AEC0;
    font-size: 0.85rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
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

/* Quick Contact Card */
.quick-contact-card {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    border-radius: 20px;
    padding: 25px 30px;
    color: white;
}

.quick-contact-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.quick-contact-icon {
    background: rgba(255,255,255,0.2);
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-contact-icon i {
    font-size: 28px;
}

.quick-contact-text {
    flex: 1;
}

.quick-contact-text h6 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 5px;
}

.quick-contact-text p {
    font-size: 0.85rem;
    margin: 0;
    opacity: 0.9;
}

.quick-contact-btn {
    background: white;
    color: #0D5C63;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.quick-contact-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    color: #0D5C63;
}

.btn-contact-now {
    display: inline-block;
    background: #0D5C63;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-contact-now:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
}

/* Pagination Wrapper */
.pagination-wrapper {
    padding: 20px 25px;
    border-top: 1px solid #E2E8F0;
    background: white;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    gap: 8px;
    margin: 0;
}

.pagination .page-item .page-link {
    background: white;
    border: 1px solid #E2E8F0;
    color: #4A5568;
    padding: 8px 14px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.pagination .page-item.active .page-link {
    background: #0D5C63;
    border-color: #0D5C63;
    color: white;
}

.pagination .page-item .page-link:hover {
    background: #0D5C63;
    color: white;
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
    
    .quick-contact-content {
        flex-direction: column;
        text-align: center;
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
        justify-content: center;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .custom-table {
        min-width: 700px;
    }
    .quick-contact-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection

