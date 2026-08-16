@extends('layouts.app')

@section('content')

{{-- ================================================= --}}
{{-- PAGE HEADER --}}
{{-- ================================================= --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">

            <div>

                <div class="text-muted small mb-2">

                    Dashboard

                    /

                    Home

                    /

                    <span class="text-dark fw-semibold">

                        Detail Siswa

                    </span>

                </div>

                <h3 class="fw-bold mb-1">

                    {{ $student->name }}

                </h3>

                <div class="text-secondary">

                    {{ $student->registration_number }}

                </div>

            </div>

            <div class="text-end">

                <a href="{{ route('students.index') }}"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            </div>

        </div>

        <hr>

        <div class="d-flex gap-2 flex-wrap">
            <span class="badge {{ $student->status_badge }}">

                {{ $student->status_label }}

            </span>

            @if(auth()->user()->role !== 'tutor')

                @if($student->status_pembayaran == 'Lunas')

                    <span class="badge bg-success">

                        Lunas

                    </span>

                @elseif($student->status_pembayaran == 'Terlambat')

                    <span class="badge bg-danger">

                        Terlambat

                    </span>

                @else

                    <span class="badge bg-warning text-dark">

                        Belum Bayar

                    </span>

                @endif

            @endif

        </div>

    </div>

</div>
{{-- ================================================= --}}
{{-- SUMMARY CARD --}}
{{-- ================================================= --}}

<div class="row g-3 mb-4">

    @if(auth()->user()->role !== 'tutor')

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Total Bayar

                    </small>

                    <h4 class="fw-bold text-success mt-2 mb-0">

                        Rp {{ number_format($student->payments ->where('student_package_id', optional($student->activePackage)->id) ->sum('amount_paid'), 0, ',', '.') }}

                    </h4>

                </div>

            </div>

        </div>

    @endif

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">

                    Sesi Sudah Selesai

                </small>

                <h4 class="fw-bold text-primary mt-2 mb-0">

                    {{ $student->completed_sessions }}

                </h4>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">

                   Sisa Sesi

                </small>

                <h4 class="fw-bold text-warning mt-2 mb-0">

                    {{ $student->remaining_sessions }}

                </h4>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">

                    Estimasi Selesai

                </small>

                <h5 class="fw-bold mt-2 mb-0">

                    {{ optional($student->activePackage?->estimated_end_date)->format('d M Y') ?? '-' }}

                </h5>

            </div>

        </div>

    </div>

</div>
{{-- ===================================== --}}
{{-- 1. INFORMASI SISWA --}}
{{-- ===================================== --}}

<div class="card shadow-sm border-0 rounded-4">

    <div class="card-header bg-white">

        <h5 class="fw-bold mb-0">

            <i class="bi bi-person-vcard text-primary me-2"></i>

            Informasi Siswa

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            {{-- ================= LEFT ================= --}}

            <div class="col-lg-6">

                <table class="table table-borderless mb-0 align-middle">

                    <tr>
                        <th width="40%">Registration Number</th>
                        <td>{{ $student->registration_number }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Daftar</th>
                        <td>{{ optional($student->registration_date)->translatedFormat('d F Y') }}</td>
                    </tr>

                    <tr>
                        <th>Program</th>
                        <td>{{ $student->program }}</td>
                    </tr>

                    <tr>
                        <th>Level</th>
                        <td>{{ $student->activePackage?->program_detail }}</td>
                    </tr>

                    <tr>
                        <th>Paket</th>
                        <td>{{ $student->activePackage?->package_type }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ $student->activePackage?->start_date?->translatedFormat('d F Y') }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ $student->completed_date?->translatedFormat('d F Y') ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Jadwal Belajar</th>
                        <td>{{ $student->schedule_type }}</td>
                    </tr>

                    <tr>
                        <th>Status Family</th>
                        <td>{{ $student->family_status ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Intensitas</th>
                        <td>{{ $student->intensity ?? '-' }}</td>
                    </tr>

                </table>

            </div>

            {{-- ================= RIGHT ================= --}}

            <div class="col-lg-6">

                <table class="table table-borderless mb-0 align-middle">

                    <tr>
                        <th width="40%">Jenis Kelamin</th>
                        <td>{{ $student->gender }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $student->date_of_birth?->format('d M Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $student->status_badge }}">
                                {{ $student->status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <td>{{ $student->age ?? '-' }} tahun</td>
                    </tr>

                    <tr>
                        <th>Sekolah</th>
                        <td>{{ $student->school }}</td>
                    </tr>

                    <tr>
                        <th>Kelas</th>
                        <td>{{ $student->class }}</td>
                    </tr>

                    <tr>
                        <th>No. HP Orang Tua</th>
                        <td>{{ $student->parent_phone }}</td>
                    </tr>

                    <tr>
                        <th>No. HP Anak</th>
                        <td>{{ $student->child_phone ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td>{{ $student->address }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- ===================== --}}
{{-- CURRENT PACKAGE --}}
{{-- ===================== --}}
<div class="col-12 mt-4">
    <div class="card shadow-sm border-0 rounded-4 h-100">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-box-seam text-primary me-2"></i>
                    Paket Aktif
                </h5>

                <a href="{{ route('students.package-history', $student->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-clock-history me-1"></i>
                    Riwayat Paket
                </a>
            </div>
        </div>

        <div class="card-body">

    <div class="row align-items-center gy-3">

        {{-- Package --}}
        <div class="col-lg-5">

            <h5 class="fw-bold mb-1">
                {{ $student->activePackage?->package_type }}
            </h5>

            <p class="text-muted mb-0">
                {{ $student->activePackage?->program_detail }}
            </p>

        </div>

        {{-- Start Date --}}
        <div class="col-lg-3">

            <small class="text-muted d-block">
                Tanggal Mulai
            </small>

            <span class="fw-semibold">
                {{ optional($student->activePackage?->start_date)->translatedFormat('d M Y') }}
            </span>

        </div>

        {{-- Schedule --}}
        <div class="col-lg-4">

            <small class="text-muted d-block">
                Jadwal Belajar
            </small>

            <span class="fw-semibold">
                {{ $student->schedule_type }}
                •
                {{ $student->intensity }}
            </span>

        </div>

    </div>

    <hr class="my-3">

        <div class="text-end">

    @if($student->status === 'Active')

        <form action="{{ route('students.deactivate', $student->id) }}"
              method="POST"
              class="d-inline">

            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="btn btn-outline-danger"
                onclick="return confirm('Yakin ingin menonaktifkan siswa ini?')">

                <i class="bi bi-person-x me-1"></i>

                Nonaktifkan Siswa

            </button>

        </form>

    @endif


    @if($student->status === 'Inactive')

    <a href="{{ route('student-packages.renew', $student->id) }}"
       class="btn btn-success">

        <i class="bi bi-arrow-repeat me-1"></i>

        Perbarui Paket

    </a>

    <form
        action="{{ route('students.graduate', $student->id) }}"
        method="POST"
        class="d-inline">

        @csrf
        @method('PATCH')

        <button
            type="submit"
            class="btn btn-outline-secondary"
            onclick="return confirm('Pindahkan siswa ini ke Data Alumni?')">

            <i class="bi bi-mortarboard-fill me-1"></i>

            Jadikan Alumni

        </button>

    </form>

@endif

</div>
</div>
</div>

{{-- ===================================== --}}
{{-- 3. LEARNING SESSION (POSISI BARU) --}}
{{-- ===================================== --}}
<div class="card shadow-sm border-0 rounded-4 mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">

        <h5 class="fw-bold mb-0">

            <i class="bi bi-journal-check text-primary me-2"></i>

            Riwayat Sesi Pembelajaran

        </h5>

        <span class="badge bg-primary">

            {{ $student->completed_sessions }}

            /

            {{ $student->total_sessions }}

            Session

        </span>

    </div>
        @forelse($student->activePackage?->learningSessions ?? [] as $session)
            <div class="border rounded-3 p-3 mb-3 bg-light-subtle">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <strong class="text-primary">
                        Session {{ $session->session_no }}
                    </strong>
                    <small class="text-muted">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $session->session_date->format('d M Y') }}
                    </small>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tutor</small>
                        <span class="fw-medium"><i class="bi bi-person me-1"></i>{{ $session->tutor?->name ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        @if($session->status == "Completed")
                            <span class="badge bg-success">Selesai</span>
                        @elseif($session->status == "Rescheduled")
                            <span class="badge bg-warning text-dark">Jadwal Ulang</span>
                        @else
                            <span class="badge bg-danger">Dibatalkan</span>
                        @endif
                    </div>
                </div>

                @if($session->notes)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted d-block">Catatan:</small>
                        <p class="mb-0 small text-secondary">{{ $session->notes }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-journal-x display-5 text-muted"></i>
                <h6 class="mt-3 text-muted">
                    Belum ada riwayat sesi pembelajaran
                </h6>
            </div>
        @endforelse
    </div>
</div>

{{-- ===================================== --}}
{{-- 4. RIWAYAT PEMBAYARAN (HIDDEN FOR TUTOR) --}}
{{-- ===================================== --}}
@if(auth()->user()->role !== 'tutor')
<div class="card shadow-sm mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Riwayat Pembayaran</h5>
            <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                Tambah Pembayaran
            </a>
        </div>

        {{-- RINGKASAN --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Total Tagihan</small>
                    <h5>Rp {{ number_format($student->total_tagihan, 0, ',', '.') }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Total Bayar</small>
                    <h5 class="text-success">Rp {{ number_format($student->total_bayar, 0, ',', '.') }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Sisa Tagihan</small>
                    <h5 class="text-danger">Rp {{ number_format($student->sisa_tagihan, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Paket</th>
                        <th>Tagihan</th>
                        <th>Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->payments as $payment)
                        <tr>
                            {{-- TANGGAL --}}
                            <td>
                                {{ $payment->payment_date?->format('d M Y') }}
                            </td>

                            {{-- PAKET + PROGRAM --}}
                            <td>

                            @if($payment->studentPackage)

                                <div class="fw-semibold">

                                    {{ $payment->studentPackage->package_type }}

                                </div>

                                <small class="text-muted">

                                    {{ $payment->studentPackage->program_detail }}

                                </small>

                            @elseif($payment->renew_package_type)

                                <div class="fw-semibold">

                                    {{ $payment->renew_package_type }}

                                </div>

                                <small class="text-muted">

                                    {{ $payment->renew_program_detail }}

                                </small>

                            @else

                                <div class="fw-semibold">

                                    Membership Fee

                                </div>

                                <small class="text-muted">

                                    Free Membership Fee

                                </small>

                            @endif

                        </td>
                            {{-- TAGIHAN --}}
                            <td>

                                <div class="fw-semibold">

                                    Rp {{ number_format($payment->amount_due,0,',','.') }}

                                </div>

                                @if(!$payment->studentPackage && !$payment->renew_package_type)

                                    <small class="text-muted">

                                        Membership Fee

                                    </small>

                                @elseif($payment->membership_fee > 0)

                                    <small class="text-primary">

                                        Termasuk Membership Fee

                                    </small>

                                @elseif(
                                    ($payment->studentPackage && $payment->studentPackage->package_type == 'Full Course')
                                )

                                    <small class="text-success">

                                        Free Membership Fee

                                    </small>

                                @endif

                            </td>
                            {{-- BAYAR --}}
                            <td>
                                @if($payment->amount_paid > 0)
                                    Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- METODE --}}
                            <td>
                                {{ $payment->payment_method ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($payment->status === 'Lunas')
                                    <span class="badge bg-success">
                                        Lunas
                                    </span>
                                @elseif($payment->status === 'Dibatalkan')
                                    <span class="badge bg-secondary">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">

                                <div class="btn-group btn-group-sm">

                                    <a href="{{ route('payments.show', $payment->id) }}"
                                       class="btn btn-outline-primary"
                                       title="Detail Pembayaran">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a href="{{ route('payments.receipt', $payment) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center text-muted py-4">

                                Belum ada pembayaran.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

{{-- ===================================== --}}
{{-- PACKAGE HISTORY MODAL --}}
{{-- ===================================== --}}
<div class="modal fade"
    id="packageHistoryModal"
    tabindex="-1"
    aria-labelledby="packageHistoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="packageHistoryModalLabel">
                    <i class="bi bi-clock-history me-2"></i>
                    Riwayat Paket Pembelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @forelse($student->packages as $package)
                    <div class="border rounded-4 p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h6 class="fw-bold mb-1">
                                    {{ $package->package_type }}
                                </h6>
                                <small class="text-muted">
                                    {{ $package->program_detail }}
                                </small>
                            </div>

                            <div class="col-md-2">
                                <small class="text-muted d-block">
                                    Tanggal Mulai
                                </small>
                                {{ optional($package->start_date)->format('d M Y') }}
                            </div>

                            <div class="col-md-2">
                                <small class="text-muted d-block">
                                   Tanggal Selesai
                                </small>
                                {{ optional($package->estimated_end_date)->format('d M Y') }}
                            </div>

                            <div class="col-md-2">
                                <small class="text-muted d-block">
                                    Sesi
                                </small>
                                {{ $package->learningSessions->count() }} / {{ $package->total_sessions }}
                            </div>

                            <div class="col-md-3 text-end">
                                @if($package->active)
                                    <span class="badge bg-success">
                                        aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-box display-5 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">
                            Belum ada riwayat package.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

@endif
@endsection