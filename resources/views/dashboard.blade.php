@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
#package-reminder table{
    width:100%;
}

#package-reminder .table{
    margin-bottom:0;
}

#package-reminder thead{
    background:#f8fafc;
}

#package-reminder thead th{
    white-space:nowrap;
}
<style>
    .ring-chart {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .ring-chart svg { transform: rotate(-90deg); }
    .ring-chart .ring-label {
        position: absolute;
        text-align: center;
        pointer-events: none;
    }
    .mini-bar {
        display: flex;
        align-items: flex-end;
        gap: 4px;
        height: 36px;
    }
    .mini-bar span {
        flex: 1;
        background: var(--primary);
        border-radius: 3px 3px 0 0;
        opacity: .25;
        transition: opacity .2s;
    }
    .mini-bar span:last-child { opacity: 1; }

    .avatar-sm {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .section-title {
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .view-all-btn {
        font-size: .78rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        padding: 4px 10px;
        border-radius: 8px;
        transition: background .18s;
    }
    .view-all-btn:hover { background: var(--primary-soft); }

    .summary-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 12px;
        background: var(--bg);
        font-size: .82rem;
    }
    .summary-pill .pill-icon {
        width: 32px; height: 32px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════
     ROW 1: STAT CARDS
═══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Total Siswa --}}
    <div class="col-md-4">
        <div class="stat-card primary h-100">
            <div class="stat-icon primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value">{{ number_format($totalSiswa) }}</div>
            <div class="stat-label">Total Siswa Terdaftar</div>
            <div>
                <span class="stat-badge neutral">
                    <i class="bi bi-database"></i>
                    Data aktif pada sistem
                </span>
            </div>
        </div>
    </div>

    {{-- Sudah Bayar --}}
    <div class="col-md-4">
        <div class="stat-card success h-100">
            <div class="stat-icon success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-value">{{ number_format($sudahBayar) }}</div>
            <div class="stat-label">Siswa Sudah Bayar</div>
            <div>
            </div>
        </div>
    </div>

    {{-- Belum Bayar --}}
    <div class="col-md-4">
        <div class="stat-card danger h-100">
            <div class="stat-icon danger">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div class="stat-value">{{ number_format($belumBayar) }}</div>
            <div class="stat-label">Siswa Belum Bayar</div>
            <div>
            </div>
        </div>
    </div>

    {{-- Total Pemasukan --}}
    <div class="col-md-4">
        <div class="stat-card warning h-100">
            <div class="stat-icon warning">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-value" style="font-size:1.4rem">
                Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
            </div>
            <div class="stat-label">Total Pemasukan Bulan Ini</div>
            <div>
                <span class="stat-badge neutral">
                    <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('F Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4">

    <div class="stat-card warning h-100">

        <div class="stat-icon warning">

            <i class="bi bi-bell-fill"></i>

        </div>

        <div class="stat-value">

            {{ number_format($totalPackageNeedReminder) }}

        </div>

        <div class="stat-label">

            Paket Hampir Selesai

        </div>

        <div>

            <span class="stat-badge neutral">

                ≤ 2 sesi tersisa

            </span>

        </div>

    </div>

</div>
<div class="col-md-4">

    <div class="stat-card success h-100">

        <div class="stat-icon success">

            <i class="bi bi-check-circle-fill"></i>

        </div>

        <div class="stat-value">

            {{ number_format($totalPackageFinished) }}

        </div>

        <div class="stat-label">

            Paket Selesai

        </div>

        <div>

            <span class="stat-badge neutral">

                Remaining = 0

            </span>

        </div>

    </div>

</div>
</div>

@if($packageReminders->count() > 0)

<div class="row mb-4">

    <div class="col-12">

        <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center">

                <div class="me-3" style="font-size:2rem;">
                    <i class="bi bi-bell-fill"></i>
                </div>

                <div>

                    <h5 class="mb-1 fw-bold">
                        Paket Hampir Selesai
                    </h5>

                    <p class="mb-0 text-muted">
                        Ada
                        <strong>{{ $packageReminders->count() }}</strong>
                        siswa yang paket belajarnya hampir selesai.
                    </p>

                </div>

            </div>

            <a href="{{ route('students.index', ['package_status' => 'warning']) }}" class="btn btn-warning rounded-pill px-4">
                Lihat Detail
            </a>

        </div>

    </div>

</div>

@endif

@if($packageReminders->count() > 0)

<div class="row mb-4" id="package-reminder">

    <div class="col-12">

        <div class="data-card">

            <div class="card-header">

                <div class="stat-icon warning"
                     style="width:36px;height:36px;border-radius:9px;font-size:1rem;margin-bottom:0">

                    <i class="bi bi-bell-fill"></i>

                </div>

                <p class="card-title">
                    Siswa yang Memerlukan Reminder
                </p>

            </div>

            <div class="table-responsive">

                <table class="table">

                    <thead>

                        <tr>

                            <th>Nama</th>

                            <th>Nomor HP</th>

                            <th>Sisa Sesi</th>

                            <th>Estimasi Selesai</th>

                            <th>Forecast Interval</th>

                            <th>Status</th>

                            <th>Aksi</th>


                        </tr>

                    </thead>

                        <tbody>

                        @foreach($packageReminders as $reminder)

                        <tr>

                            <td>
                                {{ $reminder->package?->student?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $reminder->package?->student?->parent_phone ?? '-' }}
                            </td>

                            <td>
                                {{ $reminder->predicted_remaining_sessions }} sesi
                            </td>

                            <td>
                                {{ $reminder->predicted_end_date->translatedFormat('d F Y') }}
                            </td>

                            <td>
                                {{ number_format($reminder->forecast_value,2) }}
                                hari/sesi
                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($reminder->reminder_sent)

                                    <span class="status-badge paid">
                                        Sudah Dikirim
                                    </span>

                                @elseif($reminder->predicted_remaining_sessions == 1)

                                    <span class="status-badge unpaid">
                                        Sangat Mendesak
                                    </span>

                                @else

                                    <span class="status-badge pending">
                                        Perlu Diingatkan
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>

                                <div class="d-flex gap-2">

                                    {{-- Tombol Kirim --}}
                                    <form
                                        action="{{ route('reminders.send', $reminder) }}"
                                        method="POST"
                                        class="d-inline">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-warning btn-sm"
                                            @if($reminder->reminder_sent) disabled @endif>

                                            @if($reminder->reminder_sent)
                                                <i class="bi bi-check-circle"></i>
                                                Sudah Dikirim
                                            @else
                                                <i class="bi bi-send"></i>
                                                Kirim
                                            @endif

                                        </button>

                                    </form>

                                    {{-- Preview selalu tampil --}}
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#previewModal{{ $reminder->id }}">

                                        Preview Pesan

                                    </button>

                                </div>

                            </td>

                        </tr>

                            <!-- TEMPEL DI SINI -->

                            <div class="modal fade"
                                id="previewModal{{ $reminder->id }}"
                                tabindex="-1"
                                aria-hidden="true">

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Preview Pesan WhatsApp
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal">
                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <p><strong>Nama Siswa</strong><br>
                                            {{ $reminder->package->student->name }}</p>

                                            <p><strong>Sisa Sesi</strong><br>
                                            {{ $reminder->predicted_remaining_sessions }} sesi</p>

                                            <p><strong>Estimasi Selesai</strong><br>
                                            {{ $reminder->predicted_end_date->translatedFormat('d F Y') }}</p>

                                            <hr>

                                            <p style="white-space:pre-line">

                            *PENGINGAT PAKET BELAJAR*

                            Yth. Bapak/Ibu Orang Tua/Wali,

                            Kami ingin menginformasikan bahwa paket belajar ananda hampir selesai.

                            Nama:
                            {{ $reminder->package->student->name }}

                            Sisa sesi:
                            {{ $reminder->predicted_remaining_sessions }}

                            Estimasi selesai:
                            {{ $reminder->predicted_end_date->translatedFormat('d F Y') }}

                            Silakan melakukan Renew Package.

                            Terima kasih.

                                            </p>

                                        </div>

                                        <div class="modal-footer">

                                            <button
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">

                                                Tutup

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @endforeach
                </table>

            </div>

        </div>

    </div>

</div>

@endif


{{-- ═══════════════════════════════════════════════
     ROW 2: PROGRESS + RING CHART
═══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Payment Progress --}}
    <div class="col-lg-8">
        <div class="progress-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <p class="section-title">Progres Pembayaran</p>
                    <small class="text-muted">Persentase lunas per kelas — {{ now()->translatedFormat('F Y') }}</small>
                </div>
                <a href="{{ route('payments.index') }}" class="view-all-btn">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach($progressProgram as $kelas)
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:.82rem;font-weight:600">{{ $kelas['nama'] }}</span>
                        <span style="font-size:.78rem;color:var(--muted)">
                            {{ $kelas['lunas'] }}/{{ $kelas['total'] }} siswa
                            <strong style="color:var(--text);margin-left:4px">{{ $kelas['pct'] }}%</strong>
                        </span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar {{ $kelas['pct'] >= 80 ? 'bg-success' : ($kelas['pct'] >= 50 ? 'bg-warning' : 'bg-danger') }}"
                             style="width: {{ intval($kelas['pct'] ?? 0) }}%;" role="progressbar"
                             aria-valuenow="{{ $kelas['pct'] }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Donut Summary --}}
    <div class="col-lg-4">
        <div class="progress-card h-100 d-flex flex-column align-items-center justify-content-center text-center gap-3">
            <p class="section-title w-100 text-start">Ringkasan Pembayaran</p>

            <div class="ring-chart my-2">
                <svg width="140" height="140" viewBox="0 0 140 140">
                    <circle cx="70" cy="70" r="56" fill="none" stroke="#f1f5f9" stroke-width="18"/>
                    {{-- Belum Bayar arc --}}
                    <circle cx="70" cy="70" r="56" fill="none" stroke="#fca5a5"
                        stroke-width="18"
                        stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                        stroke-dashoffset="{{ (2 * 3.14159 * 56) * (1 - $pctBelumBayar / 100) }}"
                        stroke-linecap="round"/>
                    {{-- Sudah Bayar arc --}}
                    <circle cx="70" cy="70" r="56" fill="none" stroke="#4ade80"
                        stroke-width="18"
                        stroke-dasharray="{{ (2 * 3.14159 * 56) * ($pctSudahBayar / 100) }} {{ 2 * 3.14159 * 56 }}"
                        stroke-dashoffset="0"
                        stroke-linecap="round"/>
                </svg>
                <div class="ring-label">
                    <div style="font-size:1.5rem;font-weight:800;line-height:1">{{ $pctSudahBayar }}%</div>
                    <div style="font-size:.65rem;color:var(--muted);font-weight:600">LUNAS</div>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 w-100">
                <div class="summary-pill">
                    <div class="pill-icon" style="background:var(--success-soft);color:var(--success)">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="text-start flex-fill">
                        <div style="font-weight:700;font-size:.85rem">{{ number_format($sudahBayar) }} Siswa</div>
                        <div style="color:var(--muted);font-size:.72rem">Sudah Lunas</div>
                    </div>
                    <span class="badge" style="background:var(--success-soft);color:var(--success)">{{ $pctSudahBayar }}%</span>
                </div>
                <div class="summary-pill">
                    <div class="pill-icon" style="background:var(--danger-soft);color:var(--danger)">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <div class="text-start flex-fill">
                        <div style="font-weight:700;font-size:.85rem">{{ number_format($belumBayar) }} Siswa</div>
                        <div style="color:var(--muted);font-size:.72rem">Belum Bayar</div>
                    </div>
                    <span class="badge" style="background:var(--danger-soft);color:var(--danger)">{{ $pctBelumBayar }}%</span>
                </div>
            </div>
        </div>
    </div>

</div>



{{-- ═══════════════════════════════════════════════
     ROW 3: RECENT TABLE + QUICK ACTIONS
═══════════════════════════════════════════════ --}}
<div class="row g-3">

    {{-- Tabel Siswa Belum Bayar --}}
    <div class="col-lg-8">
        <div class="data-card">
            <div class="card-header">
                <div class="stat-icon danger" style="width:36px;height:36px;border-radius:9px;font-size:1rem;margin-bottom:0">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <p class="card-title">Siswa Belum Bayar</p>
                <a href="{{ route('payments.index', ['status' => 'Belum Bayar']) }}" class="view-all-btn ms-auto">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tagihan</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaBelumBayar as $i => $siswa)
                        <tr>
                            <td class="text-muted" style="font-family:'DM Mono',monospace;font-size:.75rem">
                                {{ str_pad($siswa->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm" style="background: {{ $siswa->avatar_color ?? 'var(--primary-soft)' }}; color: {{ $siswa->avatar_text_color ?? 'var(--primary)' }};">
                                        {{ strtoupper(substr($siswa->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:.875rem">{{ $siswa->name}}</div>
                                        <div style="font-size:.72rem;color:var(--muted)">
                                            {{ $siswa->registration_number }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background:var(--primary-soft);color:var(--primary);font-weight:600;font-size:.72rem">
                                    {{ $siswa->class  }}
                                </span>
                            </td>
                            <td style="font-weight:600">
                               Rp {{ number_format($siswa->sisa_tagihan, 0, ',', '.') }}
                            </td>
                            <td style="font-size:.8rem;color:{{ $siswa->jatuh_tempo && $siswa->jatuh_tempo->isPast() ? 'var(--danger)' : 'var(--muted)' }}">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $siswa->jatuh_tempo ? $siswa->jatuh_tempo->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td>
                                @if($siswa->jatuh_tempo && $siswa->jatuh_tempo->isPast())
                                    <span class="status-badge unpaid">Terlambat</span>
                                @else
                                    <span class="status-badge pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.create', ['student_id' => $siswa->id]) }}"
                                   class="btn btn-sm rounded-3"
                                   style="background:var(--primary-soft);color:var(--primary);font-size:.72rem;font-weight:600;padding:4px 10px">
                                    Bayar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-check-circle-fill text-success" style="font-size:2rem"></i>
                                <p class="mt-2 mb-0 text-muted" style="font-size:.875rem">Semua siswa sudah lunas! 🎉</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Pembayaran Terbaru --}}
    <div class="col-lg-4 d-flex flex-column gap-3">
        {{-- Pembayaran Terbaru --}}
        <div class="data-card flex-fill">
            <div class="card-header">
                <div class="stat-icon success" style="width:36px;height:36px;border-radius:9px;font-size:1rem;margin-bottom:0">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <p class="card-title">Baru Dibayar</p>
            </div>
            <div class="d-flex flex-column">
                @forelse($pembayaranTerbaru as $bayar)
                <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid var(--border)">
                    <div class="avatar-sm" style="background:var(--success-soft);color:var(--success)">
                        {{ strtoupper(substr($bayar->student->name, 0, 1)) }}
                    </div>
                    <div class="flex-fill">
                        <div style="font-weight:600;font-size:.82rem">{{ $bayar->student->name }}</div>
                        <div style="font-size:.7rem;color:var(--muted)">{{ $bayar->payment_date?->diffForHumans() }}</div>
                    </div>
                    <div style="font-weight:700;font-size:.82rem;color:var(--success)">
                        +Rp {{ number_format($bayar->amount_paid, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.875rem">Belum ada pembayaran</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const alert = document.getElementById('success-alert');

    if(alert){

        setTimeout(function(){

            alert.classList.remove('show');

            setTimeout(function(){

                alert.remove();

            },300);

        },2500);

    }

});

</script>

@endpush
@endsection