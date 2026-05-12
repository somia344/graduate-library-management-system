<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Student;
use App\Models\IssueBook;
use Illuminate\Http\Request;

class IssueBookController extends Controller
{
    /**
     * Display a listing of issued books.
     */
    public function index()
    {
        $issueBooks = IssueBook::with(['student', 'book'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('librarian.issue-books.index', compact('issueBooks'));
    }
    
    /**
     * Show the form for creating a new book issuance.
     */
    public function create()
    {
        $students = Student::orderBy('full_name')->get();
        $books = Book::where('available', '>', 0)->orderBy('title')->get();
        
        return view('librarian.issue-books.create', compact('students', 'books'));
    }
    
    /**
     * Store a newly created book issuance.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'book_id' => 'required|exists:books,id',
            'issue_date' => 'required|date',
            'return_date' => 'required|date|after:issue_date',
        ]);
        
        $book = Book::findOrFail($request->book_id);
        
        // Check if book is available
        if ($book->available < 1) {
            return redirect()->back()->with('error', 'This book is not available for issuance.');
        }
        
        // Check if student already has this book issued and not returned
        $existingIssue = IssueBook::where('student_id', $request->student_id)
            ->where('book_id', $request->book_id)
            ->where('status', 'issued')
            ->first();
            
        if ($existingIssue) {
            return redirect()->back()->with('error', 'This student already has this book issued and not returned yet.');
        }
        
        // Create issue book record
        $issueBook = IssueBook::create([
            'student_id' => $request->student_id,
            'book_id' => $request->book_id,
            'issue_date' => $request->issue_date,
            'return_date' => $request->return_date,
            'status' => 'issued',
        ]);
        
        // Decrease book quantity
        $book->decrement('available');
        
        return redirect()->route('librarian.issue-books.index')
            ->with('success', 'Book issued successfully!');
    }
    
    /**
     * Display the specified issued book.
     */
    public function show($id)
    {
        $issueBook = IssueBook::with(['student', 'book'])->findOrFail($id);
        return view('librarian.issue-books.show', compact('issueBook'));
    }
    
    /**
     * Show the form for editing the specified issued book.
     */
    public function edit($id)
    {
        $issueBook = IssueBook::findOrFail($id);
        return view('librarian.issue-books.edit', compact('issueBook'));
    }
    
    /**
     * Update the specified issued book.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'status' => 'required|in:issued,returned',
        ]);
        
        $issueBook = IssueBook::findOrFail($id);
        $oldStatus = $issueBook->status;
        
        $issueBook->update([
            'return_date' => $request->return_date,
            'status' => $request->status,
        ]);
        
        // If book is being returned, increase available quantity
        if ($oldStatus == 'issued' && $request->status == 'returned') {
            $issueBook->book->increment('available');
        }
        
        return redirect()->route('librarian.issue-books.index')
            ->with('success', 'Book issuance record updated successfully!');
    }
    
    /**
     * Remove the specified issued book.
     */
    public function destroy($id)
    {
        $issueBook = IssueBook::findOrFail($id);
        
        // If book is issued, increase available quantity before deleting
        if ($issueBook->status == 'issued') {
            $issueBook->book->increment('available');
        }
        
        $issueBook->delete();
        
        return redirect()->route('librarian.issue-books.index')
            ->with('success', 'Record deleted successfully!');
    }
}