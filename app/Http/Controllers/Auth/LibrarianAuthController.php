<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Librarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LibrarianAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.librarian-login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        
        $credentials = $request->only('email', 'password');
        
        // ⚠️ REMOVE YEH PASSWORD VALIDATION - Librarian ke liye nahi chahiye
        // Password format validation sirf student registration ke liye hai
        
        // Attempt to login
        if (Auth::guard('librarian')->attempt($credentials)) {
            // Session regenerate for security
            $request->session()->regenerate();
            
            // Direct redirect to librarian dashboard
            return redirect()->route('librarian.dashboard');
        }
        
        return back()->withErrors([
            'email' => 'Invalid credentials',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
{
    Auth::guard('librarian')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Redirect to home page with success message
    return redirect('/')->with('success', 'You have been logged out successfully!');
}
    public function showForgotForm()
    {
        return view('auth.forgot-password', ['role' => 'librarian']);
    }
    
  public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:librarians,email'
    ]);

    $token = \Illuminate\Support\Str::random(60);
    
    // Store token in database
    \DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $token, 'created_at' => now()]
    );

    $resetLink = url('/librarian/password/reset/' . $token . '?email=' . urlencode($request->email));

    // Send email using Laravel Mail (Brevo)
    try {
        \Mail::send('emails.reset-password', [
            'resetLink' => $resetLink,
            'name' => 'Librarian'
        ], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Your Password - Graduate Library (Librarian)');
        });

        return redirect()->back()->with('success', 'Password reset link has been sent to your email!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to send email. Please try again.');
    }
}
}