@extends('layouts.app')

@section('content')

<h2>Data Pembayaran</h2>

<a href="{{ route('payments.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Jumlah Bayar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->student->name }}</td>
            <td>{{ $payment->amount_paid }}</td>
            <td>{{ $payment->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection