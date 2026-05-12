<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use App\Models\Book;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // All active reservations
    public function index()
    {
        $reservations = BookReservation::with(['student', 'book'])
            ->whereIn('status', ['pending', 'active'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);
        
        return view('librarian.reservations.index', compact('reservations'));
    }
    
    // Show waitlist for a specific book
    public function waitlist($bookId)
    {
        $book = Book::findOrFail($bookId);
        
        $waitlist = BookReservation::with('student')
            ->where('book_id', $bookId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('librarian.reservations.waitlist', compact('book', 'waitlist'));
    }
    
    // Manually notify next student
    public function notifyNext($bookId)
    {
        $nextReservation = BookReservation::where('book_id', $bookId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->first();
        
        if ($nextReservation) {
            $nextReservation->update([
                'status' => 'active',
                'expiry_date' => now()->addDays(3)
            ]);
            
            return redirect()->back()->with('success', 'Student notified successfully!');
        }
        
        return redirect()->back()->with('error', 'No pending reservations');
    }
    
    // Manually cancel a reservation
   public function cancel($id)
{
    $reservation = BookReservation::findOrFail($id);
    $reservation->update(['status' => 'cancelled']);
    
    // Agar AJAX request hai to JSON return karo
    if (request()->ajax()) {
        return response()->json(['success' => true]);
    }
    
    // Normal request ke liye redirect
    return redirect()->back()->with('success', 'Reservation cancelled');
}
}