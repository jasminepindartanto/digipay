@extends('layouts.app')

@section('page-title', 'Data Pembayaran')

@section('content')

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Data Pembayaran
        </h3>

        <p class="text-muted mb-0">
            Kelola seluruh transaksi pembayaran siswa
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('payments.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Tambah Pembayaran

        </a>

        <a href="{{ route('payments.export', request()->query()) }}"
            class="btn btn-success">

            <i class="bi bi-file-earmark-excel me-1"></i>
            Excel

        </a>

        <a href="{{ route('payments.export.pdf', request()->query()) }}"
            class="btn btn-danger">

            <i class="bi bi-file-earmark-pdf me-1"></i>
            PDF

        </a>

    </div>

</div>

<!-- FILTER -->
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form method="GET"
              action="{{ route('payments.index') }}">

            <div class="row g-3">

                <div class="col-md-5">

                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari nama siswa..."
                        value="{{ request('search') }}">

                </div>

                <div class="col-md-2">

                    <select
                        name="status"
                        class="form-select">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="Lunas"
                            {{ request('status') == 'Lunas' ? 'selected' : '' }}>
                            Lunas
                        </option>

                        <option value="Belum Bayar"
                            {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>
                            Belum Bayar
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="{{ request('tanggal') }}">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">

                        <i class="bi bi-search me-1"></i>
                        Filter

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<!-- TABLE -->
<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table payment-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>Nama</th>
                        <th>Program</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($payments as $payment)

                    <tr>

                        <td class="fw-semibold">
                            {{ $payment->student->name }}
                        </td>

                        <td>
                            {{ $payment->student->program_detail }}
                        </td>
                        <td>

                            <span class="payment-amount">

                                Rp {{ number_format($payment->amount_paid,0,',','.') }}

                            </span>

                        </td>

                        <td>

                            @if($payment->payment_method == 'Cash')

                                <span class="badge-soft-success">
                                    Cash
                                </span>

                            @elseif($payment->payment_method == 'Transfer')

                                <span class="badge-soft-primary">
                                    Transfer
                                </span>

                            @elseif($payment->payment_method == 'QRIS')

                                <span class="badge-soft-dark">
                                    QRIS
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($payment->status == 'Lunas')

                                <span class="badge-soft-success">
                                    Lunas
                                </span>

                            @else

                                <span class="badge-soft-danger">
                                    Belum Bayar
                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $payment->payment_date?->format('d M Y') }}

                        </td>

                        <td>

                            <a href="{{ route('payments.receipt',$payment->id) }}"
                                class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-printer"></i>

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8"
                            class="text-center py-5 text-muted">

                            Belum ada data pembayaran

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>

.payment-table thead th{
    background:#f8fafc;
    border-bottom:1px solid #e5e7eb;
    font-size:.85rem;
    font-weight:700;
    color:#64748b;
    padding:16px;
}

.payment-table tbody td{
    padding:16px;
    border-bottom:1px solid #f1f5f9;
}

.payment-table tbody tr:hover{
    background:#f8fafc;
}

.payment-amount{
    font-weight:700;
    color:#16a34a;
}

.badge-soft-success{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.badge-soft-primary{
    background:#dbeafe;
    color:#1d4ed8;
    padding:6px 12px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.badge-soft-warning{
    background:#fef3c7;
    color:#92400e;
    padding:6px 12px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.badge-soft-danger{
    background:#fee2e2;
    color:#b91c1c;
    padding:6px 12px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.badge-soft-dark{
    background:#e5e7eb;
    color:#111827;
    padding:6px 12px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

</style>
@endpush

@push('scripts')
<script>

let timeout;

const input = document.getElementById('searchInput');

if(input){

    input.addEventListener('input', function(){

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            this.form.submit();

        },400);

    });

}

</script>
@endpush