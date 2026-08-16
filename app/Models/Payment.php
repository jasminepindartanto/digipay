<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentPackage;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'student_package_id',
        'receipt_number',
        'payment_date',
        'due_date',
        'payment_group',
        'schedule_type',
        'class_type',
        'family_type',
        'payment_type',
        'paid_for_month',
        'amount_due',
        'amount_paid',
        'payment_method',
        'payment_proof',
        'status',
        'paid_flag',
        'package_price',
        'membership_fee',
        'membership_status',
        'discount_amount',
        'renew_package_type',
        'renew_program_detail',
        'renew_start_date',
        'renew_estimated_end_date',
        'renew_total_sessions',
    
    ];

    protected $casts = [
    'amount_due'  => 'integer',
    'amount_paid' => 'integer',
    'paid_flag'   => 'boolean',
    'payment_date'=> 'date',
    'due_date'    => 'date',
    'renew_start_date' => 'date',
    'renew_estimated_end_date' => 'date',
    ];

    // RELASI: pembayaran milik 1 siswa
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentPackage()
    {
        return $this->belongsTo(StudentPackage::class);
    }
}