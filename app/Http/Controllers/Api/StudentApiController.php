<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

class StudentApiController extends Controller
{
    public function index()
    {
        $students = Student::all();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function show($id)
    {
        $student = Student::with('payments')->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }
}