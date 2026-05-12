@extends('layouts.dashboard')

@section('title', 'Student Dashboard')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Welcome back, {{ $student->full_name }}!</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Here's what's happening with your library account</p> -->
                </div>
                <div class="top-bar-right">
                    <!-- Notification Bell -->
                    <div class="notification-bell" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span class="bell-badge">{{ $unreadCount }}</span>
                        @endif
                    </div>
                    
                    <form action="{{ route('student.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout-top">Logout</button>
                    </form>
                </div>
            </div>
            
            <!-- Notification Dropdown -->
            <div id="notificationDropdown" class="notification-dropdown" style="display: none;">
                <div class="notification-header">
                    <h5>Notifications</h5>
                    @if($unreadCount > 0)
                        <a href="#" onclick="markAllAsRead()">Mark all as read</a>
                    @endif
                </div>
                <div class="notification-list">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}">
                            <div class="notification-icon">
                                <i class="fas {{ $notification->type == 'book_available' ? 'fa-book' : 'fa-bell' }}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">{{ $notification->title }}</div>
                                <div class="notification-message">{{ $notification->message }}</div>
                                <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="notification-empty">
                            <i class="fas fa-bell-slash fa-2x mb-2" style="color: #A0AEC0;"></i>
                            <p>No new notifications</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card" style="background: linear-gradient(135deg, #0D5C63 0%, #1A7F88 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Books Issued</h3>
                        <h2 class="stat-number">{{ $bookIssued ?? 0 }}</h2>
                    </div>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #c4871e 0%, #ecb605 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pending Requests</h3>
                        <h2 class="stat-number">{{ $pendingRequests ?? 0 }}</h2>
                    </div>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #E11D48 0%, #FB7185 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Overdue Books</h3>
                        <h2 class="stat-number">{{ $overdueBooks ?? 0 }}</h2>
                    </div>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #059669 0%, #34D399 100%);">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Available Books</h3>
                        <h2 class="stat-number">{{ $availableBooks ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            
            <!-- Recent Issued Books -->
            <div class="recent-activities-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-history me-2" style="color: #0D5C63;"></i> Recently Issued Books</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Book Name</th>
                                <th>Author</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentIssued ?? [] as $item)
                            @php
                                $returnDate = \Carbon\Carbon::parse($item->return_date);
                                $isOverdue = $item->status == 'issued' && now()->gt($returnDate);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $item->book->title ?? 'N/A' }}</strong></td>
                                <td>{{ $item->book->author ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->issue_date)->format('d M Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}
                                    @if($isOverdue)
                                        <span class="overdue-badge">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'issued')
                                        @if($isOverdue)
                                            <span class="status-badge status-overdue">Overdue</span>
                                        @else
                                            <span class="status-badge status-issued">Issued</span>
                                        @endif
                                    @else
                                        <span class="status-badge status-returned">Returned</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-book-open fa-2x mb-2" style="color: #A0AEC0;"></i>
                                    <p class="mb-0">No books issued yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
//* Dashboard Container */
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

.top-bar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Logout Button */
.btn-logout-top {
    background: #F24B4B;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-logout-top:hover {
    background: #D93636;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(242, 75, 75, 0.3);
}

/* Notification Bell */
.notification-bell {
    position: relative;
    cursor: pointer;
    font-size: 1.3rem;
    color: #0D5C63;
    background: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.notification-bell:hover {
    background: #0D5C63;
    color: white;
    transform: translateY(-2px);
}

.bell-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #F24B4B;
    color: white;
    font-size: 0.6rem;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: 600;
}

/* Notification Dropdown */
.notification-dropdown {
    position: absolute;
    top: 80px;
    right: 20px;
    width: 380px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    z-index: 1000;
    max-height: 450px;
    overflow-y: auto;
}

.notification-header {
    padding: 15px 20px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #F8FAFC;
    border-radius: 16px 16px 0 0;
}

.notification-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #2D3748;
}

.notification-header a {
    font-size: 0.75rem;
    color: #0D5C63;
    text-decoration: none;
    font-weight: 500;
}

.notification-header a:hover {
    text-decoration: underline;
}

.notification-list {
    max-height: 380px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    gap: 12px;
    padding: 15px 20px;
    border-bottom: 1px solid #E2E8F0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.notification-item:hover {
    background: #F8FAFC;
}

.notification-item.unread {
    background: #E0F2FE;
}

.notification-icon {
    flex-shrink: 0;
}

.notification-icon i {
    font-size: 1.2rem;
    color: #0D5C63;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    font-size: 0.85rem;
    color: #2D3748;
}

.notification-message {
    font-size: 0.75rem;
    color: #6B7280;
    margin-top: 3px;
    line-height: 1.4;
}

.notification-time {
    font-size: 0.65rem;
    color: #A0AEC0;
    margin-top: 5px;
}

.notification-empty {
    padding: 40px 20px;
    text-align: center;
    color: #94A3B8;
}

.notification-empty p {
    margin: 0;
    font-size: 0.85rem;
}

/* Statistics Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 35px;
}

.stat-card {
    border-radius: 20px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 70px;
    height: 70px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 32px;
    color: white;
}

.stat-info h3 {
    font-size: 0.85rem;
    font-weight: 500;
    color: rgba(255,255,255,0.9);
    margin: 0 0 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: white;
    margin: 0;
}

/* Recent Activities Card */
.recent-activities-card {
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

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
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

.status-overdue {
    background: #FEE2E2;
    color: #DC2626;
}

.overdue-badge {
    display: inline-block;
    background: #FEE2E2;
    color: #DC2626;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
    margin-left: 8px;
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
    
    .stats-grid {
        gap: 20px;
    }
}

@media (max-width: 992px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
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
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .top-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .top-bar-right {
        justify-content: flex-end;
    }
    .btn-logout-top {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .custom-table thead th,
    .custom-table tbody td {
        padding: 12px 15px;
    }
    .notification-dropdown {
        width: calc(100% - 40px);
        right: 20px;
        left: 20px;
    }
}
</style>

<script>
// Toggle notification dropdown
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
    } else {
        dropdown.style.display = 'none';
    }
}

// Mark all as read
function markAllAsRead() {
    fetch('/student/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if(data.success) {
              location.reload();
          }
      });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const bell = document.querySelector('.notification-bell');
    const dropdown = document.getElementById('notificationDropdown');
    if (bell && dropdown && dropdown.style.display === 'block') {
        if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    }
});
</script>
@endsection