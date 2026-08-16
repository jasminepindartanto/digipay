<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use App\Models\StudentPackage;
use Illuminate\Support\Facades\Auth;
use App\Services\OverdueBillService;


class PaymentController extends Controller
{
    // 🔹 Tampilkan semua pembayaran
    public function index(Request $request, OverdueBillService $overdueBillService)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        // Pembatalan otomatis tagihan kedaluwarsa dijalankan saat halaman dibuka,
        // supaya tetap berfungsi meski scheduler tidak berjalan terus-menerus.
        $overdueBillService->cancelOverdueBills();

        /*
        |--------------------------------------------------------------------------
        | Mode Debug (simulasi status Terlambat)
        |--------------------------------------------------------------------------
        | Dipakai untuk demo/uji coba, misalnya saat dosen ingin melihat
        | bentuk tagihan yang sudah terlambat. Aktifkan dengan ?debug=1,
        | opsional ?debug_date=YYYY-MM-DD untuk mensimulasikan "hari ini".
        */

        $debugMode = $request->boolean('debug');

        $debugDate = null;

        if ($debugMode && $request->filled('debug_date')) {
            try {
                $debugDate = Carbon::parse($request->debug_date);
            } catch (\Throwable $e) {
                $debugDate = null;
            }
        }

        $query = Payment::with([
            'student',
            'studentPackage',
        ])->whereHas('student', function ($q) {

            $q->where('is_alumni', false);

        });
        // Clone query supaya nanti bisa dipakai dua tab
        $activeQuery = clone $query;
        $historyQuery = clone $query;

        // Query tagihan dibatalkan (termasuk siswa alumni, supaya tercatat)
        $cancelledQuery = Payment::with([
            'student',
            'studentPackage',
        ]);

        // SEARCH
        if ($request->search) {

            $callback = function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('registration_number', 'like', '%' . $request->search . '%');

            };

            $activeQuery->whereHas('student', $callback);

            $historyQuery->whereHas('student', $callback);

