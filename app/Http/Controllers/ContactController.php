<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Show the contact form page
     */
    public function index()
    {
        return view('contact.index');
    }
    
    /**
     * Send contact message
     */
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ]);
        
        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread'
        ]);
        
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}