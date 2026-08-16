<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class StudentRegistration extends Model
{
    protected $fillable = 
    [
        'student_id',
        'name',
        'gender',
        'date_of_birth',
        'school',
        'class',
        'program',
        'program_detail',
        'package_type',
        'registration_type',
        'schedule_type',
        'intensity',
        'family_status',
        'registration_date',
        'start_date',
        'parent_phone',
        'parent_email',
        'parent_instagram',
        'child_phone',
        'address',
        'status',
        'source',
        'reject_reason',
        'approved_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'registration_date' => 'date',
        'start_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}