<?php

namespace App\Services;

use App\Models\Payment;
use Carbon\Carbon;

class OverdueBillService
{
    /**
     * Batalkan otomatis tagihan yang sudah melewati jatuh tempo
     * lebih dari masa tenggang (grace period) dan belum dibayar.
     *
     * @param  int  $graceDays  Jumlah hari setelah jatuh tempo sebelum tagihan dibatalkan
     * @return int  Jumlah tagihan yang dibatalkan
     */
    public function cancelOverdueBills(?int $graceDays = null): int
    {
        $graceDays = $graceDays ?? (int) config('billing.overdue_cancel_days', 3);

        $cutoff = Carbon::today()->subDays($graceDays);

        $payments = Payment::with('student')
            ->where('status', 'Belum Bayar')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $cutoff)
            ->get();

        $cancelled = 0;

        foreach ($payments as $payment) {

            $payment->update([
                'status' => 'Dibatalkan',
            ]);

            $cancelled++;

            /*
            |--------------------------------------------------------------------------
            | Reaktivasi Alumni Gagal
            |--------------------------------------------------------------------------
            | Kalau tagihan reaktivasi alumni lewat batas dan dibatalkan,
            | kembalikan siswa ke data alumni supaya tidak tersangkut
            | di data siswa sebagai status Pending.
            */

            $student = $payment->student;

            if (
                $student
                && $student->registration_type === 'Reactivation'
                && $student->status === 'Pending'
            ) {

                // Nonaktifkan package baru yang dibuat saat reaktivasi
                $student->packages()
                    ->where('active', true)
                    ->update([
                        'active' => false,
                    ]);

                $student->update([
                    'is_alumni' => true,
                    'status' => 'Inactive',
                    'student_status' => 'Completed',
                ]);

            }
        }

        return $cancelled;
    }
}
