<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Payment;
use Carbon\Carbon;
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
    
    $startDate = Carbon::parse(
    $request->start_date ?? now()
    );

    switch ($request->package_type) {

        case 'Monthly':

            $estimatedEndDate =
                now()->copy()->addWeeks(4);

            break;

        case '1 Level':

            $estimatedEndDate =
                now()->copy()->addWeeks(16);

            break;

        case 'Full Course':

            $estimatedEndDate =
                now()->copy()->addWeeks(48);

            break;

        default:

            $estimatedEndDate =
                now()->copy()->addWeeks(4);

    }
    $student = Student::create([
        'name' => $request->name,
        'registration_number' => $registrationNumber,
        'student_status' => 'Active',
        'completed_date' => null,
        'program' => $request->program,
        'program_detail' => $request->program_detail,
        'package_type' => $request->package_type,
        'start_date' => $startDate,
        'estimated_end_date' => $estimatedEndDate,
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

    $amountDue = match ($student->program_detail) {

    'Little Creator 1' => 500000,
    'Little Creator 2' => 500000,

    'Junior 1' => 575000,
    'Junior 2' => 575000,

    'Teenager 1' => 650000,
    'Teenager 2' => 650000,
    'Teenager 3' => 650000,
    'Teenager 4' => 650000,
    };

    Payment::create([
        'student_id'      => $student->id,
        'payment_date'    => now(),
        'paid_for_month'  => Carbon::now()->format('F Y'),

        'amount_due'      => $amountDue,
        'amount_paid'     => 0,

        'payment_method'  => null,

        'payment_group'   => 'SF',
        'schedule_type'   => $student->schedule_type,
        'class_type'      => $student->intensity,
        'family_type'     => $student->family_status,

        'status'          => 'Belum Bayar',
        'paid_flag'       => false,
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
        ]);

        $student = Student::findOrFail($id);

        $data = $request->all();

        // Status siswa
        if (
            in_array(
                $request->status,
                ['Completed', 'Drop Out']
            )
        ) {
            $data['completed_date'] = now();
        }

        if ($request->status == 'Active') {
            $data['completed_date'] = null;
        }

        // Hitung ulang estimasi selesai
        if ($request->filled('start_date')) {

            $startDate = Carbon::parse($request->start_date);

            switch ($student->package_type) {

                case 'Monthly':
                    $data['estimated_end_date'] =
                        $startDate->copy()->addWeeks(3);
                    break;

                case '1 Level':
                    $data['estimated_end_date'] =
                        $startDate->copy()->addWeeks(15);
                    break;

                case 'Full Course':
                    $data['estimated_end_date'] =
                        $startDate->copy()->addWeeks(47);
                    break;
            }
        }

        $student->update($data);

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                'Data siswa berhasil diupdate'
            );
    }

    // 🔹 Hapus data
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // hapus seluruh pembayaran siswa
        $student->payments()->delete();

        // hapus siswa
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