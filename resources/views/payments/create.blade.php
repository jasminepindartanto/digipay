@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <nav class="mb-3">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('payments.index') }}">
                    Pembayaran
                </a>
            </li>

            <li class="breadcrumb-item active">

                Tambah Pembayaran

            </li>

        </ol>

    </nav>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3 class="fw-bold mb-1">

                Tambah Pembayaran

            </h3>

{{-- INFO SISWA --}}


<form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($student)

    <input
        type="hidden"
        name="student_id"
        value="{{ $student->id }}">

    <div class="mb-4">

        <label class="form-label fw-semibold">
            Nama Siswa
        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $student->registration_number }} - {{ $student->name }}"
            readonly>

    </div>

@else

    <div class="mb-4">

        <label class="form-label fw-semibold">
            Pilih Siswa
        </label>

        <select
            name="student_id"
            id="studentSelect"
            class="form-select">

            <option value="">
                -- Pilih Siswa --
            </option>

            @foreach($students as $item)

                <option value="{{ $item->id }}">

                    {{ $item->registration_number }}
                    -
                    {{ $item->name }}

                </option>

            @endforeach

        </select>

    </div>

@endif
    {{-- JUMLAH TAGIHAN --}}
    <div class="card border mb-4">
        <div class="card-header bg-light">
            <strong>
                Ringkasan Tagihan
            </strong>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4 text-muted">
                    Invoice
                </div>
                <div class="col-md-8 fw-semibold">
                <span id="invoiceNumber">

                    @if($payment)

                        {{ $payment->receipt_number }}

                    @else

                        -

                    @endif

                </span>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4 text-muted">
                Paket
            </div>
            <div class="col-md-8">
                <span id="packageType">

                @if($payment)

                    {{ $payment->renew_package_type
                        ?? $payment->studentPackage?->package_type
                        ?? '-' }}

                @else

                    -

                @endif

                </span>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4 text-muted">
                Level
            </div>
            <div class="col-md-8">
                <span id="programDetail">

                    @if($payment)

                        {{ $payment->renew_program_detail
                        ?? $payment->student->program_detail }}

                    @else

                        -

                    @endif

                </span>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4 text-muted">
                Periode
            </div>
            <div class="col-md-8">
                <span id="paymentPeriod">

                    @if($payment)

                        {{ $payment->renew_start_date
                            ?? $payment->paid_for_month
                            ?? '-' }}

                    @else

                        -

                    @endif

                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-muted">
                Jumlah Tagihan
            </div>
            <div class="col-md-8">
                <strong id="amountDueText">

                    @if($payment)

                        Rp {{ number_format($payment->amount_due,0,',','.') }}

                    @else

                        Rp 0

                    @endif

                </strong>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="amount_due" name="amount_due" value="{{ $payment?->amount_due }}">
<input type="hidden" name="payment_id" id="payment_id" value="{{ $payment?->id }}">

    {{-- JUMLAH BAYAR --}}
    <div class="mb-3">
        <label>Jumlah Bayar</label>
        <input type="number" name="amount_paid" class="form-control" min="0" required>
    </div>

    <div class="mb-3">
    
{{-- METODE PEMBAYARAN --}}
<label>Metode Pembayaran</label>
<select name="payment_method" class="form-control" required>
    <option value="">-- Pilih Metode --</option>
    <option value="Transfer">Transfer</option>
    <option value="QRIS">QRIS</option>
    <option value="Cash">Cash</option>
</select>
</div>

{{-- BUKTI PEMBAYARAN --}}
<div class="mb-3">
    <label for="payment_proof">Bukti Pembayaran</label>

    <input
        type="file"
        name="payment_proof"
        id="payment_proof"
        class="form-control"
        accept="image/jpeg,image/png,image/jpg,application/pdf"
    >

    <small class="text-muted">
        Format: JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
    </small>

    @error('payment_proof')
        <div class="text-danger mt-1" style="font-size: 0.875rem;">
            {{ $message }}
        </div>
    @enderror
</div>
    <button class="btn btn-success">Simpan Pembayaran</button>
</div>
</form>
    @push('scripts')
<script>

if ($('#studentSelect').length) {

    $('#studentSelect').select2({

        placeholder: 'Cari siswa',

        width: '100%'

    });

    $('#studentSelect').on('change', function () {

        let studentId = $(this).val();

        if (studentId === '') {
            return;
        }

        $.get(
            '/payments/student/' + studentId + '/bill',
            function (response) {

                if (!response.success) {

                    $('#invoiceNumber').text('-');
                    $('#packageType').text('-');
                    $('#programDetail').text('-');
                    $('#paymentPeriod').text('-');
                    $('#amountDueText').text('Rp 0');

                    $('#amount_due').val('');
                    $('#payment_id').val('');

                    return;
                }

                $('#invoiceNumber').text(response.invoice);
                $('#packageType').text(response.package);
                $('#programDetail').text(response.level);
                $('#paymentPeriod').text(response.period);

                $('#amountDueText').text(
                    'Rp ' +
                    Number(response.amount_due).toLocaleString('id-ID')
                );

                $('#amount_due').val(response.amount_due);
                $('#payment_id').val(response.payment_id);

            }
        );

    });

}

</script>

@endpush

        </div>

    </div>

</div>
@endsection