<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;

class ContactReplyController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

         // Page open karte hi 'replied' messages ko 'read' kar do
    \App\Models\ContactMessage::where('email', $student->email)
        ->where('status', 'replied')
        ->update(['status' => 'read']);
        
        // Student ke email se related messages + replies
        $messages = ContactMessage::where('email', $student->email)
        
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('student.contact-reply', compact('messages'));
    }
}