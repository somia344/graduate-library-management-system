<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\IssueBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RequestBookController extends Controller
{
    // This is for showing student's own requests
    public function index()
{
    $student = Auth::guard('student')->user();

    \App\Models\BookRequest::where('student_id', $student->id)
        ->whereIn('status', ['approved', 'rejected'])
        ->update(['is_seen' => 1]);

    

    // Student ki saari requests fetch karein list dikhane ke liye
    $requests = \App\Models\BookRequest::where('student_id', $student->id)
        ->with('book')
        ->orderBy('updated_at', 'desc') // Taki latest response sabse upar dikhe
        ->paginate(10);

    $books = \App\Models\Book::paginate(12);

    return view('student.request-books', compact('requests', 'books'));
}
    // This is for requesting a book (AJAX)
    public function request(Request $request, $bookId)
    {
        $student = Auth::guard('student')->user();
        $book = Book::findOrFail($bookId);
        
        // Check if book is available
        if ($book->available <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This book is not available right now!'
            ], 400);
        }
        
        // Check if student already has 3 issued books
        $issuedCount = IssueBook::where('student_id', $student->id)
            ->where('status', 'issued')
            ->count();
            
        if ($issuedCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'You already have 3 issued books! Please return some books first.'
            ], 400);
        }
        
        // Check if already requested this book
        $existingRequest = BookRequest::where('student_id', $student->id)
            ->where('book_id', $bookId)
            ->where('status', 'pending')
            ->first();
            
        if ($existingRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You have already requested this book!'
            ], 400);
        }
        
        // Create request
        $bookRequest = new BookRequest();
        $bookRequest->student_id = $student->id;
        $bookRequest->book_id = $bookId;
        $bookRequest->request_date = Carbon::now();
        $bookRequest->status = 'pending';
        $bookRequest->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Book requested successfully! Librarian will review your request.'
        ]);
    }
}