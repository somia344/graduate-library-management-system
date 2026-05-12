@extends('layouts.dashboard')

@section('title', 'Search Books')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Search Books</h2>
                    <!-- <p class="text-muted mt-1" style="color: #6B7280;">Find and request books from the library collection</p> -->
                </div>
                <div class="stats-badge">
                    <i class="fas fa-book me-2"></i>
                    Total Books: {{ $books->total() }}
                </div>
            </div>
            
            <!-- Search Form -->
            <div class="search-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-search me-2" style="color: #0D5C63;"></i> Search Filters</h5>
                </div>
                <div class="card-body-custom">
                    <form method="GET" action="{{ route('student.search-books') }}" class="search-form">
                        @csrf
                        <div class="search-row">
                            <div class="search-input-group">
                                <div class="search-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" name="search" class="search-field" placeholder="Search by book title, author, or ISBN..." value="{{ request('search') }}">
                            </div>
                            
                            <div class="category-select">
                                <select name="category" class="category-dropdown">
                                    <option value="all">All Categories</option>
                                    @php
                                        $categories = ['Fiction', 'Non-Fiction', 'Science', 'Technology', 'Mathematics', 'History', 'Literature', 'Art', 'Music', 'Philosophy', 'Psychology', 'Business', 'Economics', 'Law', 'Medicine', 'Engineering'];
                                    @endphp
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            
                            @if(request('search') || request('category') != 'all')
                                <a href="{{ route('student.search-books') }}" class="clear-btn">
                                    <i class="fas fa-times me-2"></i>Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Search Results Info -->
            @if(request('search') || request('category') != 'all')
            <div class="search-info">
                <div class="search-info-content">
                    <i class="fas fa-info-circle"></i>
                    <span>Showing results for: </span>
                    @if(request('search'))
                        <strong>"{{ request('search') }}"</strong>
                    @endif
                    @if(request('category') != 'all')
                        @if(request('search')) <span>in</span> @endif
                        <strong>{{ request('category') }}</strong>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Books Grid -->
            @if($books->count() > 0)
            <div class="books-grid">
                @foreach($books as $book)
                <div class="book-card" data-aos="fade-up">
                    <div class="book-image">
                        @if($book->book_image)
                            <img src="{{ asset($book->book_image) }}" alt="{{ $book->title }}">
                        @else
                            <div class="book-placeholder">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif
                        <div class="book-badge {{ $book->available > 0 ? 'available-badge' : 'unavailable-badge' }}">
                            {{ $book->available > 0 ? 'Available' : 'Not Available' }}
                        </div>
                    </div>
                    <div class="book-details">
                        <h4 class="book-title">{{ Str::limit($book->title, 35) }}</h4>
                        <p class="book-author">
                            <i class="fas fa-user-edit"></i> {{ $book->author }}
                        </p>
                        <div class="book-meta">
                            <span class="category-tag">
                                <i class="fas fa-tag"></i> {{ $book->category ?? 'General' }}
                            </span>
                            @if($book->isbn)
                            <span class="isbn">
                                <i class="fas fa-barcode"></i> {{ $book->isbn }}
                            </span>
                            @endif
                        </div>
                        <div class="book-stats">
                            <div class="stat">
                                <span class="stat-label">Total Copies:</span>
                                <span class="stat-value">{{ $book->quantity }}</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Available:</span>
                                <span class="stat-value {{ $book->available > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $book->available }}
                                </span>
                            </div>
                        </div>
                        @if($book->available > 0)
                            <button class="request-book-btn" onclick="requestBook({{ $book->id }}, '{{ addslashes($book->title) }}')">
                                <i class="fas fa-hand-paper me-2"></i>Request This Book
                            </button>
                        @else
                            <button class="reserve-btn" onclick="reserveBook({{ $book->id }}, '{{ addslashes($book->title) }}')">
                                <i class="fas fa-bookmark me-2"></i>Reserve (Waitlist)
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination-wrapper">
                {{ $books->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-search fa-4x"></i>
                <h3>No books found</h3>
                <p>We couldn't find any books matching your search criteria.</p>
                <p class="text-muted">Try searching with different keywords or browse by category</p>
                <a href="{{ route('student.search-books') }}" class="reset-search-btn">
                    <i class="fas fa-sync-alt me-2"></i>Reset Search
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Request Book Modal -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-hand-paper me-2" style="color: #0D5C63;"></i>
                    Request Book
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="request-info">
                    <i class="fas fa-book-open"></i>
                    <div>
                        <p class="mb-0">Are you sure you want to request:</p>
                        <strong id="modalBookTitle"></strong>
                    </div>
                </div>
                <p class="mt-3 text-muted small">The librarian will review your request and notify you once approved.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-confirm-request" id="confirmRequestBtn">
                    <i class="fas fa-check-circle me-2"></i>Confirm Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reserve Book Modal -->
<div class="modal fade" id="reserveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bookmark me-2" style="color: #0D5C63;"></i>
                    Reserve Book
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="request-info">
                    <i class="fas fa-clock"></i>
                    <div>
                        <p class="mb-0">Book is currently not available.</p>
                        <strong id="reserveBookTitle"></strong>
                        <p class="mt-2 text-muted small">You will be added to waitlist. When book becomes available, you will be notified.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-confirm-reserve" id="confirmReserveBtn">
                    <i class="fas fa-check-circle me-2"></i>Confirm Reserve
                </button>
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

/* Search Card */
.search-card {
    background: #176d74;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 25px;
    color:white;
}

.card-header-custom {
    padding: 18px 20px;
    border-bottom: 1px solid #E2E8F0;
    background: white;
}

.card-header-custom h5 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2D3748;
    margin: 0;
}

.card-body-custom {
    padding: 28px;
}

.search-row {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-group {
    flex: 2;
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 15px;
    color: #A0AEC0;
}

.search-field {
    width: 100%;
    padding: 14px 15px 14px 45px;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.search-field:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
}

.category-select {
    flex: 1;
}

.category-dropdown {
    width: 100%;
    padding: 14px 15px;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.95rem;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-dropdown:focus {
    outline: none;
    border-color: #0D5C63;
}

.search-btn {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
}

.clear-btn {
    background: #F24B4B;
    color: white;
    text-decoration: none;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.clear-btn:hover {
    background: #4B5563;
    transform: translateY(-2px);
    color: white;
}

/* Search Info */
.search-info {
    background: #E0F2FE;
    border-left: 4px solid #0D5C63;
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
}

.search-info-content {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    color: #0D5C63;
}

/* Books Grid */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.book-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.book-image {
    width: 130px;
    position: relative;
    background: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
}

.book-placeholder i {
    font-size: 3rem;
    color: white;
}

.book-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    background: white;
}

.available-badge {
    background: #D1FAE5;
    color: #059669;
}

.unavailable-badge {
    background: #FEE2E2;
    color: #DC2626;
}

.book-details {
    flex: 1;
    padding: 18px;
}

.book-title {
    font-size: 1rem;
    font-weight: 700;
    color: #2D3748;
    margin: 0 0 5px;
}

.book-author {
    font-size: 0.85rem;
    color: #6B7280;
    margin: 0 0 10px;
}

.book-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

.category-tag, .isbn {
    font-size: 0.7rem;
    padding: 3px 8px;
    background: #F3F4F6;
    border-radius: 12px;
    color: #4B5563;
}

.category-tag i, .isbn i {
    margin-right: 4px;
}

.book-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    padding: 8px 0;
    border-top: 1px solid #E2E8F0;
    border-bottom: 1px solid #E2E8F0;
}

.stat {
    display: flex;
    gap: 5px;
}

.stat-label {
    font-size: 0.75rem;
    color: #6B7280;
}

.stat-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: #2D3748;
}

.request-book-btn {
    width: 100%;
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.request-book-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 92, 99, 0.3);
}

