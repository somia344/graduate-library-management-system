@extends('layouts.dashboard')

@section('title', 'Add Book')

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
                    <h2 class="fw-bold" style="color: #0D5C63; margin: 0;">Add New Book</h2>
                    <p class="text-muted mt-1" style="color: #6B7280;">Add a new book to the library collection</p>
                </div>
               
            </div>
            
            @if ($errors->any())
                <div class="alert-custom alert-error-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Add Book Form Card -->
            <div class="form-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-book-open me-2" style="color: #0D5C63;"></i> Book Information</h5>
                </div>
                <div class="card-body-custom">
                    <form action="{{ route('librarian.books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-book me-2"></i>Book Title <span class="required">*</span>
                                </label>
                                <input type="text" name="title" class="form-control-custom" required value="{{ old('title') }}" placeholder="Enter book title">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-edit me-2"></i>Author <span class="required">*</span>
                                </label>
                                <input type="text" name="author" class="form-control-custom" required value="{{ old('author') }}" placeholder="Enter author name">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tag me-2"></i>Category <span class="required">*</span>
                                </label>
                                <select name="category" class="form-control-custom" required>
    <option value="">Select Category</option>
    <option value="Computer Science" {{ old('category') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
    <option value="Information Technology" {{ old('category') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
    <option value="Mathematics" {{ old('category') == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
    <option value="Economics" {{ old('category') == 'Economics' ? 'selected' : '' }}>Economics</option>
    <option value="Urdu" {{ old('category') == 'Urdu' ? 'selected' : '' }}>Urdu</option>
    <option value="Political Science" {{ old('category') == 'Political Science' ? 'selected' : '' }}>Political Science</option>
    <option value="English" {{ old('category') == 'English' ? 'selected' : '' }}>English</option>
    <option value="Education" {{ old('category') == 'Education' ? 'selected' : '' }}>Education</option>
    <option value="Islamiyat" {{ old('category') == 'Islamiyat' ? 'selected' : '' }}>Islamiyat</option>
    <option value="Chemistry" {{ old('category') == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
    <option value="Psychology" {{ old('category') == 'Psychology' ? 'selected' : '' }}>Psychology</option>
    <option value="Biology" {{ old('category') == 'Biology' ? 'selected' : '' }}>Biology</option>
    <option value="Statistics" {{ old('category') == 'Statistics' ? 'selected' : '' }}>Statistics</option>
    <option value="Zoology" {{ old('category') == 'Zoology' ? 'selected' : '' }}>Zoology</option>
    <option value="Botany" {{ old('category') == 'Botany' ? 'selected' : '' }}>Botany</option>
    <option value="Sociology" {{ old('category') == 'Sociology' ? 'selected' : '' }}>Sociology</option>
    <option value="Pak Studies" {{ old('category') == 'Pak Studies' ? 'selected' : '' }}>Pak Studies</option>
</select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-barcode me-2"></i>ISBN (Optional)
                                </label>
                                <input type="text" name="isbn" class="form-control-custom" value="{{ old('isbn') }}" placeholder="Enter ISBN number">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-cubes me-2"></i>Quantity <span class="required">*</span>
                                </label>
                                <input type="number" name="quantity" class="form-control-custom" required min="1" value="{{ old('quantity', 1) }}" placeholder="Enter quantity">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-image me-2"></i>Book Image
                                </label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="book_image" id="book_image" class="file-input" accept="image/*" onchange="previewImage(this)">
                                    <label for="book_image" class="file-label">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>Choose Image
                                    </label>
                                    <small class="text-muted d-block mt-2">Optional: Upload book cover image (JPG, PNG, JPEG)</small>
                                    <div id="imagePreview" class="image-preview mt-3" style="display: none;">
                                        <img id="previewImg" src="#" alt="Preview" style="max-width: 150px; border-radius: 10px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>Add Book
                            </button>
                            <a href="{{ route('librarian.books.index') }}" class="btn-cancel">
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

/* Back Button */
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
}

.alert-error-custom {
    background: #FEE2E2;
    color: #DC2626;
    border-left: 4px solid #DC2626;
}

.alert-error-custom ul {
    padding-left: 20px;
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

/* File Upload */
.file-upload-wrapper {
    position: relative;
}

.file-input {
    display: none;
}

.file-label {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #F3F4F6;
    border: 2px dashed #D1D5DB;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    color: #4B5563;
}

.file-label:hover {
    background: #E5E7EB;
    border-color: #0D5C63;
}

.image-preview {
    margin-top: 15px;
}

.image-preview img {
    max-width: 150px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 92, 99, 0.3);
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
}

/* Image Preview Function */
@media (min-width: 769px) {
    .form-row:last-of-type {
        margin-bottom: 0;
    }
}
</style>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        previewImg.src = '#';
    }
}
</script>
@endsection