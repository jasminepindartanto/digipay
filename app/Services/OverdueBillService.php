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

        return Payment::where('status', 'Belum Bayar')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $cutoff)
            ->update([
                'status' => 'Dibatalkan',
            ]);
    }
}
