@extends('layouts.dashboard')

@section('title', 'Manage Books')

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
            <!-- Top Bar with Add Book Button Only -->
            <div class="top-bar">
                <div class="page-title">
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Manage Books</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">View and manage all books in the library</p> -->
                </div>
                <a href="{{ route('librarian.books.create') }}" class="btn-add">
                    <i class="fas fa-plus me-2"></i>Add Book
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert-success-custom mb-4">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            <!-- Books Table Card -->
            <div class="recent-activities-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-book me-2" style="color: #0D5C63;"></i> All Books List</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>ISBN</th>
                                <th>Qty</th>
                                <th>Available</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                            <tr>
                                <td>{{ $book->id }}</td>
                                <td><strong>{{ $book->title }}</strong></td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    <span class="category-badge">{{ $book->category }}</span>
                                </td>
                                <td>{{ $book->isbn ?? 'N/A' }}</td>
                                <td>{{ $book->quantity }}</td>
                                <td>
                                    @if($book->available > 0)
                                        <span class="status-badge status-available">{{ $book->available }} available</span>
                                    @else
                                        <span class="status-badge status-unavailable">Out of stock</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('librarian.books.edit', $book->id) }}" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('librarian.books.destroy', $book->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this book?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-book-open fa-3x mb-3" style="color: #A0AEC0;"></i>
                                    <p class="mb-0" style="color: #6B7280;">No books found</p>
                                    <a href="{{ route('librarian.books.create') }}" class="btn-add-small mt-3">
                                        <i class="fas fa-plus me-1"></i> Add your first book
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($books->hasPages())
                    <div class="pagination-wrapper">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Container */
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

/* Responsive Sidebar */
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
}
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

/* Main Content - Large Size Proper Display */
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

/* Add Book Button */
.btn-add {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-add:hover {
    background: #084C52;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
    color: white;
}

/* Alert Success */
.alert-success-custom {
    background: #D1FAE5;
    color: #059669;
    border-left: 4px solid #0D5C63;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
}

/* Books Table Card - Large Size */
.recent-activities-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow-x: auto;
    width: 100%;
    
  
}

.card-header-custom {
    padding: 22px 28px;
    border-bottom: 1px solid #E2E8F0;
    background: white;
}

.card-header-custom h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}

/* Custom Table - Large Size */
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
    padding: 16px 25px;
    font-size: 0.95rem;
    color: #4A5568;
    border-bottom: 1px solid #E2E8F0;
}

.custom-table tbody tr:hover {
    background: #F8FAFC;
}

/* Category Badge */
.category-badge {
    background: #E2E8F0;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #2D3748;
    display: inline-block;
}

/* Status Badge */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.status-available {
    background: #D1FAE5;
    color: #059669;
}

.status-unavailable {
    background: #FEF3C7;
    color: #D97706;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-edit {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-edit:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
}

.btn-delete {
    background: #F24B4B;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
}

.btn-delete:hover {
    background: #D93636;
    transform: translateY(-2px);
}

/* Add Small Button */
.btn-add-small {
    background: #0D5C63;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 0.85rem;
    text-decoration: none;
    display: inline-block;
}

.btn-add-small:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
}

/* Pagination Wrapper */
.pagination-wrapper {
    padding: 20px 25px;
    border-top: 1px solid #E2E8F0;
}

/* Pagination Styling */
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
    background: rgba(245, 19, 19, 0.1);
}

.dashboard-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 5px;
}

/* Responsive */
@media (max-width: 1200px) {
    .dashboard-main {
        padding: 25px 35px;
    }
    
    .custom-table thead th,
    .custom-table tbody td {
        padding: 14px 16px;
    }
}

@media (max-width: 992px) {
    .dashboard-main {
        padding: 20px 25px;
    }
    
    .custom-table thead th,
    .custom-table tbody td {
        padding: 12px 14px;
        font-size: 0.85rem;
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
        padding: 15px 20px;
        width: 100%;
    }
    .top-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-add {
        text-align: center;
        justify-content: center;
    }
    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }
     .custom-table {
        min-width: 700px;
    } */
}
</style>
@endsection