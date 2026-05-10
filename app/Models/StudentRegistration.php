<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'date_of_birth',
        'school',
        'class',
        'program',
        'parent_phone',
        'address',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];
}