<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 🔹 Tampilkan semua pembayaran
    public function index()
    {
        $payments = Payment::with('student')->get();
        return view('payments.index', compact('payments'));
    }

    // 🔹 Form tambah pembayaran
    public function create()
    {
    $studentId = request('student_id');

    $student = null;

    if ($studentId) {
        $student = Student::find($studentId);
    }

    return view('payments.create', compact('student'));
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
        $status = $request->amount_paid >= $request->amount_due ? 'paid' : 'unpaid';

        Payment::create([
            'student_id' => $request->student_id,
            'receipt_number' => $request->receipt_number,
            'payment_date' => $request->payment_date,
            'amount_due' => $request->amount_due,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
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

        $status = $request->amount_paid >= $request->amount_due ? 'paid' : 'unpaid';

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
}