<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Student;
use App\Models\IssueBook;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalStudents = Student::count();
        $issuedBooks = IssueBook::where('status', 'issued')->count();
        $returnedBooks = IssueBook::where('status', 'returned')->count();
        $availableBooks = Book::sum('available');
        
        $recentActivities = IssueBook::with(['book', 'student'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('librarian.dashboard', compact(
            'totalBooks', 
            'totalStudents', 
            'issuedBooks',
            'returnedBooks',
            'availableBooks', 
            'recentActivities'
        ));
    }
}