            $cancelledQuery->whereHas('student', $callback);

        }

        if ($request->status) {

            if ($request->status) {

            $activeQuery->where('status', $request->status);

            $historyQuery->where('status', $request->status);

        }

        }

        // FILTER PROGRAM
        if ($request->level) {

            $callback = function ($q) use ($request) {

                $q->where('program_detail', $request->level);
            };

            $activeQuery->whereHas('student', $callback);

            $historyQuery->whereHas('student', $callback);

            $cancelledQuery->whereHas('student', $callback);

        }
        if ($request->package) {

            $activeQuery->where(function ($q) use ($request) {

                $q->whereHas('studentPackage', function ($qq) use ($request) {
                    $qq->where('package_type', $request->package);
                });

                $q->orWhere('renew_package_type', $request->package);

            });

            $historyQuery->where(function ($q) use ($request) {

                $q->whereHas('studentPackage', function ($qq) use ($request) {

                    $qq->where('package_type', $request->package);

                });

                $q->orWhere('renew_package_type', $request->package);

            });

            $cancelledQuery->where(function ($q) use ($request) {

                $q->whereHas('studentPackage', function ($qq) use ($request) {

                    $qq->where('package_type', $request->package);

                });

                $q->orWhere('renew_package_type', $request->package);

            });

        }

        // FILTER TANGGAL
        if ($request->tanggal) {

            $activeQuery->whereDate(
                'payment_date',
                $request->tanggal
            );

            $historyQuery->whereDate(
                'payment_date',
                $request->tanggal
            );

            $cancelledQuery->whereDate(
                'payment_date',
                $request->tanggal
            );

        }

        
        $activePayments = $activeQuery
            ->where('status', 'Belum Bayar')
            ->latest()
            ->paginate(10, ['*'], 'active_page')
            ->withQueryString();

        $paymentHistory = $historyQuery
            ->where('status', 'Lunas')
            ->latest()
            ->paginate(10, ['*'], 'history_page')
            ->withQueryString();

        $cancelledPayments = $cancelledQuery
            ->where('status', 'Dibatalkan')
            ->latest()
            ->paginate(10, ['*'], 'cancelled_page')
            ->withQueryString();

        // AMBIL LIST PROGRAM UNTUK DROPDOWN
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
        
        $packages = collect([
            'Monthly',
            '1 Level',
            'Full Course',
        ]);

        return view('payments.index',
            compact(
                'activePayments',
                'paymentHistory',
                'cancelledPayments',
                'levels',
                'packages',
                'debugMode',
                'debugDate'
            )
        );
    }

    // 🔹 Form tambah pembayaran
    public function create(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        $student = null;
        $payment = null;

        if ($request->student_id) {

            $student = Student::findOrFail($request->student_id);

            $payment = Payment::with('studentPackage')
                ->where('student_id', $student->id)
                ->where('status', 'Belum Bayar')
                ->whereNull('payment_date')
                ->latest()
                ->first();

            if (!$payment) {

                $payment = Payment::with([
                    'student',
                    'studentPackage'
                ])
                    ->where('student_id', $student->id)
                    ->where('status', 'Belum Bayar')
                    ->latest()
                    ->first();

            }
        }

        $students = Student::where('is_alumni', false)->get();

        return view(
            'payments.create',
            compact(
                'student',
                'students',
                'payment'
            )
        );
    }

    public function getStudentBill(Student $student)
    {
        $payment = Payment::with('studentPackage')
            ->where('student_id', $student->id)
            ->where('status', 'Belum Bayar')
            ->whereNull('payment_date')
            ->latest('created_at')
            ->first();

        if (!$payment) {

            $payment = Payment::with('studentPackage')
                ->where('student_id', $student->id)
                ->where('status', 'Belum Bayar')
                ->latest()
                ->first();

        }

        if (!$payment) {

            return response()->json([
                'success' => false
            ]);

        }

        $renew = [

            'package_type' => $payment->renew_package_type,

            'program_detail' => $payment->renew_program_detail,

            'start_date' => $payment->renew_start_date,

        ];

            return response()->json([

                'success' => true,

                'invoice' => $payment->receipt_number,

                'package' => $payment->studentPackage
                    ? $payment->studentPackage->package_type
                    : ($renew['package_type'] ?? $student->package_type),

                'level' => $payment->studentPackage
                    ? $payment->studentPackage->program_detail
                    : ($renew['program_detail'] ?? $student->program_detail),

                'period' => $payment->paid_for_month
                    ?? ($renew['start_date'] ?? '-'),

                'amount_due' => $payment->amount_due,

                'payment_id' => $payment->id,

            ]);
    }

    // 🔹 Simpan pembayaran
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_id' => 'required|exists:payments,id',
            'amount_paid' => 'required|numeric',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // logic status otomatis
        $payment = Payment::findOrFail($request->payment_id);
        $status = $request->amount_paid >= $payment->amount_due
            ? 'Lunas'
            : 'Belum Bayar';
        $paymentProof = null;

            if ($request->hasFile('payment_proof')) {
                $paymentProof = $request->file('payment_proof')
                    ->store('payment_proofs', 'public');
            }

        $payment->update([

                'payment_date' => now(),

                'amount_paid' => $request->amount_paid,

                'payment_method' => $request->payment_method,

                'status' => $status,

                'paid_flag' => $status === 'Lunas',
                
                'payment_proof' => $paymentProof,

            ]);

        /*
|--------------------------------------------------------------------------
| Generate Receipt Number
|--------------------------------------------------------------------------
*/

        if ($status === 'Lunas' && empty($payment->receipt_number)) {

            $payment->receipt_number =
                'INV-'
                . now()->format('Ymd')
                . '-'
                . str_pad(
                    $payment->id,
                    5,
                    '0',
                    STR_PAD_LEFT
                );

            $payment->save();
        }

        if ($status === 'Lunas') {

            $student = Student::findOrFail($request->student_id);

            /*
            |--------------------------------------------------------------------------
            | Pembayaran Pertama
            |--------------------------------------------------------------------------
            */

            if ($student->status === 'Pending') {

                $student->update([
                    'status' => 'Active',
                ]);
                $student->refresh();
            }

            /*
            |--------------------------------------------------------------------------
            | Renew Package
            |--------------------------------------------------------------------------
            */

            

            if (

                $student->status === 'Inactive'

                &&

                $payment->renew_package_type

                ) {

                // Nonaktifkan package lama

                StudentPackage::where('student_id', $student->id)
                    ->where('active', true)
                    ->update([
                        'active' => false
                    ]);

                // Buat package baru

                $newPackage = StudentPackage::create([
                    

                    'student_id' => $student->id,

                    'package_type' => $payment->renew_package_type,

                    'program_detail' => $payment->renew_program_detail,

                    'start_date' => $payment->renew_start_date,

                    'estimated_end_date' => $payment->renew_estimated_end_date,

                    'total_sessions' => $payment->renew_total_sessions,

                    'active' => true,

                ]);

                // Hubungkan payment dengan package baru

                $payment->update([

                    'student_package_id' => $newPackage->id,

                ]);

                // Update data siswa

                // Update data siswa

                $student->update([

                    'status' => 'Active',
                    
                    'student_status' => 'Active',

                    'completed_date' => null,

                    'package_type' => $payment->renew_package_type,

                    'program_detail' => $payment->renew_program_detail,

                    'start_date' => $payment->renew_start_date,

                    'estimated_end_date' => $payment->renew_estimated_end_date,

                ]);

            }
        }
        return redirect()->route('payments.index')
                         ->with('success', 'Pembayaran berhasil ditambahkan');
    }

    // 🔹 Form edit
    public function edit($id)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $payment = Payment::with([
            'student',
            'studentPackage'
        ])->findOrFail($id);

        $students = Student::where('is_alumni', false)->get();

        return view('payments.edit', compact('payment', 'students'));
    }

    // 🔹 Update pembayaran
    public function update(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $payment = Payment::findOrFail($id);
                $status = $request->amount_paid >= $payment->amount_due
                    ? 'Lunas'
                    : 'Belum Bayar';
        
        $student = Student::findOrFail($request->student_id);
        $activePackage = $student->activePackage;
        
        $payment->update([
            'student_id' => $request->student_id,
            'student_package_id' => $activePackage->id,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_date' => $status === 'Lunas'
                ? now()
                : null,
            'status' => $status,
            'paid_flag' => $status === 'Lunas' ? 1 : 0
        ]);

        return redirect()->route('payments.index')
                         ->with('success', 'Pembayaran berhasil diupdate');
    }

    // 🔹 Hapus pembayaran
    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')
                         ->with('success', 'Pembayaran berhasil dihapus');
    }

    public function receipt(Payment $payment)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        $payment->load([
            'student',
            'studentPackage',
        ]);

        if ($payment->status !== 'Lunas') {
            return redirect()
                ->back()
                ->with('error', 'Receipt hanya tersedia untuk pembayaran yang sudah lunas.');
        }

        if (!$payment->receipt_number) {
            return back()->with('error', 'Receipt belum tersedia.');
        }
        return view('payments.receipt', compact('payment'));
        
    }

    public function show(Payment $payment)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
        }

        $payment->load([
            'student',
            'studentPackage',
        ]);

        return view(
            'payments.show',
            compact('payment')
        );
    }

    public function generateMonthlyBills()
    {
        $students = Student::where('status', 'Active')->get();

        foreach ($students as $student) {
            $activePackage = $student->activePackage;
            if (!$activePackage) {
                continue;
            }

            $bulan = Carbon::now()->format('F Y');

            // CEK SUDAH ADA TAGIHAN BULAN INI BELUM
            $exists = Payment::where('student_id', $student->id)
            ->where('student_package_id', $activePackage->id)

            ->where('paid_for_month', $bulan)

            ->where('status', 'Belum Bayar')

            ->exists();

            if ($exists) {
                continue;
            }

            // TENTUKAN NOMINAL
            $amount = config(
                'pricing.packages.'
                .
                $activePackage->package_type
                .
                '.'
                .
                $activePackage->program_detail
            );

            // BUAT TAGIHAN BARU
            Payment::create([

                'student_id' => $student->id,

                'student_package_id' => $activePackage->id,

                'payment_date' => null,

                'paid_for_month' => $bulan,

                'amount_due' => $amount,

                'amount_paid' => 0,

                'payment_method' => null,
                
                'status' => 'Belum Bayar',

                'paid_flag' => false
            ]);
        }

        return back()->with(
            'success',
            'Tagihan bulanan berhasil dibuat'
        );
    }
    
    public function export(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
            }
        $query = Payment::with([
            'student',
            'studentPackage'
        ])->whereHas('student', function ($q) {

            $q->where('is_alumni', false);

        });

        // SEARCH
        if ($request->search) {

            $query->whereHas('student', function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%');

            });
        }

        // STATUS
        if ($request->status) {

            $query->where('status', $request->status);

        }

        // TANGGAL
        if ($request->tanggal) {

            $query->whereDate('payment_date', $request->tanggal);

        }

        $payments = $query->latest()->get();

        return Excel::download(
            new PaymentsExport($payments),
            'payments.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'tutor') {
            abort(403);
            }
        $payments = Payment::with([
            'student',
            'studentPackage'
        ])->whereHas('student', function ($q) {

            $q->where('is_alumni', false);

        });

        // SEARCH
        if ($request->search) {

            $payments->whereHas('student', function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%');

            });
        }

        // STATUS
        if ($request->status) {

            $payments->where('status', $request->status);

        }

        // TANGGAL
        if ($request->tanggal) {

            $payments->whereDate('payment_date', $request->tanggal);

        }

        $payments = $payments->latest()->get();

        $pdf = Pdf::loadView('payments.pdf', compact('payments'));

        return $pdf->download('laporan-pembayaran.pdf');
    }
}