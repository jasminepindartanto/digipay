<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // TOTAL SISWA
        $totalSiswa = Student::count();

        // SISWA AKTIF
        $siswaAktif = Student::where('status', 'Active')->count();

        // TOTAL PEMASUKAN (SEMUA)
        $totalPembayaran = Payment::sum('amount_paid');

        // PEMASUKAN BULAN INI
        $pemasukanBulanIni = Payment::whereMonth('payment_date', $now->month)
            ->whereYear('payment_date', $now->year)
            ->sum('amount_paid');

        // SISWA BELUM BAYAR (FIX: jangan loop collection)
        $belumBayar = Student::whereDoesntHave('payments', function ($q) {
            $q->whereColumn('amount_paid', '>=', 'amount_due');
        })->count();

        // SISWA SUDAH LUNAS
        $sudahLunas = Student::whereHas('payments')
            ->get()
            ->filter(fn($s) => $s->status_pembayaran === 'Lunas')
            ->count();

        // SISWA BELUM BAYAR (STATUS LOGIC)
        $belumBayarStatus = Student::get()
            ->filter(fn($s) => $s->status_pembayaran === 'Belum Bayar')
            ->count();

        // RECENT PAYMENT (INI YANG KAMU KURANG)
        $recentPayments = Payment::with('student')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            "success" => true,
            "data" => [
                "total_siswa" => $totalSiswa,
                "siswa_aktif" => $siswaAktif,
                "total_pembayaran" => $totalPembayaran,
                "pemasukan_bulan_ini" => $pemasukanBulanIni,
                "sudah_lunas" => $sudahLunas,
                "belum_bayar" => $belumBayarStatus,
                "recent_payments" => $recentPayments
            ]
        ]);
    }
}