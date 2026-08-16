<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Payment;
use App\Models\StudentPackage;
use App\Models\LearningSession;
use App\Services\ForecastService;
use App\Exports\StudentExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // 🔹 Tampilkan semua data
    public function index(Request $request)
    {
        $query = Student::with([
            'payments',
            'activePackage'
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('registration_number', 'like', '%' . $request->search . '%');

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Program
        |--------------------------------------------------------------------------
        */

            if ($request->filled('level')) {

            $query->where('program_detail', $request->level);

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where('status', $request->status);

        }

        /*
|--------------------------------------------------------------------------
| Filter Package
|--------------------------------------------------------------------------
*/

        if ($request->filled('package_type')) {

            $query->where(
                'package_type',
                $request->package_type
            );

        }
        /*
        |--------------------------------------------------------------------------
        | Show Per Page
        |--------------------------------------------------------------------------
        */

        $perPage = $request->per_page ?? 10;

        $students = $query
            ->where('is_alumni', false)
            ->orderByDesc('id')
            ->get();

        if ($request->filled('package_status')) {

            $students = $students->filter(function ($student) use ($request) {

                $remaining = $student->remaining_sessions;

                return match ($request->package_status) {

                    'warning' => $student->status === 'Active'
                        && $remaining <= 2
                        && $remaining > 0,

                    'finished' => $student->status === 'Inactive',

                    'normal' => $student->status === 'Active'
                        && $remaining > 2,

                    default => true,

                };

            })->values();

        }
                /*
        |--------------------------------------------------------------------------
        | Filter Pembayaran
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment')) {

            $students = $students
                ->filter(function ($student) use ($request) {

                    return $student->status_pembayaran === $request->payment;

                })
                ->values();
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $students = new LengthAwarePaginator(

            $students->forPage($currentPage, $perPage),

            $students->count(),

            $perPage,

            $currentPage,

            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]

        );
        
        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalStudents = Student::where('is_alumni', false)
            ->whereIn('status', ['Pending', 'Active'])
            ->count();

        $activeStudents = Student::where('status', 'Active')->count();

        $unpaidStudents = Student::with('activePackage.payments')
            ->whereIn('status', ['Pending', 'Active'])
            ->get()
            ->filter(fn($student) => $student->status_pembayaran === 'Belum Bayar')
            ->count();

        $programCount = Student::distinct()->count('program');
        /*
        |--------------------------------------------------------------------------
        | Dropdown Program
        |--------------------------------------------------------------------------
        */

        $levels = collect([
            'Little Creator 1',
            'Little Creator 2',
            'Junior 1',
            'Junior 2',
            'Teenager 1',
            'Teenager 2',
            'Teenager 3',
            'Teenager 4',
        ]);

        return view('students.index', compact(
            'students',
            'levels',
            'activeStudents',
            'totalStudents',
            'unpaidStudents',
            'programCount'
        ));
    }

    
    // 🔹 Form tambah
    public function create()
    {
        if (Auth::user()->role === 'tutor') {
            abort(403, 'Tutor tidak memiliki akses.');
        }

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
    if (Auth::user() && Auth::user()->role === 'tutor') {
        abort(403);
    }
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

    $packageType = $request->package_type ?? 'Monthly';

    switch ($request->package_type) {

        case 'Monthly':

            $estimatedEndDate =
                $startDate->copy()->addWeeks(3);

            break;

        case '1 Level':

            $estimatedEndDate =
                $startDate->copy()->addWeeks(15);

            break;

        case 'Full Course':

            $estimatedEndDate =
                $startDate->copy()->addWeeks(47);

            break;

        default:

            $estimatedEndDate =
                $startDate->copy()->addWeeks(3);
    }
    $student = Student::create([
        'name' => $request->name,
        'registration_number' => $registrationNumber,
        'student_status' => 'Active',
        'completed_date' => null,
        'program' => $request->program,
        'program_detail' => $request->program_detail,
        'package_type' => $request->package_type,
        #'current_session' => 0,
        'start_date' => $startDate,
        'estimated_end_date' => $estimatedEndDate,
        'schedule_type' => $request->schedule_type,
        'intensity' => $request->intensity,
        'family_status' => $request->family_status,
        'registration_type' => $request->registration_type,
        'gender' => $request->gender,
        'parent_phone' => $request->parent_phone,
        'school' => $request->school,
        'registration_date' => now(),
        'date_of_birth' => $request->date_of_birth,
        'status' => 'Pending',
        'class' => $request->class,
        'address' => $request->address,
        'child_phone' => $request->child_phone,
        'parent_email' => $request->parent_email,
        'parent_instagram' => $request->parent_instagram,
    ]);

    $totalSessions = match ($student->package_type) {

        'Monthly' => 4,
        '1 Level' => 16,
        'Full Course' => 48,
        default => 4,
    };

    $newPackage = StudentPackage::create([

        'student_id' => $student->id,

        'package_type' => $student->package_type,

        'program_detail' => $student->program_detail,

        'start_date' => $student->start_date,

        'estimated_end_date' => $student->estimated_end_date,

        'total_sessions' => $totalSessions,

        'active' => true,

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

        if (!$packagePrice) {

            return back()->with(
                'error',
                'Harga package tidak ditemukan.'
            );
        }

        $membershipFee = 0;

        $membershipStatus = 'Membership';

        $discount = 0;

        $totalPayment = $packagePrice;
/*
|--------------------------------------------------------------------------
| Membership Fee
|--------------------------------------------------------------------------
*/

    if ($student->registration_type == 'Renewal') {

        $membershipFee = 0;

        $membershipStatus = 'Bebas Biaya Membership';

    }
    elseif ($student->package_type == 'Full Course') {

        $membershipFee = 0;

        $membershipStatus = 'Membership Gratis';

    }
    else {

        $membershipFee = config('pricing.membership_fee');

        $membershipStatus = 'Membership';

    }
    /*
|--------------------------------------------------------------------------
| Sibling Discount
|--------------------------------------------------------------------------
*/

    if($student->registration_type == 'New' && $student->family_status == 'Family')
        { $discount = $packagePrice * 0.10;}

    $totalPayment = $packagePrice + $membershipFee - $discount;
    
    Payment::create([
        'student_id'      => $student->id,
        'student_package_id' => $newPackage->id,
        'payment_date'      => null,
        'due_date'        => $startDate,
        'paid_for_month'  => Carbon::now()->format('F Y'),
        'amount_due'      => $totalPayment,
        'amount_paid'     => 0,
        'payment_method'  => null,
        'payment_group'   => 'SF',
        'schedule_type'   => $student->schedule_type,
        'class_type'      => $student->intensity,
        'family_type'     => $student->family_status,
        'status'          => 'Belum Bayar',
        'paid_flag'       => false,
        'package_price'   => $packagePrice,
        'membership_fee'  => $membershipFee,
        'membership_status' => $membershipStatus,
        'discount_amount' => $discount,
    ]);

    return redirect()->route('students.index')
                     ->with('success', 'Data siswa berhasil ditambahkan');
    }


    // 🔹 Form edit
    public function edit($id)
    {
        if (Auth::user() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // 🔹 Update data
   public function update(Request $request, $id)
    {
            if (Auth::user() && Auth::user()->role === 'tutor') {
                abort(403);
            }
        $request->validate([
            'name' => 'required',
        ]);

        $student = Student::findOrFail($id);

        $data = $request->except([
            'status',
            'student_status',
        ]);

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
        ->route('students.show', $student->id)
        ->with(
            'success',
            'Data siswa berhasil diperbarui.'
        );
    }

    // 🔹 Hapus data
    public function destroy($id)
    {
      
        if (Auth::user() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $student = Student::findOrFail($id);

        // 1. Hapus seluruh pembayaran siswa
        $student->payments()->delete();

        // 2. Hapus seluruh sesi pembelajaran siswa
        $student->learningSessions()->delete();

        // 3. Hapus seluruh package siswa
        $student->packages()->delete();

        // 4. Hapus data siswa
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }

    public function show(Student $student)
    {
        $student->load([

        'payments.studentPackage',

        'packages' => function ($q) {
            $q->latest('start_date');
        },

        'activePackage.learningSessions' => function ($q) {
            $q->orderBy('session_no');
        },

        'activePackage.learningSessions.tutor',

    ]);

    $currentPackageId = optional($student->activePackage)->id;

    $currentPayments = $student->payments
        ->where('student_package_id', $currentPackageId);

    $student->total_tagihan = $currentPayments->sum('amount_due');

    $student->total_bayar = $currentPayments->sum('amount_paid');

    $student->sisa_tagihan =
        $student->total_tagihan
        -
        $student->total_bayar;


        return view('students.show', compact('student'));
    }

    public function packageHistory(Student $student)
    {
        $packages = $student->packages()
            ->with([
                'learningSessions.tutor'
            ])
            ->latest('start_date')
            ->paginate(5);

        return view(
            'students.package_history',
            compact(
                'student',
                'packages'
            )
        );
    }

    public function progress($id)
    {
        $student = Student::findOrFail($id);

        return view(
            'students.progress',
            compact('student')
        );
    }
    
    public function updateProgress($id)
    {
        return redirect()

            ->route('students.show',$id)

            ->with(

                'info',

                'Progress sekarang dihitung otomatis dari Learning Session.'

            );
    }

    public function summary(Student $student)
    {
        return response()->json([

            'name' => $student->name,

            'program' => $student->program,

            'program_detail' => $student->activePackage?->program_detail,

            'package' => $student->activePackage?->package_type,

            'completed' => $student->completed_sessions,

            'remaining' => $student->remaining_sessions,

            'progress' => $student->progress_percentage,

            'total' => $student->total_sessions,

            'current_session' => $student->current_session,

        ]);
    
    
        
    }

    public function renew(Request $request, Student $student,ForecastService $forecastService)
    {
        /*
        |--------------------------------------------------------------------------
        | Nonaktifkan package lama
        |--------------------------------------------------------------------------
        */

        $student->packages()
            ->where('active', true)
            ->update([
                'active' => false
            ]);

        /*
        |--------------------------------------------------------------------------
        | Hitung Package Baru
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $request->start_date
        );

        switch ($request->package_type){

            case 'Monthly':
                $estimatedEndDate = now()->addWeeks(3);
                $totalSessions = 4;
                break;

            case '1 Level':
                $estimatedEndDate = now()->addWeeks(15);
                $totalSessions = 16;
                break;

            default:
                $estimatedEndDate = now()->addWeeks(47);
                $totalSessions = 48;
        }

        /*
        |--------------------------------------------------------------------------
        | Buat Package Baru
        |--------------------------------------------------------------------------
        */

        $newPackage = StudentPackage::create([

            'student_id'         => $student->id,

            'package_type' => $request->package_type,

            'program_detail' => $request->program_detail,

            'start_date'         => $startDate,

            'estimated_end_date' => $estimatedEndDate,

            'total_sessions'     => $totalSessions,

            'active'             => true,

        ]);
            $packagePrice = config(
            'pricing.packages.'
            . $request->package_type
            . '.'
            . $request->program_detail
        );

        if (!$packagePrice) {
            return back()->with(
                'error',
                'Harga package tidak ditemukan.'
            );
        }

        $membershipFee = 0;
        $membershipStatus = 'Bebas Biaya Membership';
        $discount = 0;
        $totalPayment = $packagePrice;

        
        Payment::create([

            'student_id' => $student->id,

            'student_package_id' => $newPackage->id,

            'receipt_number' =>
                'INV-'
                .
                now()->format('Ymd')
                .
                '-'
                .
                str_pad(
                    Payment::count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),

            'payment_date' => null,

            'due_date' => $startDate,

            'paid_for_month' => now()->format('F Y'),

            'amount_due' => $totalPayment,

            'amount_paid' => 0,

            'package_price' => $packagePrice,

            'membership_fee' => $membershipFee,
            
            'membership_status' => $membershipStatus,

            'discount_amount' => 0,

            'payment_method' => null,

            'payment_group' => 'SF',

            'schedule_type' => $student->schedule_type,

            'class_type' => $student->intensity,

            'family_type' => $student->family_status,

            'status' => 'Belum Bayar',

            'paid_flag' => false,

        ]);
       $forecastService->generate($newPackage);

        /*
        |--------------------------------------------------------------------------
        | Reset Student
        |--------------------------------------------------------------------------
        */
        

        $student->update([

            'package_type' => $request->package_type,

            'program_detail' => $request->program_detail,

            'start_date' => $startDate,

            'estimated_end_date' => $estimatedEndDate,

            'student_status' => 'Active',

            'status' => 'Active',

            'completed_date' => null,

        ]);

        return back()->with(
            'success',
            'Package berhasil diperpanjang.'
        );
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
        new StudentExport($request),
        'Data-Siswa-' . now()->format('Y-m-d') . '.xlsx'
            );
    }
    public function exportPdf(Request $request)
{
        $query = Student::with([
            'payments',
            'activePackage'
        ])
        ->where('is_alumni', false);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'name',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'registration_number',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Level
        |--------------------------------------------------------------------------
        */

        if ($request->filled('level')) {
            $query->where(
                'program_detail',
                $request->level
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Paket
        |--------------------------------------------------------------------------
        */

        if ($request->filled('package_type')) {
            $query->where(
                'package_type',
                $request->package_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Data Siswa
        |--------------------------------------------------------------------------
        */

        $students = $query
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Status Pembayaran
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment')) {
            $students = $students
                ->filter(function ($student) use ($request) {

                    return $student->status_pembayaran
                        === $request->payment;

                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status Paket
        |--------------------------------------------------------------------------
        */

        if ($request->filled('package_status')) {

            $students = $students
                ->filter(function ($student) use ($request) {

                    $remaining = $student->remaining_sessions;

                    return match ($request->package_status) {

                        'warning' =>
                            $student->status === 'Active'
                            && $remaining <= 2
                            && $remaining > 0,

                        'finished' =>
                            $student->status === 'Inactive',

                        'normal' =>
                            $student->status === 'Active'
                            && $remaining > 2,

                        default => true,
                    };

                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Data Filter Untuk Ditampilkan di PDF
        |--------------------------------------------------------------------------
        */

        $filters = [
            'search' => $request->filled('search')
                ? $request->search
                : '-',

            'level' => $request->filled('level')
                ? $request->level
                : 'Semua Level',

            'status' => $request->filled('status')
                ? $request->status
                : 'Semua Status',

            'payment' => $request->filled('payment')
                ? $request->payment
                : 'Semua Pembayaran',

            'package_type' => $request->filled('package_type')
                ? $request->package_type
                : 'Semua Paket',
        ];

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'students.export-pdf',
            compact(
                'students',
                'filters'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Ukuran & Orientasi PDF
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'Laporan-Data-Siswa-' .
            now()->format('Y-m-d') .
            '.pdf'
        );
    }
    public function deactivate(Student $student)
    {
        $student->update([

        'status' => 'Inactive',

        'student_status' => 'Completed',

    ]);

        return back()->with(
            'success',
            'Status siswa berhasil diubah menjadi Inactive.'
        );
    }

    public function graduate(Student $student)
    {
        if ($student->is_alumni) {

            return back()->with(
                'error',
                'Siswa sudah menjadi alumni.'
            );

        }

        $student->update([

            'status' => 'Inactive',

            'student_status' => 'Completed',

            'completed_date' => now(),

            'is_alumni' => true,

        ]);

        return redirect()
            ->route('alumni.index')
            ->with(
                'success',
                'Siswa berhasil dipindahkan ke Data Alumni.'
            );
    }
}