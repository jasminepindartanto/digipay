<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sistem Tagihan Otomatis
    |--------------------------------------------------------------------------
    |
    | overdue_cancel_days : jumlah hari setelah jatuh tempo sebelum tagihan
    | yang belum dibayar otomatis dibatalkan (status "Dibatalkan").
    |
    */

    'overdue_cancel_days' => env('BILLING_OVERDUE_CANCEL_DAYS', 3),

];
