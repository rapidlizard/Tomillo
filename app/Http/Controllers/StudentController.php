<?php

namespace App\Http\Controllers;

use App\Http\Resources\Student as StudentResource;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function index()
    {
        $students = Student::all();
        return view('student.index', compact('students'));
    }

    public function getStudents()
    {
        $students = StudentResource::collection(Student::all());
        return $students;
    }

    public function getStudent(Student $student)
    {
        return $student;
    }

    public function create()
    {
        return view('student.create');
    }

    public function store(Request $request)
    {
        Student::create($request->all());
        return redirect('/students');
    }

    public function show(Student $student)
    {
        return view('student.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('student.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $student->update($request->all());
        $students = Student::all();
        return $students;
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return Student::all();
    }
}
