<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class PaymentController extends Controller
{
    // 🔹 Tampilkan semua pembayaran
    public function index(Request $request)
    {
        $query = Payment::with('student');

        // SEARCH
        if ($request->search) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {

            $query->where('status', $request->status);

        }

        // FILTER PROGRAM
        if ($request->program) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('program', $request->program);
            });
        }

        // FILTER TANGGAL
        if ($request->tanggal) {

            $query->whereDate(
                'payment_date',
                $request->tanggal
            );
        }

        $payments = $query->latest()->get();

        // AMBIL LIST PROGRAM UNTUK DROPDOWN
        $programs = Student::select('program')
            ->distinct()
            ->pluck('program');

        return view('payments.index', compact(
            'payments',
            'programs'
        ));
    }

    // 🔹 Form tambah pembayaran
    public function create(Request $request)
    {
        $student = null;

        if ($request->student_id) {
            $student = Student::findOrFail($request->student_id);
        }

        $students = Student::all();

        return view('payments.create', compact('student', 'students'));
    }

    // 🔹 Simpan pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'amount_due' => 'required',
            'amount_paid' => 'required'
        ]);

        // logic status otomatis
        if ($request->amount_paid == 0) {

            $status = 'Belum Bayar';

        } elseif ($request->amount_paid < $request->amount_due) {

            $status = 'Cicilan';

        } else {

            $status = 'Lunas';

        }
        $existingPayment = Payment::where('student_id', $request->student_id)->exists();
        $amountDue = $existingPayment
            ? 0
            : $request->amount_due;
        Payment::create([
            'student_id' => $request->student_id,
            'receipt_number' => $request->receipt_number,
            'payment_date' => $request->payment_date,
            'paid_for_month' => $request->paid_for_month,
            'amount_due' => $amountDue,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_group' => $request->payment_group,
            'schedule_type' => $request->schedule_type,
            'class_type' => $request->class_type,
            'family_type' => $request->family_type,
            'status' => $status,
            'paid_flag' => $status === 'paid' ? 1 : 0
        ]);

        return redirect()->route('payments.index')
                         ->with('success', 'Pembayaran berhasil ditambahkan');
    }

    // 🔹 Form edit
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $students = Student::all();

        return view('payments.edit', compact('payment', 'students'));
    }

    // 🔹 Update pembayaran
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

                if ($request->amount_paid == 0) {

            $status = 'Belum Bayar';

        } elseif ($request->amount_paid < $request->amount_due) {

            $status = 'Cicilan';

        } else {

            $status = 'Lunas';

        }

        $payment->update([
            'student_id' => $request->student_id,
            'amount_due' => $request->amount_due,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'paid_flag' => $status === 'paid' ? 1 : 0
        ]);

        return redirect()->route('payments.index')
                         ->with('success', 'Pembayaran berhasil diupdate');
    }

    // 🔹 Hapus pembayaran
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')
                         ->with('success', 'Pembayaran berhasil dihapus');
    }

    public function receipt($id)
    {
        $payment = Payment::with('student')->findOrFail($id);

        return view('payments.receipt', compact('payment'));
    }

    public function generateMonthlyBills()
    {
        $students = Student::where('status', 'Active')->get();

        foreach ($students as $student) {

            $bulan = Carbon::now()->format('F Y');

            // CEK SUDAH ADA TAGIHAN BULAN INI BELUM
            $exists = Payment::where('student_id', $student->id)
                ->where('paid_for_month', $bulan)
                ->exists();

            if ($exists) {
                continue;
            }

            // TENTUKAN NOMINAL
            $amount = match ($student->program_detail) {

                'Little Creator 1' => 500000,
                'Little Creator 2' => 500000,

                'Junior 1' => 575000,
                'Junior 2' => 575000,

                default => 650000
            };

            // BUAT TAGIHAN BARU
            Payment::create([

                'student_id' => $student->id,

                'payment_date' => now(),

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
        $query = Payment::with('student');

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
        $payments = Payment::with('student');

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