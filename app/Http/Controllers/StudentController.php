<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Field;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['field.campus', 'payments'])->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $fields = Field::with('campus')->get();
        return view('students.create', compact('fields'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email|unique:students',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:45',
            'parent_tel' => 'nullable|string',
            'field_id' => 'required|exists:fields,id',
            'username' => 'required|string|max:16|unique:users',
            'password' => 'required|min:6'
        ]);

        // Create user account
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        // Create student record
        $student = new Student([
            'fullName' => $validated['fullName'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'parent_tel' => $validated['parent_tel'],
            'field_id' => $validated['field_id']
        ]);

        $student->user()->associate($user);
        $student->save();

        return redirect()->route('students.index')
            ->with('success', 'Student registered successfully');
    }

    public function show(Student $student)
    {
        $student->load(['field.campus', 'payments']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $fields = Field::with('campus')->get();
        return view('students.edit', compact('student', 'fields'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'address' => 'required|string',
            'phone' => 'nullable|string|max:45',
            'parent_tel' => 'nullable|string',
            'field_id' => 'required|exists:fields,id'
        ]);

        $student->update($validated);
        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        $student->user->delete(); // This will cascade delete the student record
        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }
}
