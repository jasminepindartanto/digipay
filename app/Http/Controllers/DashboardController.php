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

        // ✅ Ganti nama variable: totalStudent -> totalSiswa
        $totalSiswa = Student::count();

        $sudahBayar = Student::whereHas('payments', function ($q) use ($bulanIni, $tahunIni) {
            $q->whereMonth('payment_date', $bulanIni)
            ->whereYear('payment_date', $tahunIni)
            ->where('paid_flag', 1);
        })->count();

        $belumBayar = $totalSiswa - $sudahBayar;

        // ✅ Tambah variable yang hilang
        $pctSudahBayar = $totalSiswa > 0 ? round($sudahBayar / $totalSiswa * 100) : 0;
        $pctBelumBayar = $totalSiswa > 0 ? round($belumBayar / $totalSiswa * 100) : 0;

        // ✅ Tambah variable tambahBulanIni (siswa baru bulan ini)
        $tambahBulanIni = Student::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        $totalPemasukan = Payment::whereMonth('payment_date', $bulanIni)
            ->whereYear('payment_date', $tahunIni)
            ->where('paid_flag', 1)
            ->sum('amount_paid');

        $progressKelas = Student::select('class')
            ->distinct()
            ->orderBy('class')
            ->get()
            ->map(function ($row) use ($bulanIni, $tahunIni) {
                $total = Student::where('class', $row->class)->count();
                $lunas = Student::where('class', $row->class)
                    ->whereHas('payments', function ($q) use ($bulanIni, $tahunIni) {
                        $q->whereMonth('payment_date', $bulanIni)
                          ->whereYear('payment_date', $tahunIni)
                          ->where('paid_flag', 1);
                    })->count();

                return [
                    'nama'  => 'Kelas ' . $row->class,
                    'total' => $total,
                    'lunas' => $lunas,
                    'pct'   => $total > 0 ? round($lunas / $total * 100) : 0,
                ];
            });

        $siswaBelumBayar = Student::with('payments')
            ->whereDoesntHave('payments', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('payment_date', $bulanIni)
                ->whereYear('payment_date', $tahunIni)
                ->where('paid_flag', 1);
            })
            ->latest()
            ->take(10)
            ->get();

        $pembayaranTerbaru = Payment::with('student')
            ->where('paid_flag', 1)
            ->latest()
            ->take(5)
            ->get();

        $avatarColors = [
            ['bg' => '#eff6ff', 'text' => '#2563eb'],
            ['bg' => '#f0fdf4', 'text' => '#16a34a'],
            ['bg' => '#fff7ed', 'text' => '#ea580c'],
            ['bg' => '#fdf4ff', 'text' => '#9333ea'],
            ['bg' => '#ecfeff', 'text' => '#0891b2'],
        ];

        foreach ($siswaBelumBayar as $i => $siswa) {
            $c = $avatarColors[$i % count($avatarColors)];
            $siswa->avatar_color      = $c['bg'];
            $siswa->avatar_text_color = $c['text'];
        }

        // ✅ compact sesuai nama variable yang sudah didefinisikan
        return view('dashboard', [
        'totalSiswa'        => $totalSiswa,
        'sudahBayar'        => $sudahBayar,
        'belumBayar'        => $belumBayar,
        'pctSudahBayar'     => $pctSudahBayar,
        'pctBelumBayar'     => $pctBelumBayar,
        'tambahBulanIni'    => $tambahBulanIni,
        'totalPemasukan'    => $totalPemasukan,
        'progressKelas'     => $progressKelas,
        'siswaBelumBayar'   => $siswaBelumBayar,
        'pembayaranTerbaru' => $pembayaranTerbaru,
    ]);

    }
}