<div class="sidebar">
    <div class="text-center mb-4">
        <div class="logo-wrapper mb-3">
            <i class="fas fa-graduation-cap fa-4x" style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h5 class="fw-bold" style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Graduate Library
        </h5>
        <p class="text-muted small mb-0">Librarian Panel</p>
    </div>
    <hr>
    
    <nav class="nav flex-column">
        <!-- Dashboard -->
        <a class="nav-link {{ request()->routeIs('librarian.dashboard') ? 'active' : '' }}" 
           href="{{ route('librarian.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Manage Books -->
        <a class="nav-link {{ request()->routeIs('librarian.books.*') && !request()->routeIs('librarian.books.create') ? 'active' : '' }}" 
           href="{{ route('librarian.books.index') }}">
            <i class="fas fa-book"></i>
            <span>Manage Books</span>
        </a>
        
        <!-- Add Book -->
        <a class="nav-link {{ request()->routeIs('librarian.books.create') ? 'active' : '' }}" 
           href="{{ route('librarian.books.create') }}">
            <i class="fas fa-plus-circle"></i>
            <span>Add Book</span>
        </a>
        
        <!-- Manage Students -->
        <a class="nav-link {{ request()->routeIs('librarian.students.*') && !request()->routeIs('librarian.students.create') ? 'active' : '' }}" 
           href="{{ route('librarian.students.index') }}">
            <i class="fas fa-users"></i>
            <span>Manage Students</span>
        </a>
        
        <!-- Add Student -->
        <a class="nav-link {{ request()->routeIs('librarian.students.create') ? 'active' : '' }}" 
           href="{{ route('librarian.students.create') }}">
            <i class="fas fa-user-plus"></i>
            <span>Add Student</span>
        </a>
        
        <!-- Issue Book -->
        <a class="nav-link {{ request()->routeIs('librarian.issue-books.*') ? 'active' : '' }}" 
           href="{{ route('librarian.issue-books.index') }}">
            <i class="fas fa-exchange-alt"></i>
            <span>Issue Book</span>
        </a>
        
        <!-- Return Book -->
        <a class="nav-link {{ request()->routeIs('librarian.return-books.*') ? 'active' : '' }}" 
           href="{{ route('librarian.return-books.index') }}">
            <i class="fas fa-undo-alt"></i>
            <span>Return Book</span>
        </a>
        
        <!-- Book Request -->
        <a class="nav-link {{ request()->routeIs('librarian.book-requests.*') ? 'active' : '' }}" 
           href="{{ route('librarian.book-requests.index') }}">
            <i class="fas fa-question-circle"></i>
            <span>Book Request</span>
            @php
                $pendingCount = \App\Models\BookRequest::where('status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
            @endif
        </a>
        
        <!-- Contact Message -->
        <a class="nav-link {{ request()->routeIs('librarian.contact-messages.*') ? 'active' : '' }}" 
           href="{{ route('librarian.contact-messages.index') }}">
            <i class="fas fa-envelope"></i>
            <span>Contact Message</span>
            @php
                $unreadCount = \App\Models\ContactMessage::where('status', 'unread')->count();
            @endphp
            @if($unreadCount > 0)
                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
            @endif
        </a>
        
        <!-- Reports -->
        <a class="nav-link {{ request()->routeIs('librarian.reports') ? 'active' : '' }}" 
           href="{{ route('librarian.reports') }}">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>
    </nav>
    
    <hr class="my-3">
    
    <!-- Logout Button -->
    <form action="{{ route('librarian.logout') }}" method="POST" class="mt-auto">
        @csrf
        <button type="submit" class="btn btn-danger w-100 logout-btn">
            <i class="fas fa-sign-out-alt me-2"></i>
            Logout
        </button>
    </form>
    
    <!-- Version Info -->
    <div class="text-center mt-3">
        <small class="text-muted">Version 1.0 | Graduate Library</small>
    </div>
</div>

<style>
.sidebar {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    position: sticky;
    top: 80px;
}

.sidebar:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.sidebar .nav-link {
    padding: 12px 15px;
    margin: 5px 0;
    border-radius: 12px;
    transition: all 0.3s ease;
    color: #4a5568;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}

.sidebar .nav-link i {
    width: 24px;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.sidebar .nav-link span {
    flex: 1;
}

.sidebar .nav-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(8px);
}

.sidebar .nav-link:hover i {
    transform: scale(1.1);
}

.sidebar .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.sidebar .nav-link.active i {
    color: white;
}

/* Logout Button Animation */
.logout-btn {
    transition: all 0.3s ease;
    border-radius: 12px;
    padding: 10px;
    font-weight: 500;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

/* Badge Styling */
.sidebar .nav-link .badge {
    transition: all 0.3s ease;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

/* Divider Styling */
.sidebar hr {
    margin: 15px 0;
    border-color: rgba(0,0,0,0.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        position: static;
        margin-bottom: 20px;
    }
    
    .sidebar .nav-link span {
        font-size: 14px;
    }
    
    .sidebar .nav-link i {
        width: 20px;
        font-size: 1rem;
    }
}

/* Scrollbar for sidebar if needed */
.sidebar nav {
    flex: 1;
    overflow-y: auto;
    max-height: calc(100vh - 250px);
}

.sidebar nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar nav::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.sidebar nav::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}
</style>