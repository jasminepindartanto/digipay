<?php

namespace App\Http\Controllers;

use App\Models\Student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    // 🔹 Tampilkan semua data
    public function index(Request $request)
    {
        $query = Student::with('payments');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('registration_number', 'like', '%' . $request->search . '%');
        }

        $students = $query->orderBy('registration_number', 'asc')->get();

        return view('students.index', compact('students'));
    }

    // 🔹 Form tambah
    public function create()
    {
        $lastStudent = Student::orderBy('id', 'desc')->first();
        $nextNumber = $lastStudent
            ? $lastStudent->id + 1
            : 1;

        $registrationNumber = 'Digikidz - ' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        return view('students.create', compact('registrationNumber'));
    }

    // 🔹 Simpan data
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'program' => 'required',
        'program_detail' => 'required',
    ]);

    $lastStudent = Student::orderBy('id', 'desc')->first();
    $nextNumber = $lastStudent
        ? $lastStudent->id + 1
        : 1;
    $registrationNumber = 'Digikidz - ' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    
    Student::create([
        'name' => $request->name,
        'registration_number' => $registrationNumber,
        'program' => $request->program,
        'program_detail' => $request->program_detail,
        'schedule_type' => $request->schedule_type,
        'intensity' => $request->intensity,
        'family_status' => $request->family_status,
        'gender' => $request->gender,
        'parent_phone' => $request->parent_phone,
        'school' => $request->school,
        'registration_date' => now(),
        'date_of_birth' => $request->date_of_birth,
        'status' => $request->status,
        'class' => $request->class,
        'address' => $request->address,
        'child_phone' => $request->child_phone,
        'parent_email' => $request->parent_email,
        'parent_instagram' => $request->parent_instagram,
    ]);

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
        $request->validate([
            'name' => 'required',
            //'registration_number' => 'required'
        ]);

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
        $student = Student::with('payments')->findOrFail($id);

        return view('students.show', compact('student'));
    }
}