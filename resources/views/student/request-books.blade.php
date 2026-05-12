@extends('layouts.dashboard')

@section('title', 'My Book Requests')

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
            <div class="top-bar">
                <div class="page-title">
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">My Book Requests</h2>
                    <!-- <p class="text-muted mt-1">Track your book requests and librarian responses</p> -->
                </div>
                <a href="{{ route('student.search-books') }}" class="btn-browse">
                    <i class="fas fa-search me-2"></i>Browse More Books
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if($requests->count() > 0)
            <div class="requests-card">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Book</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Librarian Response</th>
                                <th>Response Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $request->book->title ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">by {{ $request->book->author ?? 'N/A' }}</small>
                                </td>
                                <td>{{ date('d M Y', strtotime($request->created_at)) }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <span class="badge-pending">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @elseif($request->status == 'approved')
                                        <span class="badge-approved">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @else
                                        <span class="badge-rejected">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->admin_response)
                                        <div class="response-message">
                                            <!-- <i class="fas fa-comment-dots"></i> -->
                                            {{ $request->admin_response }}
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->updated_at && $request->status != 'pending')
                                        {{ date('d M Y', strtotime($request->updated_at)) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper">
                    {{ $requests->links() }}
                </div>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox fa-4x"></i>
                <h3>No Book Requests Yet</h3>
                <p>You haven't requested any books yet.</p>
                <a href="{{ route('student.search-books') }}" class="btn-browse-empty">
                    <i class="fas fa-search me-2"></i>Browse Books
                </a>
            </div>
            @endif
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

.btn-browse {
    background: #0D5C63;
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-browse:hover {
    background: #084C52;
    color: white;
}

/* Alert */
.alert {
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}
.alert-success {
    background: #D1FAE5;
    color: #059669;
    border-left: 4px solid #059669;
}

/* Requests Card */
.requests-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
}
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

/* Badges */
.badge-pending {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #FEF3C7;
    color: #f51606;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}
.badge-approved {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #D1FAE5;
    color: #059669;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}
.badge-rejected {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #FEE2E2;
    color: #DC2626;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Response Message */
.response-message {
    background: #F8FAFC;
    padding: 8px 12px;
    border-radius: 8px;
    max-width: 250px;
}
.response-message i {
    color: #0D5C63;
    margin-right: 6px;
}
.text-muted {
    color: #A0AEC0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
}
.empty-state i {
    color: #A0AEC0;
    margin-bottom: 15px;
}
.empty-state h3 {
    color: #2D3748;
    margin-bottom: 10px;
}
.btn-browse-empty {
    display: inline-block;
    margin-top: 15px;
    background: #0D5C63;
    color: white;
    padding: 10px 25px;
    border-radius: 10px;
    text-decoration: none;
}

/* Pagination */
.pagination-wrapper {
    padding: 20px;
    text-align: center;
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
    .custom-table thead th,
    .custom-table tbody td {
        padding: 10px 12px;
        font-size: 0.75rem;
    }
    .response-message {
        max-width: 150px;
        font-size: 0.7rem;
    }
}
</style>
@endsection