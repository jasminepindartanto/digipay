<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExport implements FromCollection, WithHeadings
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments->map(function ($payment) {

            return [

                'Nama Siswa' => $payment->student->name ?? '-',

                'No Kwitansi' => $payment->receipt_number,

                'Tanggal Pembayaran' => $payment->payment_date,

                'Program' => $payment->student->program ?? '-',

                'Program Detail' => $payment->student->program_detail ?? '-',

                'Group Pembayaran' => $payment->payment_group,

                'Schedule Type' => $payment->schedule_type,

                'Class Type' => $payment->class_type,

                'Family Type' => $payment->family_type,

                'Bulan Bayar' => $payment->paid_for_month,

                'Jumlah Tagihan' => $payment->amount_due,

                'Jumlah Bayar' => $payment->amount_paid,

                'Metode Pembayaran' => $payment->payment_method,

                'Status' => $payment->status,

                'Paid Flag' => $payment->paid_flag ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [

            'Nama Siswa',
            'No Kwitansi',
            'Tanggal Pembayaran',
            'Program',
            'Program Detail',
            'Group Pembayaran',
            'Schedule Type',
            'Class Type',
            'Family Type',
            'Bulan Bayar',
            'Jumlah Tagihan',
            'Jumlah Bayar',
            'Metode Pembayaran',
            'Status',
            'Paid Flag'

        ];
    }
}