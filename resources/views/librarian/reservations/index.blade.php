@extends('layouts.dashboard')

@section('title', 'Book Reservations')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Book Reservations</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Manage student book reservations and waitlist</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-hourglass-half me-2"></i>
                    Active: {{ $reservations->where('status', 'active')->count() }}
                    <span class="divider">|</span>
                    <i class="fas fa-clock me-2"></i>
                    Pending: {{ $reservations->where('status', 'pending')->count() }}
                </div>
            </div>
            
            <!-- Reservations Table -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-bookmark me-2" style="color: #0D5C63;"></i> All Reservations</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Book</th>
                                <th>Reserved On</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <strong>{{ $res->student->full_name }}</strong>
                                        <small>{{ $res->student->roll_no }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="book-info-cell">
                                        <strong>{{ Str::limit($res->book->title, 40) }}</strong>
                                        <small>{{ $res->book->author }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div style="white-space: nowrap;">
    {{ $res->reservation_date->format('d M Y') }} 
</div>
                                </td>
                                <td>
                                    @if($res->expiry_date)
                                        <div style="white-space: nowrap;">
    {{ $res->expiry_date->format('d M Y') }} 
    <!-- {{ $res->expiry_date->format('h:i A') }} -->
    @if($res->isExpired())
        <span style="background: #FEE2E2; color: #DC2626; padding: 2px 6px; border-radius: 10px; font-size: 0.65rem; margin-left: 5px;">Expired</span>
    @endif
</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($res->status == 'pending')
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i> Waitlist #{{ $res->position }}
                                        </span>
                                    @elseif($res->status == 'active')
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                    @elseif($res->status == 'expired')
                                        <span class="status-badge status-expired">
                                            <i class="fas fa-hourglass-end me-1"></i> Expired
                                        </span>
                                    @elseif($res->status == 'cancelled')
                                        <span class="status-badge status-cancelled">
                                            <i class="fas fa-times-circle me-1"></i> Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td>
    <div class="action-buttons">
        @if($res->status == 'pending')
            <button class="btn-notify" onclick="notifyStudent({{ $res->book_id }})">
                <i class="fas fa-bell"></i> Notify
            </button>
        @endif
        @if(in_array($res->status, ['pending', 'active']))
            <form action="{{ route('librarian.reservations.cancel', $res->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-cancel" onclick="return confirm('Cancel this reservation?')">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </form>
        @endif
    </div>
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-bookmark fa-3x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No reservations found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($reservations->hasPages())
                    <div class="pagination-wrapper">
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

.stats-badge .divider {
    opacity: 0.5;
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
    padding: 16px 35px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}
.custom-table tbody tr:hover {
    background: #F8FAFC;
}


/* Student Info */
.student-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.student-info strong {
    color: #2D3748;
    font-size: 0.9rem;
}

.student-info small {
    font-size: 0.7rem;
    color: #6B7280;
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
    font-size: 0.85rem;
}

.date-info small {
    font-size: 0.7rem;
    color: #6B7280;
}

.expired-date {
    color: #DC2626;
}

.expired-badge {
    display: inline-block;
    background: #FEE2E2;
    color: #DC2626;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
    margin-top: 5px;
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

.status-pending {
    background: #FEF3C7;
    color: #D97706;
}

.status-active {
    background: #D1FAE5;
    color: #059669;
}

.status-expired {
    background: #FEE2E2;
    color: #DC2626;
}

.status-cancelled {
    background: #F3F4F6;
    color: #6B7280;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-notify {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-notify:hover {
    background: #084C52;
    transform: translateY(-2px);
}

.btn-cancel {
    background: #FEE2E2;
    color: #DC2626;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-cancel:hover {
    background: #DC2626;
    color: white;
}

/* Pagination Wrapper */
.pagination-wrapper {
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
        min-width: 800px;
    }
}
</style>

<script>
function notifyStudent(bookId) {
    Swal.fire({
        title: 'Notify Next Student?',
        text: "This will notify the next student in the waitlist that the book is available.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0D5C63',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, notify them!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/librarian/reservations/notify/${bookId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      Swal.fire('Notified!', 'Student has been notified.', 'success');
                      location.reload();
                  } else {
                      Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
                  }
              });
        }
    });
}

function cancelReservation(reservationId) {
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
            fetch(`/librarian/reservations/${reservationId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      Swal.fire('Cancelled!', 'Reservation has been cancelled.', 'success');
                      location.reload();
                  } else {
                      Swal.fire('Error!', 'Something went wrong.', 'error');
                  }
              });
        }
    });
}
</script>
@endsection