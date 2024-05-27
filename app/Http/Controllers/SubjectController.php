<?php

// app/Http/Controllers/SubjectController.php
namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request, $studentId)
    {
        $query = Subject::query();

        // Filtering
        if ($request->has('remarks')) {
            $query->where('remarks', $request->remarks);
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
        $subjects = $query->limit($limit)->offset($offset)->get();

        return response()->json($subjects);
    }

    public function store(Request $request, $studentId)
    {
        $request->validate([
            'subject_code' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'instructor' => 'required|string',
            'schedule' => 'required|string',
            'grades' => 'required|array',
            'grades.prelims' => 'required|numeric',
            'grades.midterms' => 'required|numeric',
            'grades.pre_finals' => 'required|numeric',
            'grades.finals' => 'required|numeric',
            'date_taken' => 'required|date_format:Y-m-d',
        ]);

        $grades = $request->grades;
        $average_grade = array_sum($grades) / count($grades);
        $remarks = $average_grade >= 3.0 ? 'PASSED' : 'FAILED';

        $subject = Subject::create([
            'subject_code' => $request->subject_code,
            'name' => $request->name,
            'description' => $request->description,
            'instructor' => $request->instructor,
            'schedule' => $request->schedule,
            'grades' => json_encode($grades),
            'average_grade' => $average_grade,
            'remarks' => $remarks,
            'date_taken' => $request->date_taken,
        ]);

        return response()->json($subject, 201);
    }

    public function show($studentId, $subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        return response()->json($subject);
    }

    public function update(Request $request, $studentId, $subjectId)
    {
        $subject = Subject::findOrFail($subjectId);

        $request->validate([
            'subject_code' => 'sometimes|required|string',
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
            'instructor' => 'sometimes|required|string',
            'schedule' => 'sometimes|required|string',
            'grades' => 'sometimes|required|array',
            'grades.prelims' => 'sometimes|required|numeric',
            'grades.midterms' => 'sometimes|required|numeric',
            'grades.pre_finals' => 'sometimes|required|numeric',
            'grades.finals' => 'sometimes|required|numeric',
            'date_taken' => 'sometimes|required|date_format:Y-m-d',
        ]);

        if ($request->has('grades')) {
            $grades = $request->grades;
            $average_grade = array_sum($grades) / count($grades);
            $remarks = $average_grade >= 3.0 ? 'PASSED' : 'FAILED';

            $subject->update([
                'grades' => json_encode($grades),
                'average_grade' => $average_grade,
                'remarks' => $remarks,
            ]);
        }

        $subject->update($request->except(['grades', 'average_grade', 'remarks']));

        return response()->json($subject);
    }
}
