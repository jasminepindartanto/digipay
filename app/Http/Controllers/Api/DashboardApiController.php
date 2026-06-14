<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;

class DashboardApiController extends Controller
{
    public function index()
    {
        $totalSiswa = Student::count();

        $siswaAktif = Student::where('status', 'Active')->count();

        $totalPembayaran = Payment::sum('amount_paid');

        $belumBayar = Student::all()
            ->filter(function ($student) {
                return $student->status_pembayaran === 'Belum Bayar';
            })
            ->count();

        return response()->json([
        'success' => true,
        'data' => [
            'total_siswa'      => $totalSiswa,
            'siswa_aktif'      => $siswaAktif,
            'total_pembayaran' => $totalPembayaran,
            'belum_bayar'      => $belumBayar,
        ]
    ]);
    }
}