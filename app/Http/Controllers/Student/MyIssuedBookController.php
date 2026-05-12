<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\IssueBook;
use Illuminate\Support\Facades\Auth;

class MyIssuedBookController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $issuedBooks = IssueBook::where('student_id', $student->id)
            ->with('book')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('student.my-issued-books', compact('issuedBooks'));
    }
}