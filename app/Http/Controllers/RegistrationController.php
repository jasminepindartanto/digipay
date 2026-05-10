<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRegistration;
use App\Models\Student;

class RegistrationController extends Controller
{
    // FORM
    public function create()
    {
        return view('registration.create');
    }

    // SIMPAN
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'program' => 'required',
            'parent_phone' => 'required',
        ]);

        StudentRegistration::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'school' => $request->school,
            'class' => $request->class,
            'program' => $request->program,
            'parent_phone' => $request->parent_phone,
            'address' => $request->address,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pendaftaran berhasil dikirim');
    }

    public function index()
    {
        $registrations = StudentRegistration::where('status', 'pending')
            ->latest()
            ->get();

        $selectedDate = request('date', now()->toDateString());

        $processed = StudentRegistration::whereIn('status', ['approved', 'rejected'])
            ->whereDate('updated_at', $selectedDate)
            ->latest()
            ->get();

        return view('registrations.index', compact(
            'registrations',
            'processed',
            'selectedDate'
        ));
    }

    public function approve($id)
    {
        $reg = StudentRegistration::findOrFail($id);
        $lastStudent = Student::orderBy('id', 'desc')->first();
        $nextNumber = $lastStudent
            ? $lastStudent->id + 1
            : 1;
        $registrationNumber = 'Digikidz - ' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        $reg->update([
            'status' => 'approved'
    ]);
        $reg->update([
        'status' => 'rejected'
    ]);

        Student::create([
            'registration_number' => $registrationNumber,
            'registration_date' => $reg->created_at,
            'name' => $reg->name,
            'gender' => $reg->gender,
            'date_of_birth' => $reg->date_of_birth,
            'school' => $reg->school,
            'class' => $reg->class,
            'program' => $reg->program,
            'parent_phone' => $reg->parent_phone,
            'address' => $reg->address,
            'status' => 'pending',
        ]);

        $reg->update([
        'status' => 'approved'
        ]);

        return redirect()->back()
            ->with('success', 'Pendaftaran berhasil diapprove');
    }

    public function reject($id)
    {
        $reg = StudentRegistration::findOrFail($id);
        $reg->update([
            'status' => 'rejected'
        ]);

        return redirect()->back()
            ->with('success', 'Pendaftaran berhasil ditolak');
    }

}