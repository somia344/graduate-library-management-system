<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\IssueBook;
use App\Models\Book;
use App\Models\ReturnBook;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\BookReservation;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class ReturnBookController extends Controller
{
    public function index()
    {
        $issueBooks = IssueBook::with(['student', 'book'])
            ->where('status', 'issued')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('librarian.return-books.index', compact('issueBooks'));
    }
    
    public function create($id)
    {
        $issueBook = IssueBook::with(['student', 'book'])->findOrFail($id);
        return view('librarian.return-books.create', compact('issueBook'));
    }
    
    public function process(Request $request, $id)
    {
        $issueBook = IssueBook::findOrFail($id);
        
        $dueDate = Carbon::parse($issueBook->return_date);
        $returnDate = Carbon::now();
        
        // Calculate days overdue - INTEGER ONLY
        if ($returnDate->gt($dueDate)) {
            // Get difference in days using integer division
            $diffSeconds = $returnDate->timestamp - $dueDate->timestamp;
            $daysOverdue = (int)($diffSeconds / 86400); // 86400 seconds in a day
            
            // Agar seconds bache hain to 1 din aur add karo
            if ($diffSeconds % 86400 > 0) {
                $daysOverdue++;
            }
        } else {
            $daysOverdue = 0;
        }
        
        // Ensure daysOverdue is integer and not negative
        $daysOverdue = max(0, (int)$daysOverdue);
        
        // Calculate fine (Rs. 20 per day)
        $fineAmount = $daysOverdue * 20;
        
        // Create return record
        $returnBook = new ReturnBook();
        $returnBook->issue_book_id = $issueBook->id;
        $returnBook->return_date = $returnDate;
        $returnBook->days_overdue = $daysOverdue;
        $returnBook->fine_amount = $fineAmount;
        $returnBook->save();
        
        // Update issue book status
        $issueBook->status = 'returned';
        $issueBook->fine = $fineAmount;
        $issueBook->save();
        
        // Increase book available quantity
        $book = Book::find($issueBook->book_id);
        $book->available = $book->available + 1;
        $book->save();
        
        // ========== BOOK RESERVATION SYSTEM ==========
        // Check for pending reservations for this book
        $nextReservation = BookReservation::where('book_id', $issueBook->book_id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->first();
        
        if ($nextReservation) {
            // Activate reservation
            $nextReservation->update([
                'status' => 'active',
                'expiry_date' => Carbon::now()->addDays(3)
            ]);
            
            // ==================== SEND NOTIFICATION ====================
            Notification::create([
                'student_id' => $nextReservation->student_id,
                'book_id' => $issueBook->book_id,
                'type' => 'book_available',
                'title' => 'Book Available!',
                'message' => 'The book "' . $issueBook->book->title . '" you reserved is now available. Please collect it within 3 days.',
                'is_read' => false
            ]);
            // ==================== END NOTIFICATION ====================
            
            $message = "Book returned successfully! Reserved student has been notified.";
            if ($fineAmount > 0) {
                $message .= " Fine amount: Rs. " . number_format($fineAmount, 0) . " (Overdue by " . $daysOverdue . " day(s))";
            }
            
            return redirect()->route('librarian.return-books.index')
                ->with('success', $message);
        }
        // ========== END BOOK RESERVATION SYSTEM ==========
        
        $message = "Book returned successfully!";
        if ($fineAmount > 0) {
            $message .= " Fine amount: Rs. " . number_format($fineAmount, 0) . " (Overdue by " . $daysOverdue . " day(s))";
        } else {
            $message .= " No fine charged.";
        }
        
        return redirect()->route('librarian.return-books.index')
            ->with('success', $message);
    }
    
    public function update(Request $request, $id)
    {
        return redirect()->route('librarian.return-books.index')
            ->with('success', 'Return record updated successfully!');
    }
    
    public function destroy($id)
    {
        return redirect()->route('librarian.return-books.index')
            ->with('success', 'Return record deleted successfully!');
    }
}