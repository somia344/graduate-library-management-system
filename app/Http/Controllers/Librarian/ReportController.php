<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Student;
use App\Models\IssueBook;
use App\Models\BookRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'books');
        $start_date = $request->get('start_date', Carbon::now()->subDays(30));
        $end_date = $request->get('end_date', Carbon::now());
        
        $reports = [];
        
        if ($type == 'books') {
            $reports = Book::orderBy('id', 'asc')->get();
        } elseif ($type == 'students') {
            $reports = Student::orderBy('id', 'asc')->get();
        } elseif ($type == 'issued') {
            $reports = IssueBook::with(['book', 'student'])
                ->whereBetween('issue_date', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($type == 'returned') {
            $reports = IssueBook::with(['book', 'student'])
                ->where('status', 'returned')
                ->whereBetween('updated_at', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($type == 'requests') {
            $reports = BookRequest::with(['student', 'book'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        }
        
        $totalBooks = Book::count();
        $totalStudents = Student::count();
        $totalIssued = IssueBook::where('status', 'issued')->count();
        $totalReturned = IssueBook::where('status', 'returned')->count();
        $totalRequests = BookRequest::count();
        $pendingRequests = BookRequest::where('status', 'pending')->count();
        $totalMessages = ContactMessage::count();
        $unreadMessages = ContactMessage::where('status', 'unread')->count();
        
        return view('librarian.reports.index', compact(
            'reports', 'type', 'start_date', 'end_date',
            'totalBooks', 'totalStudents', 'totalIssued', 'totalReturned',
            'totalRequests', 'pendingRequests', 'totalMessages', 'unreadMessages'
        ));
    }
    
    public function downloadPDF(Request $request)
    {
        $type = $request->get('type', 'books');
        $start_date = $request->get('start_date', Carbon::now()->subDays(30));
        $end_date = $request->get('end_date', Carbon::now());
        
        if ($type == 'books') {
            $reports = Book::orderBy('id', 'asc')->get();
        } elseif ($type == 'students') {
            $reports = Student::orderBy('id', 'asc')->get();
        } elseif ($type == 'issued') {
            $reports = IssueBook::with(['book', 'student'])
                ->whereBetween('issue_date', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($type == 'returned') {
            $reports = IssueBook::with(['book', 'student'])
                ->where('status', 'returned')
                ->whereBetween('updated_at', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($type == 'requests') {
            $reports = BookRequest::with(['student', 'book'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $reports = collect();
        }
        
        $title = ucfirst($type) . " Report";
        $date = now()->format('d-m-Y H:i:s');
        
        $pdf = Pdf::loadView('librarian.reports-pdf', compact('reports', 'type', 'title', 'date', 'start_date', 'end_date'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download($type . '-report-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function downloadCSV(Request $request)
    {
        $type = $request->get('type', 'books');
        $start_date = $request->get('start_date', Carbon::now()->subDays(30));
        $end_date = $request->get('end_date', Carbon::now());
        
        if ($type == 'books') {
            $reports = Book::orderBy('id', 'asc')->get();
        } elseif ($type == 'students') {
            $reports = Student::orderBy('id', 'asc')->get();
        } elseif ($type == 'issued') {
            $reports = IssueBook::with(['book', 'student'])
                ->whereBetween('issue_date', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($type == 'returned') {
            $reports = IssueBook::with(['book', 'student'])
                ->where('status', 'returned')
                ->whereBetween('updated_at', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } elseif ($type == 'requests') {
            $reports = BookRequest::with(['student', 'book'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $reports = collect();
        }
        
        $fileName = $type . '-report-' . date('Y-m-d') . '.csv';
        
        $output = fopen('php://temp', 'w');
        fputs($output, "\xEF\xBB\xBF");
        fputcsv($output, $this->getCSVHeaders($type));
        
        $serial = 1;
foreach ($reports as $report) {
    fputcsv($output, $this->getCSVRow($report, $type, $serial));
    $serial++;
}
        
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
    
    private function getCSVHeaders($type)
    {
        switch($type) {
            case 'books':
                return ['ID', 'Title', 'Author', 'Category', 'ISBN', 'Quantity', 'Available', 'Created At'];
            case 'students':
                return ['ID', 'Full Name', 'Father Name', 'Email', 'Phone', 'Class', 'Roll No', 'Department', 'Registration No'];
            case 'issued':
    return ['S.No', 'ID', 'Book Title', 'Book Author', 'Student Name', 'Roll No', 'Issue Date', 'Due Date', 'Status'];
            case 'returned':
                return ['ID', 'Book Title', 'Student Name', 'Roll No', 'Issue Date', 'Return Date', 'Days Overdue', 'Fine'];
            case 'requests':
                return ['ID', 'Student Name', 'Student Email', 'Book Title', 'Book Author', 'Request Date', 'Status', 'Response'];
            default:
                return ['ID', 'Title', 'Created At'];
        }
    }
    
    private function getCSVRow($item, $type, $serial)
{
    switch($type) {
        case 'books':
            return [
                $item->id,
                $item->title,
                $item->author,
                $item->category ?? 'General',
                $item->isbn ?? 'N/A',
                $item->quantity,
                $item->available,
                $item->created_at ? $item->created_at->format('Y-m-d') : 'N/A'
            ];
        case 'students':
            return [
                $item->id,
                $item->full_name,
                $item->father_name,
                $item->email,
                $item->phone_number,
                $item->class,
                $item->roll_no,
                $item->department ?? 'N/A',
                $item->registration_no ?? 'N/A'
            ];
       case 'issued':
    return [
        $serial,
        $item->id,
        $item->book->title ?? 'N/A',
        $item->book->author ?? 'N/A',
        $item->student->full_name ?? 'N/A',
        $item->student->roll_no ?? 'N/A',
        $item->issue_date ? \Carbon\Carbon::parse($item->issue_date)->format('d-m-Y') : 'N/A',
        $item->return_date ? \Carbon\Carbon::parse($item->return_date)->format('d-m-Y') : 'N/A',
        $item->status ?? 'N/A'
    ];
        case 'returned':
            $daysOverdue = 0;
            if($item->return_date && now()->gt($item->return_date)) {
                $daysOverdue = (int)now()->diffInDays($item->return_date);
            }
            return [
                $item->id,
                $item->book->title ?? 'N/A',
                $item->student->full_name ?? 'N/A',
                $item->student->roll_no ?? 'N/A',
                $item->issue_date ? \Carbon\Carbon::parse($item->issue_date)->format('Y-m-d') : 'N/A',
                $item->return_date ? \Carbon\Carbon::parse($item->return_date)->format('Y-m-d') : 'N/A',
                $daysOverdue,
                $item->fine ?? 0
            ];
        case 'requests':
            return [
                $item->id,
                $item->student->full_name ?? 'N/A',
                $item->student->email ?? 'N/A',
                $item->book->title ?? 'N/A',
                $item->book->author ?? 'N/A',
                $item->created_at ? $item->created_at->format('Y-m-d') : 'N/A',
                ucfirst($item->status),
                $item->admin_response ?? '-'
            ];
        default:
            return [
                $item->id, 
                $item->title ?? 'N/A', 
                isset($item->created_at) ? $item->created_at->format('Y-m-d') : 'N/A'
            ];
    }
}
}