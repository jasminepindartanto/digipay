<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRegistration;
use App\Models\Student;
use App\Models\Payment;

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

    public function approve(Request $request, $id)
    {
        $reg = StudentRegistration::findOrFail($id);

        $lastStudent = Student::orderBy('id', 'desc')->first();

        $nextNumber = $lastStudent
            ? $lastStudent->id + 1
            : 1;

        $registrationNumber =
            'Digikidz - ' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        // BUAT SISWA
        $student = Student::create([

            'registration_number' => $registrationNumber,

            'registration_date' => now()->toDateString(),
            
            'start_date' => now(),

            'student_status' => 'Active',

            'completed_date' => null,
            
            'name' => $reg->name,

            'gender' => $reg->gender,

            'date_of_birth' => $reg->date_of_birth,

            'school' => $reg->school,

            'class' => $reg->class,

            'parent_phone' => $reg->parent_phone,

            'address' => $reg->address,

            'status' => 'Active',

            // DARI ADMIN
            'program' => $request->program,

            'program_detail' => $request->program_detail,

            'schedule_type' => $request->schedule_type,

            'intensity' => $request->intensity,

            'family_status' => $request->family_status,

        ]);

        // AUTO TAGIHAN
        $amount = 0;

        if ($request->program_detail == 'Little Creator 1') {
            $amount = 500000;
        }

        elseif ($request->program_detail == 'Little Creator 2') {
            $amount = 500000;
        }

        elseif ($request->program_detail == 'Junior 1') {
            $amount = 575000;
        }

        elseif ($request->program_detail == 'Junior 2') {
            $amount = 575000;
        }

        else {
            $amount = 650000;
        }

        // BUAT PAYMENT PERTAMA
        Payment::create([

            'student_id' => $student->id,

            'payment_date' => now(),

            'paid_for_month' => now()->format('F Y'),

            'amount_due' => $amount,

            'amount_paid' => 0,

            'payment_method' => null,

            'paid_flag' => false

        ]);

        // UPDATE STATUS
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