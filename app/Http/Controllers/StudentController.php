<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // 1. GET: Display all students
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Student::all()
        ], 200);
    }

    // 2. POST: Create a student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_no' => 'required|string|max:20|unique:students,student_no',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'course' => 'required|string|max:50',
            'year_level' => 'required|integer|between:1,4',
            'email' => 'nullable|email|max:150',
        ]);

        $student = Student::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully.',
            'data' => $student
        ], 201);
    }

    // 3. GET: Display one student
    public function show(string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ], 200);
    }

    // 4. PUT: Update a student
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        $validated = $request->validate([
            'student_no' => [
                'sometimes', 'required', 'string', 'max:20',
                Rule::unique('students', 'student_no')->ignore($student->id)
            ],
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'course' => 'sometimes|required|string|max:50',
            'year_level' => 'sometimes|required|integer|between:1,4',
            'email' => 'sometimes|nullable|email|max:150',
        ]);

        $student->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
            'data' => $student
        ], 200);
    }

    // 5. DELETE: Remove a student
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully.'
        ], 200);
    }
}
