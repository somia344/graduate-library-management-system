@extends('layouts.dashboard')

@section('title', 'Return Books')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Return Books</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Process book returns and calculate fines</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-book-reader me-2"></i>
                Total Issued: {{ $issueBooks->count() ?? 0 }}

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
            
            <!-- Return Books Table Card -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-undo-alt me-2" style="color: #0D5C63;"></i> Currently Issued Books</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Book Details</th>
                                <th>Student Details</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issueBooks as $issueBook)
                           @php
    $today = \Carbon\Carbon::now();
    $dueDate = \Carbon\Carbon::parse($issueBook->return_date);
    $daysOverdue = $today->gt($dueDate) ? (int)$dueDate->diffInDays($today) : 0;
    $fine = $daysOverdue * 20;
    $isOverdue = $daysOverdue > 0;
@endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="book-info">
                                        <strong>{{ $issueBook->book->title ?? 'N/A' }}</strong>
                                        <small>by {{ $issueBook->book->author ?? 'N/A' }}</small>
                                        <small class="text-muted">ISBN: {{ $issueBook->book->isbn ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-info">
                                        <strong>{{ $issueBook->student->full_name ?? 'N/A' }}</strong>
                                        <small>{{ $issueBook->student->roll_no ?? 'N/A' }}</small>
                                        <small class="text-muted">{{ $issueBook->student->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($issueBook->issue_date)->format('d M Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($issueBook->return_date)->format('d M Y') }}
                                    @if($isOverdue)
                                        <span class="overdue-badge">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isOverdue)
                                        <span class="days-overdue">{{ $daysOverdue }} days</span>
                                    @else
                                        <span class="text-success">On Time</span>
                                    @endif
                                </td>
                                <td>
                                    @if($fine > 0)
                                        <span class="fine-badge">Rs. {{ number_format($fine, 2) }}</span>
                                    @else
                                        <span class="text-muted">No Fine</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-issued">
                                        <i class="fas fa-book-open me-1"></i>Issued
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="return-btn" onclick="showReturnModal({{ $issueBook->id }}, '{{ addslashes($issueBook->book->title) }}', '{{ addslashes($issueBook->student->full_name) }}', {{ $fine }}, {{ $daysOverdue }})">
                                        <i class="fas fa-undo-alt me-2"></i>Return Book
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-book-reader fa-4x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No issued books found</p>
                                    <small class="text-muted">All books have been returned</small>
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

<!-- Return Book Modal -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="returnForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-undo-alt me-2" style="color: #0D5C63;"></i>
                        Confirm Book Return
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="return-summary">
                        <div class="summary-row">
                            <span class="summary-label">Book:</span>
                            <span class="summary-value" id="modalBookTitle"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Student:</span>
                            <span class="summary-value" id="modalStudentName"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Days Overdue:</span>
                            <span class="summary-value" id="modalDaysOverdue"></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Fine Amount:</span>
                            <span class="summary-value fine-amount" id="modalFineAmount"></span>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label class="form-label">Additional Notes (Optional)</label>
                        <textarea name="notes" class="form-control-custom" rows="2" placeholder="Any additional notes about the return..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-confirm-return">
                        <i class="fas fa-check-circle me-2"></i>Confirm Return
                    </button>
                </div>
            </form>
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
}

.custom-table thead th {
    background: #176d74;
    padding: 18px 20px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #f1f3f7;
    border-bottom: 2px solid #E2E8F0;
    text-align: left;
}

.custom-table tbody td {
    padding: 16px 15px;
    font-size: 0.9rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
    vertical-align: middle;
}

.custom-table tbody tr:hover {
    background: #F8FAFC;
}

/* Book and Student Info */
.book-info, .student-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.book-info strong, .student-info strong {
    color: #2D3748;
}

.book-info small, .student-info small {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Status and Badges */
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

.days-overdue {
    color: #DC2626;
    font-weight: 600;
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

/* Return Button */
.return-btn {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.return-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 92, 99, 0.3);
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

.return-summary {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #E2E8F0;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-label {
    font-weight: 600;
    color: #4A5568;
}

.summary-value {
    color: #2D3748;
}

.fine-amount {
    color: #D97706;
    font-weight: 700;
    font-size: 1.1rem;
}

.form-label {
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

.btn-confirm-return {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-confirm-return:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
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
        padding: 12px 10px;
        font-size: 0.85rem;
    }
    
    .return-btn {
        padding: 6px 12px;
        font-size: 0.75rem;
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
        font-size: 0.75rem;
    }
    .return-btn {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
    .summary-row {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<script>
function showReturnModal(issueId, bookTitle, studentName, fineAmount, daysOverdue) {
    // Set form action URL
    document.getElementById('returnForm').action = '/librarian/return-books/' + issueId + '/process';
    
    // Set modal content
    document.getElementById('modalBookTitle').textContent = bookTitle;
    document.getElementById('modalStudentName').textContent = studentName;
    document.getElementById('modalDaysOverdue').innerHTML = daysOverdue > 0 ? '<span class="text-danger">' + daysOverdue + ' days</span>' : 'No overdue';
    document.getElementById('modalFineAmount').innerHTML = fineAmount > 0 ? 'Rs. ' + fineAmount.toFixed(2) : 'No fine';
    
    // Show modal
    var myModal = new bootstrap.Modal(document.getElementById('returnModal'));
    myModal.show();
}
</script>

<!-- Bootstrap JS for Modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection