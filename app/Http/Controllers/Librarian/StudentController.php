<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('id', 'asc')->paginate(10);
        return view('librarian.manage-students.index', compact('students'));
    }

    public function create()
    {
        return view('librarian.manage-students.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'email' => 'required|email|unique:students',
        'phone_number' => 'required|string|max:20',
        'class' => 'required|string',
        'roll_no' => 'required|string|unique:students',
        'address' => 'required|string',
    ]);
    
    // Auto generate password
    $generatedPassword = Str::random(8); // e.g., "XyZ@1234"
    
    $student = Student::create([
        'full_name' => $request->full_name,
        'father_name' => $request->father_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'class' => $request->class,
        'roll_no' => $request->roll_no,
        'address' => $request->address,
        'password' => Hash::make($generatedPassword),
    ]);
    
    // Send email with password
    Mail::send('emails.student-credentials', [
        'name' => $student->full_name,
        'email' => $student->email,
        'password' => $generatedPassword,
        'login_url' => route('student.login')
    ], function ($message) use ($student) {
        $message->to($student->email)
                ->subject('Your Library Account Credentials');
    });
    
    return redirect()->route('librarian.students.index')
        ->with('success', 'Student added successfully! Login credentials sent to email.');
}

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('librarian.manage-students.show', compact('student'));
    }

public function edit($id)
{
    $student = Student::findOrFail($id);
    return view('librarian.manage-students.edit', compact('student'));  // ← YEH PATH
}
/**
 * Update the specified student in storage.
 */
public function update(Request $request, $id)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email,' . $id,
        'phone_number' => 'required|string|max:20',
        'class' => 'required|string',
        'roll_no' => 'required|string|unique:students,roll_no,' . $id,
        'address' => 'required|string'
    ]);

    $student = Student::findOrFail($id);
    $student->full_name = $request->full_name;
    $student->father_name = $request->father_name;
    $student->email = $request->email;
    $student->phone_number = $request->phone_number;
    $student->class = $request->class;
    $student->roll_no = $request->roll_no;
    $student->address = $request->address;
    
    if ($request->filled('password')) {
        $student->password = Hash::make($request->password);
    }
    
    $student->save();

    return redirect()->route('librarian.students.index')
        ->with('success', 'Student updated successfully!');
}

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('librarian.students.index')
            ->with('success', 'Student deleted successfully!');
    }
}