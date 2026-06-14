@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Detail Siswa</h3>

    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
        Kembali
    </a>
</div>
<div class="col-md-6">
    <b>Status Pembayaran:</b><br>
    @if($student->status_pembayaran == 'Lunas')
        <span class="badge bg-success">Lunas</span>
    @elseif($student->status_pembayaran == 'Terlambat')
        <span class="badge bg-danger">Terlambat</span>
    @else
        <span class="badge bg-warning">Belum Bayar</span>
    @endif
</div>
<div class="col-md-6">
    <b>Total Bayar:</b><br>
    Rp {{ number_format($student->payments->sum('amount_paid'), 0, ',', '.') }}
</div>
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3">{{ $student->name }}</h5>

        <div class="row g-3">

            <div class="col-md-6">
                <b>Registration Number:</b><br>
                {{ $student->registration_number }}
            </div>
            <div class="col-md-6">
                <b>Registration Date:</b><br>
                {{ $student->registration_date }}
            </div>
            <div class="col-md-6">
                <b>Program:</b><br>
                {{ $student->program }}
            </div>

            <div class="col-md-6">
                <b>Program Detail:</b><br>
                {{ $student->program_detail }}
            </div>

             <div class="col-md-6">
                <b>Schedule Type:</b><br>
                {{ $student->schedule_type }}
            </div>
            
            <div class="col-md-6">
                <b>Status Family:</b><br>
                {{ $student->family_status ?? '-' }}
            </div>

             <div class="col-md-6">
                <b>Intensitas:</b><br>
                {{ $student->intensity ?? '-' }}
            </div>
            
            <div class="col-md-6">
                <b>Gender:</b><br>
                {{ $student->gender }}
            </div>

            <div class="col-md-6">
                <b>Date of Birth:</b><br>
                {{ $student->date_of_birth?->format('d M Y') ?? '-' }}
            </div>

            <div class="col-md-6">
                <b>Status:</b><br>
                {{ $student->status }}
            </div>

            <div class="col-md-6">
                <b>Umur:</b><br>
                {{ $student->age ?? '-' }} tahun
            </div>

            <div class="col-md-6">
                <b>School:</b><br>
                {{ $student->school }}
            </div>

            <div class="col-md-6">
                <b>Class:</b><br>
                {{ $student->class }}
            </div>

            <div class="col-md-6">
                <b>Parent Phone:</b><br>
                {{ $student->parent_phone }}
            </div>

            <div class="col-md-12">
                <b>Address:</b><br>
                {{ $student->address }}
            </div>

        </div>

    </div>
</div>
{{-- RIWAYAT PEMBAYARAN --}}
<div class="card shadow-sm mt-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Riwayat Pembayaran</h5>

            <a href="{{ route('payments.create', ['student_id' => $student->id]) }}"
               class="btn btn-primary btn-sm">
                + Tambah Pembayaran
            </a>
        </div>

        {{-- RINGKASAN --}}
        <div class="row mb-4">

            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Total Tagihan</small>
                    <h5>
                        Rp {{ number_format($student->total_tagihan, 0, ',', '.') }}
                    </h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Total Bayar</small>
                    <h5 class="text-success">
                        Rp {{ number_format($student->total_bayar, 0, ',', '.') }}
                    </h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <small class="text-muted">Sisa Tagihan</small>
                    <h5 class="text-danger">
                        Rp {{ number_format($student->sisa_tagihan, 0, ',', '.') }}
                    </h5>
                </div>
            </div>

        </div>

        {{-- TABLE --}}
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tagihan</th>
                    <th>Bayar</th>
                    <th>Metode</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($student->payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $payment->payment_date?->format('d M Y') }}
                        </td>

                        <td>
                            Rp {{ number_format($payment->amount_due, 0, ',', '.') }}
                        </td>

                        <td>
                            Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                        </td>
                        <td>
                            {{ $payment->payment_method ?? '-' }}
                        </td>
                        <td>
                            @if($payment->paid_flag)
                                <span class="badge bg-success">
                                    Lunas
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    Cicilan
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection