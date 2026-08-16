@extends('layouts.app')

@section('page-title', 'Data Pembayaran')

@section('content')

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Pembayaran</h3>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pembayaran
        </a>
        <a href="{{ route('payments.export', request()->query()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
        <a href="{{ route('payments.export.pdf', request()->query()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
    </div>
</div>

                <!-- FILTER -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('payments.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari nama siswa" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">

                    <select
                        name="level"
                        class="form-select">

                        <option value="">
                            Semua Level
                        </option>

                        @foreach($levels as $level)

                            <option
                                value="{{ $level }}"
                                {{ request('level') == $level ? 'selected' : '' }}>

                                {{ $level }}

                            </option>

                        @endforeach

                    </select>

                </div>
                <div class="col-md-2">
                    <select name="package" class="form-select">
                        <option value="">Semua Paket</option>
                        @foreach($packages as $package)
                            <option
                                value="{{ $package }}"
                                {{ request('package') == $package ? 'selected' : '' }}>
                                {{ $package }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- TAB & DATA TABEL PEMBAYARAN -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <ul class="nav nav-tabs" id="paymentTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#activePayments" type="button" role="tab">
                    Tagihan Aktif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#paymentHistory" type="button" role="tab">
                    Riwayat Pembayaran
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelledPayments" type="button" role="tab">
                    Tagihan Dibatalkan
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content mt-3">
            <!-- TAB 1: TAGIHAN AKTIF -->
            <div class="tab-pane fade show active" id="activePayments" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle payment-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Program</th>
                                <th>Paket</th>
                                <th>Dibayar</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th>Bukti</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activePayments as $payment)
                                <tr>
                                    <td>{{ $payment->student?->name ?? '-' }}</td>
                                    <td>{{ $payment->student?->program ?? '-' }}</td>
                                    <td>{{ $payment->studentPackage?->package_type ?? '-' }}</td>
                                    <td>Rp {{ number_format($payment->amount_due, 0, ',', '.') }}</td>
                                    <td>@php
                                            $jatuhTempo = $payment->due_date
                                                ?? $payment->renew_start_date
                                                ?? $payment->studentPackage?->start_date;
                                        @endphp

                                        {{ optional($jatuhTempo)->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        @if($payment->status == 'Belum Bayar')

                                            <span class="badge bg-warning">
                                                Pending
                                            </span>

                                        @elseif($payment->status == 'Lunas')

                                            <span class="badge bg-success">
                                                Lunas
                                            </span>

                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('payments.create', ['student_id' => $payment->student_id]) }}" class="btn btn-primary btn-sm">
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada tagihan aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: TAGIHAN DIBATALKAN -->
            <div class="tab-pane fade" id="cancelledPayments" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle payment-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Program</th>
                                <th>Paket</th>
                                <th>Tagihan</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Dibatalkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cancelledPayments as $payment)
                                <tr>
                                    <td>
                                        {{ $payment->student?->name ?? '-' }}
                                        @if($payment->student?->is_alumni)
                                            <span class="badge bg-secondary ms-1">Alumni</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->renew_program_detail ?? $payment->studentPackage?->program_detail ?? $payment->student?->program_detail ?? '-' }}</td>
                                    <td>{{ $payment->renew_package_type ?? $payment->studentPackage?->package_type ?? '-' }}</td>
                                    <td>Rp {{ number_format($payment->amount_due, 0, ',', '.') }}</td>
                                    <td>@php
                                            $jatuhTempo = $payment->due_date
                                                ?? $payment->renew_start_date
                                                ?? $payment->studentPackage?->start_date;
                                        @endphp

                                        {{ optional($jatuhTempo)->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    </td>
                                    <td>{{ $payment->updated_at?->format('d M Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada tagihan yang dibatalkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: RIWAYAT PEMBAYARAN -->
            <div class="tab-pane fade" id="paymentHistory" role="tabpanel">
                <div class="table-responsive">
                    <table class="table align-middle payment-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Program</th>
                                <th>Paket</th>
                                <th>Dibayar</th>
                                <th>Metode</th>
                                <th>Tanggal</th>
                                <th width="120">Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentHistory as $payment)
                                <tr>
                                    <td>{{ $payment->student?->name ?? '-' }}</td>

                                    <td>
                                        {{ $payment->renew_program_detail
                                            ?? $payment->studentPackage?->program_detail
                                            ?? $payment->student?->program_detail
                                            ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $payment->studentPackage?->package_type
                                            ?? $payment->renew_package_type
                                            ?? '-' }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        {{ $payment->payment_method ?? '-' }}
                                    </td>

                                    <td>
                                        {{ optional($payment->renew_start_date)->format('d M Y')
                                            ?? optional($payment->payment_date)->format('d M Y')
                                            ?? '-' }}
                                    </td>

                                    {{-- BUKTI PEMBAYARAN --}}
                                    <td>
                                        @if($payment->payment_proof)
                                            <a
                                                href="{{ asset('storage/' . $payment->payment_proof) }}"
                                                target="_blank"
                                                class="btn btn-outline-success btn-sm"
                                            >
                                                <i class="bi bi-image me-1"></i>
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>

                                    {{-- DETAIL --}}
                                    <td>
                                        <a
                                            href="{{ route('payments.show', $payment->id) }}"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Belum ada riwayat pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: #2563eb;
        border-bottom: 3px solid #2563eb;
        background: transparent;
    }
    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }
    .payment-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        font-size: .85rem;
        font-weight: 700;
        color: #64748b;
        padding: 16px;
    }
    .payment-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .payment-table tbody tr:hover {
        background: #f8fafc;
    }
    .payment-amount {
        font-weight: 700;
        color: #16a34a;
    }
    .badge-soft-success {
        background: #dcfce7;
        color: #166534;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }
    .badge-soft-primary {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #92400e;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }
    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }
    .badge-soft-dark {
        background: #e5e7eb;
        color: #111827;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    let timeout;
    const input = document.getElementById('searchInput');

    if (input) {
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 400);
        });
    }
</script>
@endpush