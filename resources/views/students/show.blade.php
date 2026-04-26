@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Detail Siswa</h3>

    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">
        Kembali
    </a>
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
                <b>Program:</b><br>
                {{ $student->program }}
            </div>

            <div class="col-md-6">
                <b>Jenis Program:</b><br>
                {{ $student->program_type }}
            </div>

            <div class="col-md-6">
                <b>Gender:</b><br>
                {{ $student->gender }}
            </div>

            <div class="col-md-6">
                <b>Date of Birth:</b><br>
                {{ $student->date_of_birth }}
            </div>

            <div class="col-md-6">
                <b>Status:</b><br>
                {{ $student->status }}
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

@endsection