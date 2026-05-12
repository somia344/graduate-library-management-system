<div class="sidebar">
    <div class="text-center mb-4">
        <div class="logo-wrapper mb-3">
            <i class="fas fa-user-graduate fa-4x" style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
        </div>
        <h5 class="fw-bold" style="background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
            Graduate Library
        </h5>
        <p class="text-muted small mb-0">Student Panel</p>
    </div>
    <hr>
    
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" 
           href="{{ route('student.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        
        <a class="nav-link {{ request()->routeIs('student.my-issued-books') ? 'active' : '' }}" 
           href="{{ route('student.my-issued-books') }}">
            <i class="fas fa-book"></i>
            <span>My Issued Books</span>
        </a>
        
        <a class="nav-link {{ request()->routeIs('student.search-books') ? 'active' : '' }}" 
           href="{{ route('student.search-books') }}">
            <i class="fas fa-search"></i>
            <span>Search Books</span>
        </a>
        
        <a class="nav-link {{ request()->routeIs('student.request-books.index') ? 'active' : '' }}" 
           href="{{ route('student.request-books.index') }}">
            <i class="fas fa-question-circle"></i>
            <span>Request Books</span>
            @php
                if(auth()->guard('student')->check()) {
                    $pendingCount = \App\Models\BookRequest::where('student_id', auth()->guard('student')->id())
                        ->where('status', 'pending')
                        ->count();
                } else {
                    $pendingCount = 0;
                }
            @endphp
            @if($pendingCount > 0)
                <span class="notification-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        
        <a class="nav-link {{ request()->routeIs('student.contact-reply') ? 'active' : '' }}" 
           href="{{ route('student.contact-reply') }}">
            <i class="fas fa-reply-all"></i>
            <span>Contact Replies</span>
            @php
                if(auth()->guard('student')->check()) {
                    $student = auth()->guard('student')->user();
                    $msgCount = \App\Models\ContactMessage::where('email', $student->email)
                        ->where('status', 'unread')
                        ->count();
                } else {
                    $msgCount = 0;
                }
            @endphp
            @if($msgCount > 0)
                <span class="notification-badge">{{ $msgCount }}</span>
            @endif
        </a>
        
        <a class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}" 
           href="{{ route('student.profile') }}">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
        </a>
    </nav>
    
    <hr class="my-3">
    
    <form action="{{ route('student.logout') }}" method="POST" class="mt-auto">
        @csrf
        <button type="submit" class="btn btn-danger w-100 logout-btn">
            <i class="fas fa-sign-out-alt me-2"></i>
            Logout
        </button>
    </form>
    
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
}

.sidebar .nav-link i {
    width: 24px;
    font-size: 1.2rem;
}

.sidebar .nav-link:hover {
    background: linear-gradient(135deg, #0D5C63 0%, #1A7F88 100%);
    color: white;
    transform: translateX(8px);
}

.sidebar .nav-link:hover .notification-badge {
    background: white;
    color: #0D5C63;
}

.sidebar .nav-link.active {
    background: linear-gradient(135deg, #0D5C63 0%, #1A7F88 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(13, 92, 99, 0.3);
}

.sidebar .nav-link.active .notification-badge {
    background: white;
    color: #0D5C63;
}

/* Notification Badge */
.notification-badge {
    background: #F24B4B;
    color: white;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: auto;
    min-width: 22px;
    text-align: center;
}

.logout-btn {
    transition: all 0.3s ease;
    border-radius: 12px;
    padding: 10px;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

@media (max-width: 768px) {
    .sidebar {
        position: static;
        margin-bottom: 20px;
    }
}
</style>