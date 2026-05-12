<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IssueBook;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\Notification;  // ← ADD THIS

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get statistics
        $bookIssued = IssueBook::where('student_id', $student->id)
            ->where('status', 'issued')
            ->count();
            
        $pendingRequests = BookRequest::where('student_id', $student->id)
            ->where('status', 'pending')
            ->count();
            
        $overdueBooks = IssueBook::where('student_id', $student->id)
            ->where('status', 'issued')
            ->where('return_date', '<', now())
            ->count();
            
        $availableBooks = Book::where('available', '>', 0)->count();
        
        // Get recent issued books
        $recentIssued = IssueBook::with(['book'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // ==================== NOTIFICATION CODE ====================
        // Get unread notifications for the student
        $notifications = Notification::where('student_id', $student->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $unreadCount = $notifications->count();
        // ==================== END NOTIFICATION CODE ====================
        
        return view('student.dashboard', compact(
            'student', 
            'bookIssued', 
            'pendingRequests', 
            'overdueBooks', 
            'availableBooks', 
            'recentIssued',
            'notifications',      // ← ADD THIS
            'unreadCount'         // ← ADD THIS
        ));
    }
    
    // ==================== MARK NOTIFICATIONS AS READ ====================
    public function markAllNotificationsRead()
    {
        $student = Auth::guard('student')->user();
        Notification::where('student_id', $student->id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
    
    public function markNotificationRead($id)
    {
        $student = Auth::guard('student')->user();
        Notification::where('id', $id)->where('student_id', $student->id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
    // ==================== END NOTIFICATION CODE ====================
}