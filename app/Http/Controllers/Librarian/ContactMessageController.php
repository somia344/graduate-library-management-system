<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('id', 'desc')->paginate(10);
        return view('librarian.contact-messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read if unread
        if ($message->status == 'unread') {
            $message->status = 'read';
            $message->save();
        }
        
        return view('librarian.contact-messages.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);
        
        $message = ContactMessage::findOrFail($id);
        $message->admin_reply = $request->reply;
        $message->status = 'replied';
        $message->save();
        
        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('librarian.contact-messages.index')
            ->with('success', 'Message deleted successfully!');
    }
}