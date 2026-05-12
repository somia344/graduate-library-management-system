<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\BookReservation;


class SearchBookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();
        
        // Search by title or author
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
            });
        }
        
             \App\Models\Book::where('is_new', 1)->update(['is_new' => 0]);

        
        // Filter by category
        if ($request->has('category') && $request->category && $request->category != 'all') {
            $query->where('category', $request->category);
        }
        
        $books = $query->paginate(12);
        
        // Get all categories for filter dropdown
        $categories = Book::distinct()->pluck('category');
        
        return view('student.search-books', compact('books', 'categories'));
    }
    
    public function search(Request $request)
    {
        return $this->index($request);
    }
    
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    public function reserve(Request $request, $bookId)
{
    $student = auth()->guard('student')->user();
    $book = Book::findOrFail($bookId);
    
    // Check if book is available
    if ($book->available > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Book is available! You can issue it directly.'
        ]);
    }
    
    // Check if student already has active reservation for this book
    $existingReservation = BookReservation::where('student_id', $student->id)
        ->where('book_id', $bookId)
        ->whereIn('status', ['pending', 'active', 'notified'])
        ->first();
    
    if ($existingReservation) {
        return response()->json([
            'success' => false,
            'message' => 'You already have a pending reservation for this book!'
        ]);
    }
    
    // Create reservation
    $reservation = BookReservation::create([
        'student_id' => $student->id,
        'book_id' => $bookId,
        'reservation_date' => now(),
        'expiry_date' => now()->addDays(7),
        'status' => 'pending',
        'position' => 0
    ]);
    
    // Calculate waitlist position
    $position = BookReservation::where('book_id', $bookId)
        ->where('status', 'pending')
        ->where('created_at', '<', $reservation->created_at)
        ->count() + 1;
    
    $reservation->update(['position' => $position]);
    
    return response()->json([
        'success' => true,
        'message' => 'Book reserved successfully! Your position in waitlist: ' . $position,
        'position' => $position
    ]);
}

// View my reservations
public function myReservations()
{
    $student = auth()->guard('student')->user();
    
    $reservations = BookReservation::with('book')
        ->where('student_id', $student->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    return view('student.my-reservations', compact('reservations'));
}

// Cancel reservation
public function cancelReservation($id)
{
    $reservation = BookReservation::findOrFail($id);
    
    if ($reservation->student_id != auth()->guard('student')->id()) {
        abort(403);
    }
    
    $reservation->update(['status' => 'cancelled']);
    
    return redirect()->back()->with('success', 'Reservation cancelled successfully!');
}
}