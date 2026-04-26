<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'receipt_number',
        'payment_date',
        'program',
        'level',
        'payment_group',
        'payment_type',
        'paid_for_month',
        'amount_due',
        'amount_paid',
        'payment_method',
        'status',
        'paid_flag'
    ];

    protected $casts = [
    'amount_due'  => 'integer',
    'amount_paid' => 'integer',
    'paid_flag'   => 'boolean',
    'payment_date'=> 'date',
    ];

    // RELASI: pembayaran milik 1 siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}