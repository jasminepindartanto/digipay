<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'registration_number',
        'name',
        'registration_date',
        'program',
        'program_type',
        'gender',
        'date_of_birth',
        'status',
        'age',
        'school',
        'class',
        'address',
        'parent_phone',
        'child_phone',
        'parent_email',
        'parent_instagram',
        'schedule_type',
        'program_category',
        'family_status'
    ];

    protected $casts = [
        'jatuh_tempo' => 'datetime',
        'tagihan'     => 'integer',
    ];

    // RELASI: 1 siswa punya banyak pembayaran
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function sudahLunasBulanIni(): bool
    {
        return $this->payments()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'lunas')
            ->exists();
    }
}