.reserve-btn {
    width: 100%;
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.reserve-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.unavailable-btn {
    width: 100%;
    background: #F3F4F6;
    color: #6B7280;
    border: none;
    padding: 10px;
    border-radius: 10px;
    font-weight: 600;
    cursor: not-allowed;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
}

.empty-state i {
    color: #A0AEC0;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #2D3748;
    margin-bottom: 10px;
}

.reset-search-btn {
    display: inline-block;
    margin-top: 20px;
    background: #0D5C63;
    color: white;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.reset-search-btn:hover {
    background: #084C52;
    transform: translateY(-2px);
    color: white;
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

.request-info {
    display: flex;
    gap: 15px;
    align-items: center;
    background: #F8FAFC;
    padding: 15px;
    border-radius: 12px;
}

.request-info i {
    font-size: 2rem;
    color: #0D5C63;
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

.btn-confirm-request {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-confirm-request:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 92, 99, 0.3);
}

.btn-confirm-reserve {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-confirm-reserve:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* Pagination Wrapper */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 20px;
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
        padding: 25px 30px;
    }
    
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
    .search-row {
        flex-direction: column;
    }
    .search-input-group, .category-select, .search-btn, .clear-btn {
        width: 100%;
    }
    .book-card {
        flex-direction: column;
    }
    .book-image {
        width: 100%;
        height: 180px;
    }
    .books-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentBookId = null;
let currentReserveBookId = null;

function requestBook(bookId, bookTitle) {
    currentBookId = bookId;
    document.getElementById('modalBookTitle').textContent = bookTitle;
    var myModal = new bootstrap.Modal(document.getElementById('requestModal'));
    myModal.show();
}

function reserveBook(bookId, bookTitle) {
    currentReserveBookId = bookId;
    document.getElementById('reserveBookTitle').textContent = bookTitle;
    var myModal = new bootstrap.Modal(document.getElementById('reserveModal'));
    myModal.show();
}

document.getElementById('confirmRequestBtn').addEventListener('click', function() {
    if (currentBookId) {
        fetch(`/student/books/${currentBookId}/request`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success!', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Something went wrong', 'error');
        });
        
        var modal = bootstrap.Modal.getInstance(document.getElementById('requestModal'));
        modal.hide();
    }
});

document.getElementById('confirmReserveBtn').addEventListener('click', function() {
    if (currentReserveBookId) {
        fetch(`/student/books/${currentReserveBookId}/reserve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Reserved!', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Something went wrong', 'error');
        });
        
        var modal = bootstrap.Modal.getInstance(document.getElementById('reserveModal'));
        modal.hide();
    }
});
</script>
@endsection

