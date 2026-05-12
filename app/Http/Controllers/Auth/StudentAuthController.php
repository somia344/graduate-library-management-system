<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }
    
    public function showRegisterForm()
    {
        return view('auth.student-register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone_number' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'class' => 'required|string',
            'roll_no' => 'required|string|unique:students,roll_no',
            'registration_no' => 'nullable|string|unique:students,registration_no,' . $request->id,
            'address' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Validate password format
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $request->password)) {
            return back()->withErrors([
                'password' => 'Password must contain at least 1 uppercase, 1 number, and 1 special character',
            ])->withInput();
        }
        
        // Create new student with all fields
        $student = Student::create([
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'department' => $request->department,        // Added
            'class' => $request->class,
            'roll_no' => $request->roll_no,
            'registration_no' => $request->registration_no,  // Added
            'address' => $request->address,
            'password' => Hash::make($request->password)
        ]);
        
        // Auto login after registration
        Auth::guard('student')->login($student);
        
        // Regenerate session for security
        $request->session()->regenerate();
        
        // Redirect to dashboard with success message
        return redirect()->route('student.dashboard')->with('success', 'Welcome ' . $student->full_name . '! Your account has been created successfully.');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        
        if (Auth::guard('student')->attempt($credentials, $remember)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();
            
            // Get the logged in student
            $student = Auth::guard('student')->user();
            
            // Redirect to dashboard with welcome message
            return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back ' . $student->full_name . '!');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
    
    public function showForgotForm()
    {
        return view('auth.forgot-password', ['role' => 'student']);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email'
        ]);

        $token = \Illuminate\Support\Str::random(60);
        
        // Store token in database
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetLink = url('/student/password/reset/' . $token . '?email=' . urlencode($request->email));

        // Find student name
        $student = Student::where('email', $request->email)->first();
        $studentName = $student ? $student->full_name : 'Student';

        // Send email using Laravel Mail
        try {
            Mail::send('emails.reset-password', [
                'resetLink' => $resetLink,
                'name' => $studentName
            ], function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Reset Your Password - Graduate Library');
            });

            return redirect()->back()->with('success', 'Password reset link has been sent to your email!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email. Please try again.');
        }
    }
    
    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'role' => 'student'
        ]);
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:students,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Validate password format
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $request->password)) {
            return back()->withErrors([
                'password' => 'Password must contain at least 1 uppercase, 1 number, and 1 special character',
            ])->withInput();
        }
        
        // Check token
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        
        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.']);
        }
        
        // Check if token is not expired (60 minutes)
        $tokenCreatedAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($tokenCreatedAt->diffInMinutes(now()) > 60) {
            return back()->withErrors(['email' => 'This password reset link has expired.']);
        }
        
        // Update password
        $student = Student::where('email', $request->email)->first();
        $student->password = Hash::make($request->password);
        $student->save();
        
        // Delete token
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return redirect()->route('student.login')->with('success', 'Password has been reset successfully! Please login with your new password.');
    }
    
    // Dashboard method
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        return view('student.dashboard', compact('student'));
    }
}