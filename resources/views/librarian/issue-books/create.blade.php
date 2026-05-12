@extends('layouts.dashboard')

@section('title', 'Issue New Book')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Issue New Book</h2>
                    <p class="text-muted mt-1" style="color: #6B7280;">Issue a book to a student</p>
                </div>
              
            </div>
            
            @if(session('error'))
                <div class="alert-custom alert-error-custom">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif
            
            <!-- Issue Book Form Card -->
            <div class="form-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-exchange-alt me-2" style="color: #0D5C63;"></i> Book Issuance Form</h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('librarian.issue-books.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-graduate me-2"></i>Select Student <span class="required">*</span>
                                </label>
                                <select name="student_id" class="form-control-custom" required id="studentSelect">
                                    <option value="">-- Select Student --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" data-roll="{{ $student->roll_no }}" data-email="{{ $student->email }}" data-class="{{ $student->class }}">
                                            {{ $student->full_name }} (Roll No: {{ $student->roll_no }}) - {{ $student->class }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Search for student by name or roll number</small>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-book me-2"></i>Select Book <span class="required">*</span>
                                </label>
                                <select name="book_id" class="form-control-custom" required id="bookSelect">
                                    <option value="">-- Select Book --</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" data-available="{{ $book->available }}" data-author="{{ $book->author }}" data-quantity="{{ $book->quantity }}">
                                            {{ $book->title }} by {{ $book->author }} 
                                            @if($book->available > 0)
                                                (Available: {{ $book->available }})
                                            @else
                                                (Not Available)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Only books with available copies are shown</small>
                            </div>
                        </div>
                        
                        <!-- Student Info Card (Shows when student selected) -->
                        <div id="studentInfoCard" class="info-card" style="display: none;">
                            <div class="info-card-header">
                                <i class="fas fa-user-circle"></i> Student Information
                            </div>
                            <div class="info-card-body">
                                <div class="info-row">
                                    <span class="info-label">Name:</span>
                                    <span class="info-value" id="studentName"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Roll No:</span>
                                    <span class="info-value" id="studentRoll"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value" id="studentEmail"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Class:</span>
                                    <span class="info-value" id="studentClass"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Book Info Card (Shows when book selected) -->
                        <div id="bookInfoCard" class="info-card" style="display: none;">
                            <div class="info-card-header">
                                <i class="fas fa-book-open"></i> Book Information
                            </div>
                            <div class="info-card-body">
                                <div class="info-row">
                                    <span class="info-label">Title:</span>
                                    <span class="info-value" id="bookTitle"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Author:</span>
                                    <span class="info-value" id="bookAuthor"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Total Copies:</span>
                                    <span class="info-value" id="bookTotal"></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Available Copies:</span>
                                    <span class="info-value" id="bookAvailable"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Issue Date <span class="required">*</span>
                                </label>
                                <input type="date" name="issue_date" class="form-control-custom" required value="{{ date('Y-m-d') }}" id="issueDate">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-check me-2"></i>Return Date <span class="required">*</span>
                                </label>
                                <input type="date" name="return_date" class="form-control-custom" required value="{{ date('Y-m-d', strtotime('+14 days')) }}" id="returnDate">
                                <small class="text-muted">Standard lending period is 14 days</small>
                            </div>
                        </div>
                        
                        <!-- Return Date Calculator -->
                        <div class="date-calculator">
                            <button type="button" class="date-preset" onclick="setReturnDays(7)">7 days</button>
                            <button type="button" class="date-preset" onclick="setReturnDays(14)">14 days</button>
                            <button type="button" class="date-preset" onclick="setReturnDays(21)">21 days</button>
                            <button type="button" class="date-preset" onclick="setReturnDays(30)">30 days</button>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                <i class="fas fa-exchange-alt me-2"></i>Issue Book
                            </button>
                            <a href="{{ route('librarian.issue-books.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
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

.btn-back {
    background: #6B7280;
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-back:hover {
    background: #4B5563;
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
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

/* Form Card */
.form-card {
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

.card-body-custom {
    padding: 28px;
}

/* Form Layout */
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 0;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2D3748;
    font-size: 0.9rem;
}

.required {
    color: #DC2626;
}

.form-control-custom {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control-custom:focus {
    outline: none;
    border-color: #0D5C63;
    box-shadow: 0 0 0 3px rgba(13, 92, 99, 0.1);
}

select.form-control-custom {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234A5568' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
}

/* Info Cards */
.info-card {
    background: #F8FAFC;
    border-radius: 12px;
    margin-bottom: 25px;
    overflow: hidden;
    border: 1px solid #E2E8F0;
}

.info-card-header {
    background: #E2E8F0;
    padding: 12px 20px;
    font-weight: 600;
    color: #2D3748;
    font-size: 0.9rem;
}

.info-card-header i {
    margin-right: 8px;
    color: #0D5C63;
}

.info-card-body {
    padding: 15px 20px;
}

.info-row {
    display: flex;
    margin-bottom: 8px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-label {
    width: 120px;
    font-weight: 600;
    color: #4A5568;
    font-size: 0.85rem;
}

.info-value {
    flex: 1;
    color: #2D3748;
    font-size: 0.85rem;
}

/* Date Calculator Buttons */
.date-calculator {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.date-preset {
    background: #F3F4F6;
    border: 1px solid #E2E8F0;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    color: #4B5563;
    cursor: pointer;
    transition: all 0.3s ease;
}

.date-preset:hover {
    background: #0D5C63;
    color: white;
    border-color: #0D5C63;
    transform: translateY(-2px);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px solid #E2E8F0;
}

.btn-submit {
    background: linear-gradient(135deg, #0D5C63 0%, #084C52 100%);
    color: white;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
}

.btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-cancel {
    background: #F3F4F6;
    color: #4B5563;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-cancel:hover {
    background: #E5E7EB;
    color: #1F2937;
    text-decoration: none;
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
@media (max-width: 992px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .dashboard-main {
        padding: 25px 30px;
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
    .btn-back {
        width: 100%;
        justify-content: center;
    }
    .page-title h2 {
        font-size: 1.6rem;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn-submit, .btn-cancel {
        width: 100%;
        justify-content: center;
    }
    .card-body-custom {
        padding: 20px;
    }
    .date-calculator {
        justify-content: center;
    }
    .info-row {
        flex-direction: column;
    }
    .info-label {
        width: auto;
        margin-bottom: 4px;
    }
}
</style>

<script>
// Student selection handler
document.getElementById('studentSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const studentInfoCard = document.getElementById('studentInfoCard');
    const submitBtn = document.getElementById('submitBtn');
    const bookSelected = document.getElementById('bookSelect').value;
    
    if (this.value) {
        const rollNo = selectedOption.getAttribute('data-roll') || 'N/A';
        const email = selectedOption.getAttribute('data-email') || 'N/A';
        const className = selectedOption.getAttribute('data-class') || 'N/A';
        const studentName = selectedOption.text.split(' (')[0];
        
        document.getElementById('studentName').textContent = studentName;
        document.getElementById('studentRoll').textContent = rollNo;
        document.getElementById('studentEmail').textContent = email;
        document.getElementById('studentClass').textContent = className;
        
        studentInfoCard.style.display = 'block';
    } else {
        studentInfoCard.style.display = 'none';
    }
    
    submitBtn.disabled = !(this.value && bookSelected);
});

// Book selection handler
document.getElementById('bookSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const bookInfoCard = document.getElementById('bookInfoCard');
    const submitBtn = document.getElementById('submitBtn');
    const studentSelected = document.getElementById('studentSelect').value;
    
    if (this.value) {
        const title = selectedOption.text.split(' by ')[0];
        const author = selectedOption.getAttribute('data-author') || 'N/A';
        const available = selectedOption.getAttribute('data-available') || '0';
        const quantity = selectedOption.getAttribute('data-quantity') || '0';
        
        document.getElementById('bookTitle').textContent = title;
        document.getElementById('bookAuthor').textContent = author;
        document.getElementById('bookTotal').textContent = quantity;
        document.getElementById('bookAvailable').textContent = available;
        
        bookInfoCard.style.display = 'block';
        
        if (parseInt(available) === 0) {
            submitBtn.disabled = true;
            submitBtn.title = 'This book is not available';
        } else {
            submitBtn.disabled = !(this.value && studentSelected);
        }
    } else {
        bookInfoCard.style.display = 'none';
        submitBtn.disabled = true;
    }
    
    if (this.value && studentSelected) {
        const available = selectedOption.getAttribute('data-available') || '0';
        submitBtn.disabled = (parseInt(available) === 0);
    }
});

function setReturnDays(days) {
    const returnDate = new Date();
    returnDate.setDate(returnDate.getDate() + days);
    const year = returnDate.getFullYear();
    const month = String(returnDate.getMonth() + 1).padStart(2, '0');
    const day = String(returnDate.getDate()).padStart(2, '0');
    document.getElementById('returnDate').value = `${year}-${month}-${day}`;
}

document.getElementById('issueDate').addEventListener('change', function() {
    const issueDate = new Date(this.value);
    const returnDate = new Date(document.getElementById('returnDate').value);
    
    if (returnDate <= issueDate) {
        alert('Return date must be after issue date');
        const newReturnDate = new Date(issueDate);
        newReturnDate.setDate(issueDate.getDate() + 14);
        const year = newReturnDate.getFullYear();
        const month = String(newReturnDate.getMonth() + 1).padStart(2, '0');
        const day = String(newReturnDate.getDate()).padStart(2, '0');
        document.getElementById('returnDate').value = `${year}-${month}-${day}`;
    }
});
</script>
@endsection