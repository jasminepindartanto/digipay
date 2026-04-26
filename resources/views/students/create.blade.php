@extends('layouts.app')

@section('content')

<h2>Tambah Siswa</h2>

<form action="{{ route('students.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>No Registrasi</label>
        <input type="text" name="registration_number" class="form-control">
    </div>

    <button class="btn btn-success">Simpan</button>
</form>

@endsection