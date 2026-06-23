<?php

namespace App\Models;
use Carbon\Carbon;
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
        'package_type',
        'start_date',
        'estimated_end_date',
        'completed_date',
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
    'start_date' => 'date',
    'estimated_end_date' => 'date',
    'completed_date' => 'date',
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
        if (
            $this->total_bayar >= $this->total_tagihan &&
            $this->total_tagihan > 0
        ) {
            return 'Lunas';
        }

        return 'Belum Bayar';
    }

    public function getJatuhTempoAttribute()
    {
        if (!$this->registration_date) {
            return null;
        }

        return Carbon::parse($this->registration_date)
            ->addMonth()
            ->day(10);
    }
    
    
}