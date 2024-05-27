<?php

// app/Http/Controllers/StudentController.php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }
        if ($request->has('course')) {
            $query->where('course', $request->course);
        }
        if ($request->has('section')) {
            $query->where('section', $request->section);
        }

        // Sorting
        if ($request->has('sort')) {
            $sort = explode(',', $request->sort);
            foreach ($sort as $sortOption) {
                $sortParts = explode(':', $sortOption);
                $query->orderBy($sortParts[0], $sortParts[1] ?? 'asc');
            }
        }

        // Pagination
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        $students = $query->limit($limit)->offset($offset)->get();

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'birthdate' => 'required|date_format:Y-m-d',
            'sex' => 'required|in:MALE,FEMALE',
            'address' => 'required|string',
            'year' => 'required|integer',
            'course' => 'required|string',
            'section' => 'required|string',
        ]);

        $student = Student::create($request->all());
        return response()->json($student, 201);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'firstname' => 'sometimes|required|string',
            'lastname' => 'sometimes|required|string',
            'birthdate' => 'sometimes|required|date_format:Y-m-d',
            'sex' => 'sometimes|required|in:MALE,FEMALE',
            'address' => 'sometimes|required|string',
            'year' => 'sometimes|required|integer',
            'course' => 'sometimes|required|string',
            'section' => 'sometimes|required|string',
        ]);

        $student->update($request->all());
        return response()->json($student);
    }
}
