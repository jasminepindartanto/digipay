<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRegistration;
use App\Models\StudentPackage;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;    

class RegistrationController extends Controller
{
    // FORM
    public function create()
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        return view('registration.create');
    }

    public function createAdmin()
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        $lastStudent = Student::orderBy('id', 'desc')->first();

        $nextNumber = $lastStudent
            ? $lastStudent->id + 1
            : 1;

        $registrationNumber =
            'Digikidz - ' .
            str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        $alumni = Student::where('is_alumni', true)
            ->orderBy('name')
            ->get();

        return view(
            'registrations.create_admin',
            compact('registrationNumber', 'alumni')
        );
    }

    public function storeAdmin(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'school' => 'required',
            'class' => 'required',
            'program' => 'required',
            'program_detail' => 'required',
            'package_type' => 'required',
            'registration_type' => 'required',
            'schedule_type' => 'required',
            'intensity' => 'required',
            'family_status' => 'required',
            'parent_phone' => 'required',
            'start_date' => 'required|date',
            'student_id' => 'nullable|exists:students,id',
        ]);
        if ($request->registration_type === 'Reactivation') {
            $request->validate([
                'student_id' => 'required|exists:students,id',
            ]);
        }

        StudentRegistration::create([

            'student_id' => $request->student_id,
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'school' => $request->school,
            'class' => $request->class,
            'program' => $request->program,
            'program_detail' => $request->program_detail,
            'package_type' => $request->package_type,
            'registration_type' => $request->registration_type,
            'schedule_type' => $request->schedule_type,
            'intensity' => $request->intensity,
            'family_status' => $request->family_status,
            'registration_date' => $request->registration_date,
            'start_date' => $request->start_date,
            'parent_phone' => $request->parent_phone,
            'parent_email' => $request->parent_email,
            'parent_instagram' => $request->parent_instagram,
            'child_phone' => $request->child_phone,
            'address' => $request->address,
            'source' => 'admin',
            'status' => 'pending',

        ]);

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Pendaftaran berhasil ditambahkan.');
    }
    // SIMPAN
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
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
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        $selectedDate = request(
            'date',
            now()->toDateString()
        );

        /*
        |--------------------------------------------------------------------------
        | Pending
        |--------------------------------------------------------------------------
        */

        $pending = StudentRegistration::where(
                'status',
                'pending'
            )
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Approved
        |--------------------------------------------------------------------------
        */

        $approved = StudentRegistration::with('student')
            ->where('status', 'approved')
            ->whereDate('approved_at', $selectedDate)
            ->latest('approved_at')
            ->get();

        return view(

            'registrations.index',

            compact(

                'pending',

                'approved',

                'selectedDate'

            )

        );
    }
    
    public function show($id)
    {
        $registration = StudentRegistration::findOrFail($id);

        $requiredFields = [
            'name',
            'gender',
            'date_of_birth',
            'school',
            'class',
            'address',
            'parent_phone',
            'program',
            'program_detail',
            'package_type',
            'schedule_type',
            'intensity',
            'family_status',
            'registration_date',
        ];

        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (blank($registration->$field)) {
                $missingFields[] = $field;
            }
        }

        $isComplete = count($missingFields) === 0;

        return view('registrations.show', compact(
            'registration',
            'isComplete',
            'missingFields'
        ));
    }
    public function edit($id)
    {
        $registration = StudentRegistration::findOrFail($id);

        return view(
            'registrations.edit',
            compact('registration')
        );
    }

    public function update(Request $request, $id)
    {
        $registration = StudentRegistration::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'school' => 'required',
            'class' => 'required',
            'program' => 'required',
            'program_detail' => 'required',
            'package_type' => 'required',
            'registration_type' => 'required',
            'schedule_type' => 'required',
            'intensity' => 'required',
            'family_status' => 'required',
            'parent_phone' => 'required',
        ]);

        $registration->update($request->all());

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function approve($id)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $reg = StudentRegistration::findOrFail($id);
        $requiredFields = [
            'name',
            'gender',
            'date_of_birth',
            'school',
            'class',
            'address',
            'parent_phone',
            'program',
            'program_detail',
            'package_type',
            'schedule_type',
            'intensity',
            'family_status',
            'registration_date',
        ];

        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($reg->$field)) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return back()->with(
                'error',
                'Data belum lengkap. Lengkapi data terlebih dahulu sebelum disetujui.'
            );
        }

        $lastStudent = Student::orderBy('id', 'desc')->first();

        $nextNumber = $lastStudent
            ? $lastStudent->id + 1
            : 1;

        $registrationNumber =
            'Digikidz - ' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

    /*
|--------------------------------------------------------------------------
| Estimated End Date
|--------------------------------------------------------------------------
*/

        $startDate = \Carbon\Carbon::parse($reg->start_date);

        $estimatedEndDate = match ($reg->package_type) {

            'Monthly'     => $startDate->copy()->addWeeks(3),

            '1 Level'     => $startDate->copy()->addWeeks(15),

            'Full Course' => $startDate->copy()->addWeeks(47),

            default       => $startDate->copy()->addWeeks(3),

        };

        // --------------------------------------------------------------------------
        // BUAT / AKTIFKAN KEMBALI SISWA
        // --------------------------------------------------------------------------

        if ($reg->registration_type === 'Reactivation') {

            // Pastikan pendaftaran Reactivation memiliki siswa alumni
            if (!$reg->student_id) {
                return back()->with(
                    'error',
                    'Siswa alumni belum dipilih untuk Reactivation.'
                );
            }

            // Ambil siswa lama
            $student = Student::findOrFail($reg->student_id);

            // Update data siswa lama dengan data pendaftaran terbaru
            $student->update([
                'registration_date' => $reg->registration_date ?? now(),
                'start_date' => $startDate,
                'estimated_end_date' => $estimatedEndDate,
                'completed_date' => null,

                'name' => $reg->name,
                'gender' => $reg->gender,
                'date_of_birth' => $reg->date_of_birth,
                'school' => $reg->school,
                'class' => $reg->class,
                'parent_phone' => $reg->parent_phone,
                'address' => $reg->address,

                'student_status' => 'Active',
                'status' => 'Pending',

                'program' => $reg->program,
                'program_detail' => $reg->program_detail,
                'package_type' => $reg->package_type,
                'schedule_type' => $reg->schedule_type,
                'intensity' => $reg->intensity,
                'family_status' => $reg->family_status,

                'registration_type' => 'Reactivation',
                'is_alumni' => false,
            ]);

            $student->refresh();

        } else {

            // ----------------------------------------------------------------------
            // NEW STUDENT
            // ----------------------------------------------------------------------

            $student = Student::create([

                'registration_number' => $registrationNumber,

                'registration_date' => $reg->registration_date ?? now(),

                'start_date' => $startDate,

                'estimated_end_date' => $estimatedEndDate,

                'student_status' => 'Active',

                'completed_date' => null,

                'name' => $reg->name,

                'gender' => $reg->gender,

                'date_of_birth' => $reg->date_of_birth,

                'school' => $reg->school,

                'class' => $reg->class,

                'parent_phone' => $reg->parent_phone,

                'address' => $reg->address,

                'status' => 'Pending',

                'program' => $reg->program,

                'program_detail' => $reg->program_detail,

                'package_type' => $reg->package_type,

                'schedule_type' => $reg->schedule_type,

                'intensity' => $reg->intensity,

                'family_status' => $reg->family_status,

                'registration_type' => $reg->registration_type,

            ]);
        }
    /*
|--------------------------------------------------------------------------
| Total Session
|--------------------------------------------------------------------------
*/

        $totalSessions = match ($student->package_type) {

            'Monthly'    => 4,

            '1 Level'    => 16,

            'Full Course'=> 48,

            default      => 4,

        };

        // Jika Reactivation, nonaktifkan package lama
        if ($reg->registration_type === 'Reactivation') {

            StudentPackage::where('student_id', $student->id)
                ->where('active', true)
                ->update([
                    'active' => false
                ]);
        }

