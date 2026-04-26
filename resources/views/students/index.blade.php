@extends('layouts.app')

@section('content')

<h2>Data Siswa</h2>

<a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Tambah Siswa</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>No Registrasi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->registration_number }}</td>
            <td>
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

