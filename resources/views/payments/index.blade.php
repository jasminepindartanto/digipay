@extends('layouts.app')

@section('content')

<h2>Data Pembayaran</h2>
<form method="GET" action="{{ route('payments.index') }}" class="mb-3">
    <div class="input-group">
        <input 
            type="text"
            name="search"
            id="searchInput"
            class="form-control"
            placeholder="Cari nama siswa..."
            value="{{ request('search') }}"
        >

        <button class="btn btn-primary">
            Cari
        </button>
    </div>
</form>

<a href="{{ route('payments.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Program</th>
            <th>Level</th>
            <th>Jumlah Bayar</th>
            <th>Metode</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->student->name }}</td>
            <td>{{ $payment->student->program }}</td>
            <td>{{ $payment->student->level }}</td>
            <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
            <td>
                @if($payment->payment_method == 'Cash')
                    <span class="badge bg-success">Cash</span>

                @elseif($payment->payment_method == 'Transfer')
                    <span class="badge bg-primary">Transfer</span>

                @elseif($payment->payment_method == 'QRIS')
                    <span class="badge bg-dark">QRIS</span>

                @else
                    <span class="badge bg-secondary">
                        {{ $payment->payment_method ?? '-' }}
                    </span>
                @endif
            </td>
            <td>{{ $payment->payment_date?->format('d M Y') }}</td>
            <td>
                <a href="{{ route('payments.receipt', $payment->id) }}"
                class="btn btn-sm btn-outline-primary">
                    Print
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@push('scripts')
    <script>
    let timeout = null;

    const input = document.getElementById('searchInput');

    if (input) {
        input.addEventListener('input', function () {

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                this.form.submit();
            }, 400);

        });
    }
    </script>
@endpush
@endsection