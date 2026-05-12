@extends('layouts.dashboard')

@section('title', 'Book Requests')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Book Requests</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Manage student book requests</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Total Requests: {{ $requests->total() }}
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
            
            <!-- Requests Table Card -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-clipboard-list me-2" style="color: #0D5C63;"></i> Book Requests List</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Student</th>
                                <th>Roll No</th>
                                <th>Book Requested</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Response</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                            <tr class="request-row">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="student-info">
                                        <strong>{{ $request->student->full_name ?? 'N/A' }}</strong>
                                        <small class="text-muted">{{ $request->student->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>{{ $request->student->roll_no ?? 'N/A' }}</td>
                                <td>
                                    <div class="book-info">
                                        <strong>{{ $request->book->title ?? 'N/A' }}</strong>
                                        <small class="text-muted">by {{ $request->book->author ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>{{ $request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d M Y') : \Carbon\Carbon::parse($request->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($request->status == 'pending')
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                    @elseif($request->status == 'approved')
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check-circle me-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->admin_response)
                                        {{ $request->admin_response }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($request->status == 'pending')
                                        <div class="action-buttons">
                                            <form action="{{ route('librarian.book-requests.approve', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="action-btn approve-btn" onclick="return confirm('Approve this request and issue book?')" title="Approve & Issue">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="action-btn reject-btn" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('librarian.book-requests.reject', $request->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-times-circle me-2" style="color: #DC2626;"></i>
                                                                Reject Request
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label-custom">Reason for rejection:</label>
                                                                <textarea name="reason" class="form-control-custom" rows="3" placeholder="Enter reason for rejecting this request..."></textarea>
                                                            </div>
                                                            <div class="request-summary">
                                                                <p><strong>Student:</strong> {{ $request->student->full_name ?? 'N/A' }}</p>
                                                                <p><strong>Book:</strong> {{ $request->book->title ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn-reject-modal">Reject Request</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($request->status == 'approved')
                                        <span class="processed-badge approved-badge">
                                            <i class="fas fa-check-circle"></i> Processed
                                        </span>
                                    @else
                                        <span class="processed-badge rejected-badge">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No book requests found</p>
                                    <small class="text-muted">When students request books, they will appear here</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-wrapper">
                    {{ $requests->links() }}
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
    padding: 16px 16px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}
.request-row:hover {
    background: #F8FAFC;
}

/* Student and Book Info */
.student-info, .book-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.student-info strong, .book-info strong {
    color: #2D3748;
}

.student-info small, .book-info small {
    font-size: 0.75rem;
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

.status-pending {
    background: #FEF3C7;
    color: #D97706;
}

.status-approved {
    background: #D1FAE5;
    color: #059669;
}

.status-rejected {
    background: #FEE2E2;
    color: #DC2626;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.approve-btn {
    background: #D1FAE5;
    color: #059669;
}

.approve-btn:hover {
    background: #059669;
    color: white;
    transform: translateY(-2px);
}

.reject-btn {
    background: #FEE2E2;
    color: #DC2626;
}

.reject-btn:hover {
    background: #DC2626;
    color: white;
    transform: translateY(-2px);
}

/* Processed Badges */
.processed-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.approved-badge {
    background: #D1FAE5;
    color: #059669;
}

.rejected-badge {
    background: #FEE2E2;
    color: #DC2626;
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
}

.modal-header {
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    padding: 20px 25px;
}

.modal-header .modal-title {
    font-weight: 600;
    color: #2D3748;
}

.modal-body {
    padding: 25px;
}

.form-label-custom {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2D3748;
    font-size: 0.9rem;
}

.form-control-custom {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-control-custom:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
}

.request-summary {
    background: #F8FAFC;
    padding: 12px 15px;
    border-radius: 10px;
    margin-top: 15px;
}

.request-summary p {
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.request-summary p:last-child {
    margin-bottom: 0;
}

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #E2E8F0;
    gap: 10px;
}

.btn-cancel-modal {
    background: #F3F4F6;
    color: #4B5563;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-cancel-modal:hover {
    background: #E5E7EB;
}

.btn-reject-modal {
    background: #DC2626;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-reject-modal:hover {
    background: #B91C1C;
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

<!-- Bootstrap JS for Modals (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection