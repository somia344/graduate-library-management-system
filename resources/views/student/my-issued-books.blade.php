@extends('layouts.dashboard')

@section('title', 'My Issued Books')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">My Issued Books</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">View all books you have borrowed from the library</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-book-reader me-2"></i>
                    Total: {{ $issuedBooks->total() }}
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
            <!-- Statistics Summary -->
<!-- Statistics Cards - Same as Student Dashboard -->
<div class="stats-grid">
    <!-- Currently Issued -->
    <div class="stat-card" style="background: linear-gradient(135deg, #0D5C63 0%, #1A7F88 100%);">
        <div class="stat-icon">
            <i class="fas fa-book-reader"></i>
        </div>
        <div class="stat-info">
            <h3>Currently Issued</h3>
            <h2 class="stat-number">{{ $issuedBooks->where('status', 'issued')->count() }}</h2>
        </div>
    </div>
    
    <!-- Returned Books -->
    <div class="stat-card" style="background: linear-gradient(135deg, #059669 0%, #34D399 100%);">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>Returned Books</h3>
            <h2 class="stat-number">{{ $issuedBooks->where('status', 'returned')->count() }}</h2>
        </div>
    </div>
    
    <!-- Overdue Books -->
    <div class="stat-card" style="background: linear-gradient(135deg, #E11D48 0%, #FB7185 100%);">
        <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
            <h3>Overdue Books</h3>
            <h2 class="stat-number">{{ $issuedBooks->filter(function($book) {
                return $book->status == 'issued' && now()->gt(\Carbon\Carbon::parse($book->return_date));
            })->count() }}</h2>
        </div>
    </div>
    
    <!-- Total Fine -->
    <div class="stat-card" style="background: linear-gradient(135deg, #c4871e 0%, #ecb605 100%);">
        <div class="stat-icon">
            <i class="fas fa-rupee-sign"></i>
        </div>
        <div class="stat-info">
            <h3>Total Fine</h3>
            <h2 class="stat-number">{{ $issuedBooks->sum('fine') }}</h2>
        </div>
    </div>
</div>
            <!-- Issued Books Table Card -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-book-reader me-2" style="color: #0D5C63;"></i> Book Issuance History</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Book Details</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Days Left/Overdue</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issuedBooks as $book)
                            @php
    $today = \Carbon\Carbon::now()->startOfDay();
    $dueDate = \Carbon\Carbon::parse($book->return_date)->startOfDay();
    $isOverdue = $today->gt($dueDate) && $book->status == 'issued';
    
    if (!$isOverdue && $book->status == 'issued') {
        $daysLeft = $today->diffInDays($dueDate);
        $daysOverdue = 0;
    } elseif ($isOverdue) {
        $daysOverdue = $dueDate->diffInDays($today);
        $daysLeft = 0;
    } else {
        $daysLeft = 0;
        $daysOverdue = 0;
    }
@endphp

                            <tr class="{{ $isOverdue ? 'overdue-row' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="book-info">
                                        <strong>{{ $book->book->title ?? 'N/A' }}</strong>
                                        <small>by {{ $book->book->author ?? 'N/A' }}</small>
                                        @if($book->book->category)
                                            <small class="category-badge">{{ $book->book->category }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($book->issue_date)->format('d M Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($book->return_date)->format('d M Y') }}
                                    @if($isOverdue)
                                        <span class="overdue-badge">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->status == 'returned')
                                        <span class="returned-text">Returned on {{ \Carbon\Carbon::parse($book->updated_at)->format('d M Y') }}</span>
                                    @elseif($isOverdue)
                                        <span class="days-overdue">{{ $daysOverdue }} days overdue</span>
                                    @else
                                        <span class="days-left">{{ $daysLeft }} days left</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->fine && $book->fine > 0)
                                        <span class="fine-badge">Rs. {{ number_format($book->fine, 2) }}</span>
                                    @else
                                        <span class="text-muted">No fine</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->status == 'returned')
                                        <span class="status-badge status-returned">
                                            <i class="fas fa-check-circle me-1"></i>Returned
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="status-badge status-overdue">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                        </span>
                                    @else
                                        <span class="status-badge status-issued">
                                            <i class="fas fa-book-open me-1"></i>Issued
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-book-open fa-4x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No books issued yet</p>
                                    <small class="text-muted">Visit the library to borrow books</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-wrapper">
                    {{ $issuedBooks->links() }}
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

/* /* Stats Grid  */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 40px;
}

.stat-card {
    border-radius: 20px;
    padding: 29px !important;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 60px !important;
    height: 70px !important;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 32px !important;
    color: white;
}

.stat-info h3 {
    font-size: 0.9rem !important;
    font-weight: 500;
    color: rgba(255,255,255,0.9);
    margin: 0 0 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2.2rem !important;
    font-weight: 700;
    color: white;
    margin: 0;
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
    padding: 16px 15px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}
.custom-table tbody tr:hover {
    background: #F8FAFC;
}

.overdue-row {
    background: rgba(220, 38, 38, 0.02);
    border-left: 3px solid #F24B4B;
}

/* Book Info */
.book-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.book-info strong {
    color: #2D3748;
    font-size: 0.95rem;
}

.book-info small {
    font-size: 0.75rem;
    color: white;
}

.category-badge {
    display: inline-block;
    background: #F24B4B;
    color: white;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 0.7rem;
    margin-top: 4px;
    width: fit-content;
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

/* Date Status */
.days-left {
    color: #059669;
    font-weight: 500;
    font-size: 0.85rem;
}

.days-overdue {
    color: #DC2626;
    font-weight: 600;
    font-size: 0.85rem;
}

.returned-text {
    color: #6B7280;
    font-size: 0.8rem;
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

.fine-badge {
    display: inline-block;
    background: #FEF3C7;
    color: #D97706;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

/* Pagination Wrapper */
.pagination-wrapper {
    padding: 20px 25px;
    border-top: 1px solid #E2E8F0;
    background: white;
    display: flex;
    justify-content: center;
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
    
    .stats-summary {
        gap: 15px;
    }
}

@media (max-width: 992px) {
    .stats-summary {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .custom-table thead th,
    .custom-table tbody td {
        padding: 12px 15px;
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
    .stats-summary {
        grid-template-columns: 1fr;
    }
    .custom-table {
        font-size: 0.8rem;
    }
    .book-info strong {
        font-size: 0.85rem;
    }
}
</style>

@endsection