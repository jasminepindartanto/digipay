<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\LearningSession;
use App\Models\StudentPackage;

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
        #'current_session',
        'start_date',
        'estimated_end_date',
        'completed_date',
        'schedule_type',
        'intensity',
        'gender',
        'date_of_birth',
        'status',
        'student_status',
        'is_alumni',
        'age',
        'school',
        'class',
        'address',
        'parent_phone',
        'child_phone',
        'parent_email',
        'parent_instagram',
        'family_status',
        'registration_type',
    ];

    protected $casts = [
    'registration_date' => 'date',
    'date_of_birth' => 'date',
    'start_date' => 'date',
    'estimated_end_date' => 'date',
    'completed_date' => 'date',
    'jatuh_tempo' => 'datetime',
    'tagihan' => 'integer',
    'is_alumni' => 'boolean',
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
            ->whereMonth('payment_date', $bulanIni)
            ->whereYear('payment_date', $tahunIni)
            ->sum('amount_due');

        $bayarBulanIni =$this->payments()
            ->whereMonth('payment_date', $bulanIni)
            ->whereYear('payment_date', $tahunIni)
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
        if (!$this->activePackage) {
            return 0;
        }

        return $this->activePackage
            ->payments
            ->where('status', '!=', 'Dibatalkan')
            ->sum('amount_due');
    }

    public function getTotalBayarAttribute()
    {
        if (!$this->activePackage) {
            return 0;
        }

        return $this->activePackage
            ->payments
            ->where('status', '!=', 'Dibatalkan')
            ->sum('amount_paid');
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

    public function getTotalSessionsAttribute()
    {
        return $this->activePackage?->total_sessions ?? 0;
    }
    
    public function getRemainingSessionsAttribute()
    {
        return max(
            $this->total_sessions - $this->completed_sessions,
            0
        );
    }
    
    public function getCompletedSessionsAttribute()
    {if (!$this->activePackage) {return 0;}
    return LearningSession::where('student_package_id', $this->activePackage->id)
    ->where('status','Completed')
    ->count();
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_sessions == 0) {
            return 0;
        }

        return round(
            ($this->completed_sessions / $this->total_sessions)
            * 100
        );
    }
    
    public function getCurrentSessionAttribute()
    {
        return min( $this->completed_sessions + 1, $this->total_sessions );
    }

    public function learningSessions()
    {
        return $this->hasMany(LearningSession::class);
    }

    public function packages()
    {
        return $this->hasMany(
            StudentPackage::class
        );
    }

    public function activePackage()
    {
        return $this->hasOne(
            StudentPackage::class
        )->where(
            'active',
            true
        );
    }

    public function latestPackage()
    {
        return $this->hasOne(StudentPackage::class)
            ->latestOfMany();
    }

    /*
|--------------------------------------------------------------------------
| Status Label
|--------------------------------------------------------------------------
*/

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {

            'Active' => 'Aktif',

            'Inactive' => 'Tidak Aktif',

            'Pending' => 'Pending',

            default => $this->status,

        };
    }

    /*
|--------------------------------------------------------------------------
| Status Badge
|--------------------------------------------------------------------------
*/

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {

            'Pending' => 'text-bg-warning',

            'Active' => 'text-bg-success',

            'Inactive' => 'text-bg-secondary',

            default => 'text-bg-secondary',

        };
    }
}