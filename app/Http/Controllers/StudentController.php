<?php

namespace App\Http\Controllers;

use App\Models\Student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    // 🔹 Tampilkan semua data
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    // 🔹 Form tambah
    public function create()
    {
        return view('students.create');
    }

    // 🔹 Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'registration_number' => 'required'
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')
                         ->with('success', 'Data siswa berhasil ditambahkan');
    }

    // 🔹 Form edit
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // 🔹 Update data
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $student->update($request->all());

        return redirect()->route('students.index')
                         ->with('success', 'Data siswa berhasil diupdate');
    }

    // 🔹 Hapus data
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
                         ->with('success', 'Data siswa berhasil dihapus');
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }
}