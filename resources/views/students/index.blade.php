@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Siswa</h2>
    <a href="{{ route('students.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>

<div class="student-card-list d-flex flex-column gap-3">
    @foreach($students as $student)
    <div class="card shadow-sm border">
        <div class="card-body">

            {{-- Header: Nama + Badge --}}
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h5 class="mb-0 fw-semibold">{{ $student->name }}</h5>
                    <small class="text-muted">Reg: {{ $student->registration_number }}</small>
                </div>
                <div class="d-flex gap-2">
                    @if($student->status == 'Enrolled')
                        <span class="badge bg-success-subtle text-success">{{ $student->status }}</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger">{{ $student->status }}</span>
                    @endif
                    <span class="badge bg-info-subtle text-info">{{ $student->weekday_sabtu }}</span>
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="row row-cols-2 row-cols-md-3 g-2 border-top pt-2 mt-1">
                <div>
                    <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.04em;">Reg Date</div>
                    <div class="small">{{ \Carbon\Carbon::parse($student->registration_date)->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.04em;">Program</div>
                    <div class="small">{{ $student->program }}</div>
                </div>
                <div>
                    <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.04em;">Jenis Program</div>
                    <div class="small">{{ $student->program_type }}</div>
                </div>
                <div>
                    <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.04em;">Regular / Intensif</div>
                    <div class="small">{{ $student->regular_intensif }}</div>
                </div>
                <div>
                    <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.04em;">Family</div>
                    <div class="small">{{ $student->family_status }}</div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-2">
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </div>

        </div>
    </div>
    @endforeach
</div>

@endsection