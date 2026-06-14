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
        'program_detail',
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
        'family_status'
    ];

    protected $casts = [
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
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $tagihanBulanIni = $this->payments()
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('amount_due');

        $bayarBulanIni = $this->payments()
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('amount_paid');

        return $bayarBulanIni >= $tagihanBulanIni
            && $tagihanBulanIni > 0;
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth?->age;
    }

    public function getTotalTagihanAttribute()
    {
        return $this->payments->sum('amount_due');
    }

    public function getTotalBayarAttribute()
    {
        return $this->payments->sum('amount_paid');
    }

    public function getSisaTagihanAttribute()
    {
        return max($this->total_tagihan - $this->total_bayar, 0);
    }

    public function getStatusPembayaranAttribute()
    {
        if ($this->total_bayar >= $this->total_tagihan && $this->total_tagihan > 0) {
            return 'Lunas';
        }

        if ($this->total_bayar > 0) {
            return 'Cicilan';
        }

        return 'Belum Bayar';
    }
    
    
}