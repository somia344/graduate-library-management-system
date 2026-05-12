@extends('layouts.dashboard')

@section('title', 'My Reservations')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">My Reservations</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Track your reserved books and waitlist position</p> -->
                </div>
                <a href="{{ route('student.search-books') }}" class="btn-browse">
                    <i class="fas fa-search me-2"></i>Browse Books
                </a>
            </div>
            
            <!-- Reservations Table -->
            <div class="reservation-card">
                <div class="reservation-card-header">
                    <h5><i class="fas fa-bookmark me-2" style="color: #0D5C63;"></i> My Reservations List</h5>
                </div>
                <div class="table-responsive">
                    <table class="reservation-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Reserved On</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $index => $reservation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="book-info-cell">
                                        <strong>{{ Str::limit($reservation->book->title, 50) }}</strong>
                                        @if($reservation->book->isbn)
                                            <small>ISBN: {{ $reservation->book->isbn }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $reservation->book->author }}</td>
                                <td>
                                    <div class="date-info">
                                        {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    @if($reservation->expiry_date)
                                        <div class="date-info">
                                            {{ \Carbon\Carbon::parse($reservation->expiry_date)->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->status == 'pending')
                                        <span class="res-badge res-badge-pending">
                                            <i class="fas fa-clock me-1"></i> Waitlist #{{ $reservation->position }}
                                        </span>
                                    @elseif($reservation->status == 'active')
                                        <span class="res-badge res-badge-active">
                                            <i class="fas fa-check-circle me-1"></i> Available
                                        </span>
                                    @elseif($reservation->status == 'expired')
                                        <span class="res-badge res-badge-expired">
                                            <i class="fas fa-hourglass-end me-1"></i> Expired
                                        </span>
                                    @elseif($reservation->status == 'cancelled')
                                        <span class="res-badge res-badge-cancelled">
                                            <i class="fas fa-times-circle me-1"></i> Cancelled
                                        </span>
                                    @elseif($reservation->status == 'notified')
                                        <span class="res-badge res-badge-notified">
                                            <i class="fas fa-bell me-1"></i> Ready for Pickup
                                        </span>
                                    @elseif($reservation->status == 'issued')
                                        <span class="res-badge res-badge-issued">
                                            <i class="fas fa-book me-1"></i> Issued
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->status == 'pending')
                                        <button class="btn-cancel-reservation" onclick="cancelReservation({{ $reservation->id }})">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-bookmark fa-3x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No reservations found</p>
                                    <a href="{{ route('student.search-books') }}" class="btn-browse-empty mt-3">
                                        <i class="fas fa-search me-2"></i>Browse Books
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($reservations->hasPages())
                    <div class="reservation-pagination">
                        {{ $reservations->links() }}
                    </div>
                @endif
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

.btn-browse {
    background: #0D5C63;
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-browse:hover {
    background: #084C52;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 92, 99, 0.3);
    color: white;
}

/* Reservation Card - Unique Class (No Conflict) */
.reservation-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.reservation-card-header {
    padding: 22px 28px;
    border-bottom: 1px solid #E2E8F0;
    background: white;
}

.reservation-card-header h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}

/* Reservation Table - Unique Class */
.reservation-table {
    width: 100%;
    border-collapse: collapse;
}

.reservation-table thead th {
    background: #176d74;
    padding: 18px 20px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #f1f3f7;
    border-bottom: 2px solid #E2E8F0;
    text-align: left;
}

.reservation-table tbody td {
    padding: 16px 30px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}

.reservation-table tbody tr:hover {
    background: #F8FAFC;
}

/* Book Info Cell */
.book-info-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.book-info-cell strong {
    color: #2D3748;
    font-size: 0.9rem;
}

.book-info-cell small {
    font-size: 0.7rem;
    color: #6B7280;
}

/* Date Info */
.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    white-space: nowrap;
}

/* Reservation Badges - Unique Class (No Conflict with Messages) */
.res-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.res-badge-pending {
    background: #FEF3C7;
    color: #D97706;
}

.res-badge-active {
    background: #D1FAE5;
    color: #059669;
}

.res-badge-expired {
    background: #FEE2E2;
    color: #DC2626;
}

.res-badge-cancelled {
    background: #F3F4F6;
    color: #6B7280;
}

.res-badge-notified {
    background: #E0F2FE;
    color: #0284C7;
}

.res-badge-issued {
    background: #D1FAE5;
    color: #059669;
}

/* Cancel Button */
.btn-cancel-reservation {
    background: #FEE2E2;
    color: #DC2626;
    border: none;
    padding: 6px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.7rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-cancel-reservation:hover {
    background: #DC2626;
    color: white;
    transform: translateY(-2px);
}

/* Empty State Button */
.btn-browse-empty {
    display: inline-flex;
    align-items: center;
    background: #0D5C63;
    color: white;
    padding: 8px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-browse-empty:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
}

.text-muted {
    color: #6B7280;
}

.text-center {
    text-align: center;
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

/* Pagination */
.reservation-pagination {
    padding: 20px 25px;
    border-top: 1px solid #E2E8F0;
    background: white;
}

.pagination {
    display: flex;
    justify-content: flex-end;
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
    .reservation-table thead th,
    .reservation-table tbody td {
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
    .btn-browse {
        text-align: center;
        justify-content: center;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .reservation-table {
        min-width: 700px;
    }
}
</style>

<script>
function cancelReservation(id) {
    Swal.fire({
        title: 'Cancel Reservation?',
        text: "Are you sure you want to cancel this reservation?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/student/reservations/${id}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      Swal.fire('Cancelled!', 'Your reservation has been cancelled.', 'success');
                      location.reload();
                  } else {
                      Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
                  }
              }).catch(error => {
                  Swal.fire('Error!', 'Network error. Please try again.', 'error');
              });
        }
    });
}
</script>
@endsection