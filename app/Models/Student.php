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
        'level',
        'schedule_type',
        'intensity',
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
        'date_of_birth' => 'date',
        'jatuh_tempo' => 'datetime',
        'tagihan' => 'integer',
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

    public function getAgeAttribute()
    {
        return $this->date_of_birth?->age;
    }

    public function getStatusPembayaranAttribute()
    {
        $totalTagihan = $this->payments->sum('amount_due');
        $totalBayar = $this->payments->sum('amount_paid');

        if ($totalBayar >= $totalTagihan && $totalTagihan > 0) {
            return 'Lunas';
        }

        if ($totalBayar > 0) {
            return 'Cicilan';
        }

        return 'Belum Bayar';
    }
    public function getTotalBayarAttribute()
    {
        return $this->payments->sum('amount_paid');
    }
}