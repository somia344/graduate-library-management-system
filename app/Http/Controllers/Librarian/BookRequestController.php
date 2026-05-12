<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\IssueBook;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookRequestController extends Controller
{
    public function index()
    {
        $requests = BookRequest::with(['student', 'book'])
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('librarian.book-requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $request = BookRequest::findOrFail($id);
        $book = Book::find($request->book_id);
        
        // Check if book is available
        if ($book->available <= 0) {
            return redirect()->back()->with('error', 'Book is not available!');
        }
        
        // Check if student already has 3 issued books
        $issuedCount = IssueBook::where('student_id', $request->student_id)
            ->where('status', 'issued')
            ->count();
        
        if ($issuedCount >= 3) {
            return redirect()->back()->with('error', 'Student already has 3 issued books!');
        }
        
        // Issue the book
        $issueBook = new IssueBook();
        $issueBook->student_id = $request->student_id;
        $issueBook->book_id = $request->book_id;
        $issueBook->issue_date = Carbon::now();
        $issueBook->return_date = Carbon::now()->addDays(14);
        $issueBook->status = 'issued';
        $issueBook->fine = 0;
        $issueBook->save();
        
        // Update book availability
        $book->available = $book->available - 1;
        $book->save();
        
        // Update request status
        $request->status = 'approved';
        $request->admin_response = 'Book approved and issued successfully!';
        $request->save();
        
        return redirect()->back()->with('success', 'Book request approved and issued successfully!');
    }

    public function reject(Request $request, $id)
    {
        $bookRequest = BookRequest::findOrFail($id);
        $bookRequest->status = 'rejected';
        $bookRequest->admin_response = $request->input('reason', 'Request rejected by librarian');
        $bookRequest->save();
        
        return redirect()->back()->with('success', 'Book request rejected!');
    }

    public function store(Request $request)
{
    // ... validation ...
    
    $book = Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'category' => $request->category,
        'isbn' => $request->isbn,
        'quantity' => $request->quantity,
        'available' => $request->quantity,
        'book_image' => $imagePath,
        'is_new' => 1,  // ✅ New book add kar rahe hain
    ]);
    
    return redirect()->route('librarian.books.index')->with('success', 'Book added successfully!');
}
}