/*
|--------------------------------------------------------------------------
| Create Active Package
|--------------------------------------------------------------------------
*/

        $activePackage = StudentPackage::create([

            'student_id'         => $student->id,

            'package_type'       => $student->package_type,

            'program_detail'     => $student->program_detail,

            'start_date'         => $student->start_date,

            'estimated_end_date' => $student->estimated_end_date,

            'total_sessions'     => $totalSessions,

            'active'             => true,

        ]);

                /*
        |--------------------------------------------------------------------------
        | Package Price
        |--------------------------------------------------------------------------
        */

        $packagePrice = config(
            'pricing.packages.'
            .
            $student->package_type
            .
            '.'
            .
            $student->program_detail
        );

        if(!$packagePrice){
            return back()->with(
                'error',
                'Harga paket tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Membership Fee
        |--------------------------------------------------------------------------
        */

        $membershipFee = config('pricing.membership_fee');
        $membershipStatus = 'Membership';
        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        $discount = 0;

        /*
        |--------------------------------------------------------------------------
        | Renewal-Reactivation
        |--------------------------------------------------------------------------
        */

        if (
            $student->registration_type === 'Renewal'
            ||
            $student->registration_type === 'Reactivation'
        ) {

            $membershipFee = 0;

            $membershipStatus = 'free';
        }

        /*
        |--------------------------------------------------------------------------
        | Full Course Free Membership
        |--------------------------------------------------------------------------
        */

        if ($student->package_type == 'Full Course') {

            $membershipFee = 0;

            $membershipStatus = 'included';
        }

        /*
        |--------------------------------------------------------------------------
        | Sibling Discount
        |--------------------------------------------------------------------------
        */

        if($student->registration_type == 'New' && $student->family_status == 'Family')
            {
            $discount = $packagePrice * 0.10;
            }

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $totalPayment = $packagePrice + $membershipFee - $discount;
        // BUAT PAYMENT PERTAMA
        Payment::create([
            'student_id' => $student->id,

            'student_package_id' => $activePackage->id,

            'payment_date' => null,

            'due_date' => $startDate,

            'paid_for_month' => $startDate->format('F Y'),

            'amount_due' => $totalPayment,

            'amount_paid' => 0,

            'package_price' => $packagePrice,

            'membership_fee' => $membershipFee,

            'membership_status' => $membershipStatus,

            'discount_amount' => $discount,

            'payment_method' => null,

            'payment_group' => 'SF',

            'schedule_type' => $student->schedule_type,

            'class_type' => $student->intensity,

            'family_type' => $student->family_status,

            'status' => 'Belum Bayar',

            'paid_flag' => false,

        ]);

        // UPDATE STATUS
        $reg->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pendaftaran berhasil diapprove');
    }

}