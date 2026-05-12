<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LibrarianAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Librarian\DashboardController;
use App\Http\Controllers\Librarian\BookController;
use App\Http\Controllers\Librarian\StudentController;
use App\Http\Controllers\Librarian\IssueBookController;
use App\Http\Controllers\Librarian\ReturnBookController;
use App\Http\Controllers\Librarian\BookRequestController;
use App\Http\Controllers\Librarian\ContactMessageController;
use App\Http\Controllers\Librarian\ReportController;
use App\Http\Controllers\Librarian\ReservationController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\MyIssuedBookController;
use App\Http\Controllers\Student\SearchBookController;
use App\Http\Controllers\Student\RequestBookController;
use App\Http\Controllers\Student\ContactReplyController;
use App\Http\Controllers\Student\ProfileController;

// ==================== DEFAULT LOGIN ROUTE ====================
Route::get('/login', function() {
    return redirect()->route('student.login');
})->name('login');

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// ==================== LIBRARIAN AUTH ROUTES ====================
Route::prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/login', [LibrarianAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LibrarianAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [LibrarianAuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [LibrarianAuthController::class, 'showForgotForm'])->name('forgot');
    Route::post('/reset-password', [LibrarianAuthController::class, 'resetPassword'])->name('reset');
});

// ==================== STUDENT AUTH ROUTES ====================
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    Route::get('/register', [StudentAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.submit');
    Route::get('/forgot-password', [StudentAuthController::class, 'showForgotForm'])->name('forgot');
    Route::post('/reset-password', [StudentAuthController::class, 'resetPassword'])->name('reset');
});

// ==================== RESET PASSWORD ROUTES ====================
Route::get('/student/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('student.password.reset')->defaults('role', 'student');
Route::post('/student/password/reset', [ResetPasswordController::class, 'reset'])->name('student.password.update');

Route::get('/librarian/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('librarian.password.reset')->defaults('role', 'librarian');
Route::post('/librarian/password/reset', [ResetPasswordController::class, 'reset'])->name('librarian.password.update');

// ==================== LIBRARIAN PROTECTED ROUTES ====================
Route::prefix('librarian')->name('librarian.')->middleware('auth:librarian')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manage Books Routes
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/store', [BookController::class, 'store'])->name('store');
        Route::get('/{book}/edit', [BookController::class, 'edit'])->name('edit');
        Route::put('/{book}', [BookController::class, 'update'])->name('update');
        Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        Route::get('/{book}', [BookController::class, 'show'])->name('show');
    });
    
    // Manage Students Routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::post('/store', [StudentController::class, 'store'])->name('store');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
    });
    
    // Issue Books Routes
    Route::prefix('issue-books')->name('issue-books.')->group(function () {
        Route::get('/', [IssueBookController::class, 'index'])->name('index');
        Route::get('/create', [IssueBookController::class, 'create'])->name('create');
        Route::post('/store', [IssueBookController::class, 'store'])->name('store');
        Route::get('/{issueBook}/edit', [IssueBookController::class, 'edit'])->name('edit');
        Route::put('/{issueBook}', [IssueBookController::class, 'update'])->name('update');
        Route::delete('/{issueBook}', [IssueBookController::class, 'destroy'])->name('destroy');
        Route::get('/{issueBook}', [IssueBookController::class, 'show'])->name('show');
    });
    
    // Return Books Routes
    Route::prefix('return-books')->name('return-books.')->group(function () {
        Route::get('/', [ReturnBookController::class, 'index'])->name('index');
        Route::get('/{issueBook}/return', [ReturnBookController::class, 'create'])->name('create');
        Route::post('/{issueBook}/process', [ReturnBookController::class, 'process'])->name('process');
        Route::put('/{returnBook}', [ReturnBookController::class, 'update'])->name('update');
        Route::delete('/{returnBook}', [ReturnBookController::class, 'destroy'])->name('destroy');
    });
    
    // Book Requests Routes
    Route::prefix('book-requests')->name('book-requests.')->group(function () {
        Route::get('/', [BookRequestController::class, 'index'])->name('index');
        Route::post('/{request}/approve', [BookRequestController::class, 'approve'])->name('approve');
        Route::post('/{request}/reject', [BookRequestController::class, 'reject'])->name('reject');
    });
    
    // Contact Messages Routes
    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/{message}', [ContactMessageController::class, 'show'])->name('show');
        Route::post('/{message}/reply', [ContactMessageController::class, 'reply'])->name('reply');
        Route::delete('/{message}', [ContactMessageController::class, 'destroy'])->name('destroy');
    });
    
    // ==================== BOOK RESERVATION ROUTES (LIBRARIAN) ====================
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/book/{book}/waitlist', [ReservationController::class, 'waitlist'])->name('waitlist');
        Route::post('/book/{book}/notify-next', [ReservationController::class, 'notifyNext'])->name('notify-next');
        Route::post('/{id}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
    });
    // ==================== END BOOK RESERVATION ROUTES ====================
    
    // ==================== REPORTS ROUTES ====================
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/download-pdf', [ReportController::class, 'downloadPDF'])->name('reports.download-pdf');
    Route::get('/reports/download-csv', [ReportController::class, 'downloadCSV'])->name('reports.download-csv');
    // ==================== END REPORTS ROUTES ====================
});

// ==================== STUDENT PROTECTED ROUTES ====================
Route::prefix('student')->name('student.')->middleware('auth:student')->group(function () {
    
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-issued-books', [MyIssuedBookController::class, 'index'])->name('my-issued-books');
    
    Route::get('/search-books', [SearchBookController::class, 'index'])->name('search-books');
    Route::post('/search-books/search', [SearchBookController::class, 'search'])->name('search-books.search');
    Route::get('/search-books/filter', [SearchBookController::class, 'filter'])->name('search-books.filter');
    
    Route::post('/books/{book}/request', [RequestBookController::class, 'request'])->name('request-book');
    
    // ==================== BOOK RESERVATION ROUTES (STUDENT) ====================
    Route::post('/books/{book}/reserve', [SearchBookController::class, 'reserve'])->name('book.reserve');
    Route::get('/my-reservations', [SearchBookController::class, 'myReservations'])->name('my-reservations');
    Route::post('/reservations/{id}/cancel', [SearchBookController::class, 'cancelReservation'])->name('reservation.cancel');
    // ==================== END BOOK RESERVATION ROUTES ====================
    
    Route::get('/request-books', [RequestBookController::class, 'index'])->name('request-books.index');
    
    Route::get('/contact-reply', [ContactReplyController::class, 'index'])->name('contact-reply');
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// ==================== NOTIFICATION ROUTES ====================
Route::post('/notifications/mark-all-read', [StudentDashboardController::class, 'markAllNotificationsRead'])->name('student.notifications.mark-all-read');
Route::post('/notifications/{id}/mark-read', [StudentDashboardController::class, 'markNotificationRead'])->name('student.notifications.mark-read');

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return redirect('/');
});