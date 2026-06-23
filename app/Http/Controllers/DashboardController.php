<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $bulanIni = Carbon::now()->month;
    $tahunIni = Carbon::now()->year;

    // ======================
    // BASIC STAT
    // ======================
    $totalSiswa = Student::count();

    $totalSudahBayar = Payment::where('paid_flag', 1)
    ->whereMonth('payment_date', $bulanIni)
    ->whereYear('payment_date', $tahunIni)
    ->sum('amount_paid');

    $sudahBayar = Student::all()
    ->filter(fn($student) => $student->status_pembayaran === 'Lunas')
    ->count();

    $belumBayar = Student::all()
    ->filter(fn($student) => $student->status_pembayaran !== 'Lunas')
    ->count();
    // ======================
    // PERCENTAGE
    // ======================
    $pctSudahBayar = $totalSiswa > 0
        ? round($sudahBayar / $totalSiswa * 100)
        : 0;

    $pctBelumBayar = $totalSiswa > 0
        ? round($belumBayar / $totalSiswa * 100)
        : 0;

    // ======================
    // ADDITIONAL STATS
    // ======================
    $tambahBulanIni = Student::whereMonth('created_at', $bulanIni)
        ->whereYear('created_at', $tahunIni)
        ->count();

    $totalPemasukan = Payment::whereMonth('payment_date', $bulanIni)
        ->whereYear('payment_date', $tahunIni)
        ->sum('amount_paid');

    $pemasukanBulanIni = $totalPemasukan;

    // ======================
    // PROGRAM PROGRESS
    // ======================
    $progressProgram = Student::select('program_detail')
        ->distinct()
        ->orderBy('program_detail')
        ->get()
        ->map(function ($row) use ($bulanIni, $tahunIni) {

            $total = Student::where('program_detail', $row->program_detail)->count();

            $lunas = Student::where('program_detail', $row->program_detail)
            ->whereHas('payments', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('payment_date', $bulanIni)
                ->whereYear('payment_date', $tahunIni)
                ->where('paid_flag', 1);
            })
            ->count();

            return [
                'nama'  => 'Program ' . $row->program_detail,
                'total' => $total,
                'lunas' => $lunas,
                'pct'   => $total > 0 ? round($lunas / $total * 100) : 0,
            ];
        });

    // ======================
    // LIST SISWA BELUM BAYAR
    // ======================
    $siswaBelumBayar = Student::with('payments')
    ->get()
    ->filter(function ($student) {
        return $student->status_pembayaran !== 'Lunas'
            && $student->payments->count() > 0;
    });

    // ======================
    // PEMBAYARAN TERBARU
    // ======================
    $pembayaranTerbaru = Payment::with('student')
    ->where('amount_paid', '>', 0)
    ->latest('payment_date')
    ->take(5)
    ->get();

    // ======================
    // AVATAR COLOR
    // ======================
    $avatarColors = [
        ['bg' => '#eff6ff', 'text' => '#2563eb'],
        ['bg' => '#f0fdf4', 'text' => '#16a34a'],
        ['bg' => '#fff7ed', 'text' => '#ea580c'],
        ['bg' => '#fdf4ff', 'text' => '#9333ea'],
        ['bg' => '#ecfeff', 'text' => '#0891b2'],
    ];

    foreach ($siswaBelumBayar as $i => $siswa) {
        $c = $avatarColors[$i % count($avatarColors)];
        $siswa->avatar_color = $c['bg'];
        $siswa->avatar_text_color = $c['text'];
    }

    // ======================
    // RETURN VIEW
    // ======================
    return view('dashboard', compact(
        'totalSiswa',
        'sudahBayar',
        'belumBayar',
        'pctSudahBayar',
        'pctBelumBayar',
        'pemasukanBulanIni',
        'tambahBulanIni',
        'totalPemasukan',
        'progressProgram',
        'siswaBelumBayar',
        'pembayaranTerbaru',
        'totalSudahBayar'
    ));
}
}