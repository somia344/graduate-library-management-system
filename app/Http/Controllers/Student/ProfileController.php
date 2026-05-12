<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile', compact('student'));
    }
    
public function update(Request $request)
{
    $student = auth()->guard('student')->user();
    
    $request->validate([
        'full_name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'department' => 'nullable|string',
        'registration_no' => 'nullable|string',
        'address' => 'required|string',
    ]);
    
    $student->update([
        'full_name' => $request->full_name,
        'father_name' => $request->father_name,
        'phone_number' => $request->phone_number,
        'department' => $request->department,
        'registration_no' => $request->registration_no,
        'address' => $request->address,
    ]);
    
    return redirect()->back()->with('success', 'Profile updated successfully!');
}
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);
        
        $student = Auth::guard('student')->user();
        
        // Check current password
        if (!Hash::check($request->current_password, $student->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }
        
        // Validate password format
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $request->new_password)) {
            return redirect()->back()->with('error', 'Password must contain at least 1 uppercase, 1 number, and 1 special character');
        }
        
        $student->password = Hash::make($request->new_password);
        $student->save();
        
        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}