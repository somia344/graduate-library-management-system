<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Librarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm($token, Request $request)
    {
        $email = $request->email;
        $role = $request->role ?? 'student';
        
        // Check which table to use based on role
        $table = $role == 'librarian' ? 'librarians' : 'students';
        
        // Check if token exists
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();
        
        if (!$resetRecord) {
            return redirect()->route($role . '.login')->with('error', 'Invalid reset link!');
        }
        
        return view('auth.reset-password', compact('token', 'email', 'role'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'role' => 'required|in:student,librarian',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $role = $request->role;
        
        // Check if email exists in respective table
        if ($role == 'librarian') {
            $request->validate(['email' => 'exists:librarians,email']);
            $user = Librarian::where('email', $request->email)->first();
        } else {
            $request->validate(['email' => 'exists:students,email']);
            $user = Student::where('email', $request->email)->first();
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->with('error', 'Invalid or expired reset link!');
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            return back()->with('error', 'Reset link has expired! Please request a new one.');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route($role . '.login')->with('success', 'Password reset successfully! Please login with your new password.');
    }
}