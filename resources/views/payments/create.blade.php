@extends('layouts.app')

@section('content')

<h2>Tambah Pembayaran</h2>

{{-- INFO SISWA --}}
@if($student)
    @php
        $totalTagihan = $student->payments->sum('amount_due');
        $totalBayar = $student->payments->sum('amount_paid');
        $sisaTagihan = $totalTagihan - $totalBayar;
    @endphp
    <div class="alert alert-info">
        <b>Nama Siswa:</b> {{ $student->name }} <br>
        <b>Program:</b> {{ $student->program }} <br>
        <b>Sisa Tagihan:</b>
        Rp {{ number_format(max($sisaTagihan, 0), 0, ',', '.') }}
    </div>
@endif

<form action="{{ route('payments.store') }}" method="POST">
    @csrf

    {{-- STUDENT ID --}}
    @if($student)
        <input type="hidden" name="student_id" value="{{ $student->id }}">
    @endif

    {{-- PILIH SISWA --}}
    @if(!$student)
        <div class="mb-3">
            <label>Pilih Siswa</label>
            <select name="student_id" id="studentSelect" class="form-control">
                <option value="">-- Pilih Siswa --</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- JUMLAH TAGIHAN --}}
    <div class="mb-3">
        <label>Jumlah Tagihan</label>
        <input type="number" name="amount_due" class="form-control" required>
    </div>

    {{-- JUMLAH BAYAR --}}
    <div class="mb-3">
        <label>Jumlah Bayar</label>
        <input type="number" name="amount_paid" class="form-control" required>
    </div>

    {{-- TANGGAL BAYAR --}}
    <div class="mb-3">
        <label>Tanggal Pembayaran</label>
        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}">
    </div>
    <div class="mb-3">

    {{-- METODE PEMBAYARAN --}}
    <label>Metode Pembayaran</label>
        <select name="payment_method" class="form-control">
            <option value="">-- Pilih Metode --</option>
            <option value="Transfer">Transfer</option>
            <option value="QRIS">QRIS</option>
            <option value="Cash">Cash</option>
        </select>
    </div>

    {{-- PAYMENT GROUP --}}
<div class="mb-3">
    <label>Group Pembayaran</label>

    <select name="payment_group" class="form-control">

        <option value="">-- Pilih Group --</option>

        <option value="SF">SF</option>

        <option value="DigiSchool">DigiSchool</option>

        <option value="Other">Other</option>

    </select>
</div>

    {{-- SCHEDULE TYPE --}}
    <div class="mb-3">
        <label>Schedule Type</label>

        <select name="schedule_type" class="form-control">
            <option value="">-- Pilih Schedule --</option>
            <option value="Weekend">Weekday</option>
            <option value="Sabtu">Sabtu</option>
        </select>
    </div>

{{-- CLASS TYPE --}}
<div class="mb-3">
    <label>Class Type</label>

    <select name="class_type" class="form-control">
        <option value="">-- Pilih Class Type --</option>
        <option value="Regular">Regular</option>
        <option value="Intensif">Intensif</option>
    </select>
</div>

{{-- FAMILY TYPE --}}
<div class="mb-3">
    <label>Family Type</label>

    <select name="family_type" class="form-control">
        <option value="">-- Pilih Family Type --</option>
        <option value="Family">Family</option>
        <option value="Non Family">Non Family</option>
    </select>
</div>

    {{-- CATATAN --}}
    <div class="mb-3">
        <label>Catatan (opsional)</label>
        <textarea name="note" class="form-control"></textarea>
    </div>

    <button class="btn btn-success">Simpan Pembayaran</button>
</div>
</form>
    @push('scripts')
<script>
    $(document).ready(function() {
        $('#studentSelect').select2({
            placeholder: "Cari siswa...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

@endsection