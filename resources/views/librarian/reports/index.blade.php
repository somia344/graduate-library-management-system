@extends('layouts.dashboard')

@section('title', 'Reports')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Reports</h2>
                </div>
                <!-- ========== DOWNLOAD BUTTONS ADDED HERE ========== -->
                <div class="download-buttons">
                    <a href="{{ route('librarian.reports.download-pdf', ['type' => $type, 'start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn-pdf" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a>
                    <a href="{{ route('librarian.reports.download-csv', ['type' => $type, 'start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn-csv">
                        <i class="fas fa-file-excel me-2"></i>Excel (CSV)
                    </a>
                </div>
                <!-- ========== END DOWNLOAD BUTTONS ========== -->
            </div>
            
            <!-- Statistics Cards -->
            <div class="stats-summary">
                <div class="stat-card-mini">
                    <div class="stat-icon-mini" style="background: linear-gradient(135deg, #0D5C63 0%, #1A7F88 100%);">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stat-info-mini">
                        <span>Total Records</span>
                        <h3>{{ $reports->count() }}</h3>
                    </div>
                </div>
                <div class="stat-card-mini">
                    <div class="stat-icon-mini" style="background: linear-gradient(135deg, #F59E0B 0%, #FCD34D 100%);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info-mini">
                        <span>Report Type</span>
                        <h3>{{ ucfirst($type) }}</h3>
                    </div>
                </div>
            </div>
            
            <!-- Report Type Selection - Button + Dropdown -->
            <div class="filter-card">
                <div class="filter-header">
                    <span class="filter-title"><i class="fas fa-file-alt me-2"></i>Generate Report</span>
                    <div class="filter-controls">
                        <select class="report-select" id="reportTypeSelect">
                            <option value="{{ route('librarian.reports', ['type' => 'books']) }}" {{ $type == 'books' ? 'selected' : '' }}>Books Report</option>
                            <option value="{{ route('librarian.reports', ['type' => 'students']) }}" {{ $type == 'students' ? 'selected' : '' }}> Students Report</option>
                            <option value="{{ route('librarian.reports', ['type' => 'issued']) }}" {{ $type == 'issued' ? 'selected' : '' }}> Issued Books</option>
                            <option value="{{ route('librarian.reports', ['type' => 'returned']) }}" {{ $type == 'returned' ? 'selected' : '' }}>Returned Books</option>
                            <option value="{{ route('librarian.reports', ['type' => 'requests']) }}" {{ $type == 'requests' ? 'selected' : '' }}> Book Requests</option>
                        </select>
                        <button class="btn-generate" onclick="generateReport()">
                            <i class="fas fa-chart-line me-1"></i> Generate
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Report Table -->
            <div class="data-card">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-table me-2" style="color: #0D5C63;"></i>
                        {{ ucfirst($type) }} Report Details
                        <span class="record-count">({{ $reports->count() }} records found)</span>
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            @if($type == 'books')
                            <tr>
                                <th>ID</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Total Quantity</th>
                                <th>Available</th>
                                <th>Status</th>
                            </tr>
                            @elseif($type == 'students')
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Class</th>
                                <th>Roll No</th>
                            </tr>
                            @elseif($type == 'issued' || $type == 'returned')
                            <tr>
                                <th>#</th>
                                <th>Book Title</th>
                                <th>Student Name</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                            @elseif($type == 'requests')
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Book Title</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @if($type == 'books')
                                @forelse($reports as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><strong>{{ $item->title }}</strong></td>
                                    <td>{{ $item->author }}</td>
                                    <td>{{ $item->category ?? 'General' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->available }}</td>
                                    <td>
                                        @if($item->available > 0)
                                            <span class="status-badge status-available">Available</span>
                                        @else
                                            <span class="status-badge status-unavailable">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-4">No books found</td></tr>
                                @endforelse
                                
                            @elseif($type == 'students')
                                @forelse($reports as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><strong>{{ $item->full_name }}</strong></td>
                                    <td>{{ $item->father_name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone_number }}</td>
                                    <td>{{ $item->class }}</td>
                                    <td>{{ $item->roll_no }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-4">No students found</td></tr>
                                @endforelse
                                
                            @elseif($type == 'issued' || $type == 'returned')
                                @forelse($reports as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->book->title ?? 'N/A' }}</strong></td>
                                    <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->issue_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($item->status == 'returned')
                                            {{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'issued')
                                            <span class="status-badge status-issued">Issued</span>
                                        @else
                                            <span class="status-badge status-returned">Returned</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <td><td colspan="7" class="text-center py-4">No records found</td></tr>
                                @endforelse
                                
                            @elseif($type == 'requests')
                                @forelse($reports as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->student->full_name ?? 'N/A' }}</td>
                                    <td><strong>{{ $item->book->title ?? 'N/A' }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="status-badge status-pending">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="status-badge status-approved">Approved</span>
                                        @else
                                            <span class="status-badge status-rejected">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <div class="action-buttons">
                                                <form action="{{ route('librarian.book-requests.approve', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-approve" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('librarian.book-requests.reject', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn-reject" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4">No requests found</td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
                @if(method_exists($reports, 'links'))
                    <div class="pagination-wrapper">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    var select = document.getElementById('reportTypeSelect');
    var url = select.value;
    window.location.href = url;
}
</script>

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
    padding: 10px 40px;
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
    font-size: 2rem;
    font-weight: 700;
    color: #0D5C63;
    margin: 0;
}

/* ========== DOWNLOAD BUTTONS CSS ========== */
.download-buttons {
    display: flex;
    gap: 15px;
}

.btn-pdf {
    background: #DC2626;
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    font-size: 0.9rem;
}

.btn-pdf:hover {
    background: #B91C1C;
    transform: translateY(-2px);
    color: white;
}

.btn-csv {
    background: #059669;
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    font-size: 0.9rem;
}

.btn-csv:hover {
    background: #047857;
    transform: translateY(-2px);
    color: white;
}
/* ========== END DOWNLOAD BUTTONS CSS ========== */

/* Stats Summary */
.stats-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card-mini {
    background: white;
    border-radius: 12px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.stat-icon-mini {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon-mini i {
    font-size: 20px;
    color: white;
}

.stat-info-mini span {
    font-size: 0.95rem;
    color: #6B7280;
    display: block;
}

.stat-info-mini h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2D3748;
    margin: 3px 0 0;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    padding: 20px 20px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-title {
    font-size: 0.95rem;
    font-weight: 500;
    color: #2D3748;
}

.filter-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.report-select {
    padding: 10px 12px;
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    font-size: 0.95rem;
    color: #2D3748;
    cursor: pointer;
    min-width: 170px;
}

.report-select:hover {
    border-color: #0D5C63;
}

.report-select:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 2px rgba(13, 92, 99, 0.1);
}

.btn-generate {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-generate:hover {
    background: #084C52;
    transform: translateY(-1px);
}

/* Data Card */
.data-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header-custom {
    padding: 15px 20px;
    border-bottom: 1px solid #E2E8F0;
    background: white;
}

.card-header-custom h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}

.record-count {
    font-size: 0.75rem;
    color: #6B7280;
    font-weight: normal;
    margin-left: 8px;
}

/* Custom Table */
.custom-table {
    width: 100%;
    border-collapse: collapse;
}

.custom-table thead th {
    background: #176d74;
    padding: 14px 16px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #f1f3f7;
    border-bottom: 2px solid #E2E8F0;
    text-align: left;
}

.custom-table tbody td {
    padding: 12px 16px;
    font-size: 0.85rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}

.custom-table tbody tr:hover {
    background: #F8FAFC;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
}

.status-available {
    background: #D1FAE5;
    color: #059669;
}

.status-unavailable {
    background: #FEE2E2;
    color: #DC2626;
}

.status-issued {
    background: #FEF3C7;
    color: #D97706;
}

.status-returned {
    background: #D1FAE5;
    color: #059669;
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
    gap: 6px;
}

.btn-approve {
    background: #059669;
    color: white;
    border: none;
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.7rem;
}

.btn-approve:hover {
    background: #047857;
}

.btn-reject {
    background: #DC2626;
    color: white;
    border: none;
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.7rem;
}

.btn-reject:hover {
    background: #B91C1C;
}

/* Pagination Wrapper */
.pagination-wrapper {
    padding: 15px 20px;
    border-top: 1px solid #E2E8F0;
}

.pagination {
    display: flex;
    justify-content: flex-end;
    gap: 5px;
    margin: 0;
}

.pagination .page-item .page-link {
    background: white;
    border: 1px solid #E2E8F0;
    color: #4A5568;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
}

.pagination .page-item.active .page-link {
    background: #0D5C63;
    border-color: #0D5C63;
    color: white;
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
        padding: 20px;
    }
    .custom-table thead th,
    .custom-table tbody td {
        padding: 8px 12px;
    }
}

@media (max-width: 768px) {
    .dashboard-sidebar {
        transform: translateX(-100%);
        transition: 0.3s;
        width: 260px;
    }
    .dashboard-sidebar.show {
        transform: translateX(0);
    }
    .dashboard-main {
        margin-left: 0;
        padding: 15px;
    }
    .top-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .download-buttons {
        justify-content: center;
    }
    .stats-summary {
        grid-template-columns: 1fr;
    }
    .filter-header {
        flex-direction: column;
        align-items: stretch;
    }
    .filter-controls {
        flex-direction: column;
    }
    .report-select {
        width: 100%;
    }
    .btn-generate {
        width: 100%;
        text-align: center;
    }
    .custom-table {
        min-width: 650px;
    }
}
</style>
@endsection