<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni   = Carbon::now()->month;
        $tahunIni   = Carbon::now()->year;

        // ── Statistik Utama ────────────────────────────────────────────
        $totalSiswa     = Siswa::count();
        $sudahBayar     = Siswa::whereHas('pembayaran', function ($q) use ($bulanIni, $tahunIni) {
                              $q->whereMonth('created_at', $bulanIni)
                                ->whereYear('created_at', $tahunIni)
                                ->where('status', 'lunas');
                          })->count();
        $belumBayar     = $totalSiswa - $sudahBayar;
        $pctSudahBayar  = $totalSiswa > 0 ? round($sudahBayar / $totalSiswa * 100) : 0;
        $pctBelumBayar  = 100 - $pctSudahBayar;

        $tambahBulanIni = Siswa::whereMonth('created_at', $bulanIni)
                               ->whereYear('created_at', $tahunIni)
                               ->count();

        $totalPemasukan = Pembayaran::whereMonth('created_at', $bulanIni)
                                    ->whereYear('created_at', $tahunIni)
                                    ->where('status', 'lunas')
                                    ->sum('jumlah');

        // ── Progres Per Kelas ──────────────────────────────────────────
        $progressKelas = Siswa::select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->get()
            ->map(function ($row) use ($bulanIni, $tahunIni) {
                $total = Siswa::where('kelas', $row->kelas)->count();
                $lunas = Siswa::where('kelas', $row->kelas)
                    ->whereHas('pembayaran', fn($q) =>
                        $q->whereMonth('created_at', $bulanIni)
                          ->whereYear('created_at', $tahunIni)
                          ->where('status', 'lunas')
                    )->count();
                return [
                    'nama'  => 'Kelas ' . $row->kelas,
                    'total' => $total,
                    'lunas' => $lunas,
                    'pct'   => $total > 0 ? round($lunas / $total * 100) : 0,
                ];
            });

        // ── Siswa Belum Bayar (10 terbaru) ────────────────────────────
        $siswaBelumBayar = Siswa::whereDoesntHave('pembayaran', fn($q) =>
                                    $q->whereMonth('created_at', $bulanIni)
                                      ->whereYear('created_at', $tahunIni)
                                      ->where('status', 'lunas')
                               )
                               ->latest()
                               ->take(10)
                               ->get();

        // ── Pembayaran Terbaru ─────────────────────────────────────────
        $pembayaranTerbaru = Pembayaran::with('siswa')
                                       ->where('status', 'lunas')
                                       ->latest()
                                       ->take(5)
                                       ->get();

        // ── Warna Avatar Acak ──────────────────────────────────────────
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

        return view('dashboard', compact(
            'totalSiswa', 'sudahBayar', 'belumBayar',
            'pctSudahBayar', 'pctBelumBayar',
            'tambahBulanIni', 'totalPemasukan',
            'progressKelas', 'siswaBelumBayar',
            'pembayaranTerbaru', 'belumBayarCount'
        ));
    }
}