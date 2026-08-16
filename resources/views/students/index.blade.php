@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            Data Siswa
        </h3>
    </div>
</div>
</div>
<div class="row g-3 mb-4">

    <div class="col-md-3">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">

                    <i class="bi bi-people-fill fs-3 text-primary"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Total Siswa
                    </small>

                    <h3 class="mb-0 fw-bold">
                        {{ $totalStudents }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow border-0 rounded-4 h-100">

            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">

                    <i class="bi bi-person-check-fill fs-3 text-success"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Siswa Aktif
                    </small>

                    <h3 class="mb-0 fw-bold">
                        {{ $activeStudents }}
                    </h3>

                </div>

            </div>

        </div>

    </div>
    @if(auth()->user()->role != 'tutor')
    <div class="col-md-3">

        <div class="card shadow border-0 rounded-4 h-100">

            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">

                    <i class="bi bi-exclamation-circle-fill fs-3 text-danger"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Belum Bayar
                    </small>

                    <h3 class="mb-0 fw-bold">
                        {{ $unpaidStudents }}
                    </h3>

                </div>

            </div>

        </div>

    </div>
    @endif

    <div class="col-md-3">

        <div class="card shadow border-0 rounded-4 h-100">

            <div class="card-body d-flex align-items-center">

                <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">

                    <i class="bi bi-book-half fs-3 text-info"></i>

                </div>

                <div>

                    <small class="text-muted">
                        Program Aktif
                    </small>

                    <h3 class="mb-0 fw-bold">
                        {{ $programCount }}
                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>
<div class="card shadow border-0 rounded-4 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('students.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-2">

    {{-- Search --}}
    <div style="flex-grow: 1; min-width:300px;">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            class="form-control"
            placeholder="Cari nama / no registrasi">
    </div>

    {{-- Level --}}
    <div style="width:150px;">
        <select name="level" class="form-select">
            <option value="">Semua Level</option>

            @foreach($levels as $level)
                <option value="{{ $level }}"
                    {{ request('level') == $level ? 'selected' : '' }}>
                    {{ $level }}
                </option>
            @endforeach

        </select>
    </div>

    {{-- Status --}}
    <div style="width:150px;">
        <select name="status" class="form-select">

            <option value="">Semua Status</option>
            <option value="Pending">Pending</option>
            <option value="Active">Aktif</option>
            <option value="Inactive">Tidak Aktif</option>

        </select>
    </div>

    {{-- Pembayaran --}}
    <div style="width:170px;">
        <select name="payment" class="form-select">

            <option value="">Semua Pembayaran</option>
            <option value="Lunas">Lunas</option>
            <option value="Belum Bayar">Belum Bayar</option>

        </select>
    </div>

    {{-- Paket --}}
    <div style="width:150px;">
        <select name="package_type" class="form-select">

            <option value="">Semua Paket</option>
            <option value="Monthly">Monthly</option>
            <option value="1 Level">1 Level</option>
            <option value="Full Course">Full Course</option>

        </select>
    </div>

    {{-- Cari --}}
    <button
        type="submit"
        class="btn btn-primary"
        style="width:46px;height:46px;"
        title="Cari">

        <i class="bi bi-search"></i>

    </button>

    {{-- Excel --}}
    <a
        href="{{ route('students.export.excel', request()->query()) }}"
        class="btn btn-success"
        style="width:46px;height:46px;"
        title="Export Excel">

         <i class="bi bi-file-earmark-excel"></i>

    </a>

    {{-- PDF --}}
    <a
        href="{{ route('students.export.pdf', request()->query()) }}"
        class="btn btn-danger"
        style="width:46px;height:46px;"
        title="Export PDF">

        <i class="bi bi-file-earmark-pdf"></i>

    </a>

</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase small">
                <tr>

                    <th width="60" class="text-secondary">
                        No
                    </th>

                    <th class="text-secondary">
                        No Registrasi
                    </th>

                    <th class="text-secondary">
                        Nama Siswa
                    </th>

                    <th class="text-secondary">
                        Program
                    </th>

                   <th class="text-secondary">
                        Level
                    </th>

                    <th class="text-center">
                        Sisa Sesi
                    </th>

                    @if(auth()->user()->role != 'tutor')

                        <th>Status Pembayaran</th>

                    @endif

                    <th class="text-secondary">
                        Status
                    </th>

                    <th class="text-end">
                        Aksi
                    </th>

                </tr>

                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="fw-semibold text-secondary">
                            {{ $students->firstItem() + $loop->index }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $student->registration_number }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center me-3" style="width:40px;height:40px;">
                                    {{ strtoupper(substr($student->name,0,2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $student->name }}
                                    </div>
                                    @if($student->school)
                                    <small class="text-muted">
                                        {{ $student->school }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                {{ $student->program }}
                            </span>
                        </td>
                        <td>

                            @php

                                $level = strtolower($student->program_detail);

                            @endphp

                            @if(str_contains($level,'little'))

                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">

                                    {{ $student->program_detail }}

                                </span>

                            @elseif(str_contains($level,'junior'))

                                <span class="badge bg-success-subtle text-success border border-success-subtle">

                                    {{ $student->program_detail }}

                                </span>

                            @elseif(str_contains($level,'teenager'))

                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">

                                    {{ $student->program_detail }}

                                </span>

                            @elseif(str_contains($level,'adult'))

                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">

                                    {{ $student->program_detail }}

                                </span>

                            @else

                                <span class="badge bg-secondary">

                                    {{ $student->program_detail }}

                                </span>

                            @endif

                            </td>
                        <td class="text-center">
                            @if($student->activePackage)
                            @php
                            $remaining = $student->remaining_sessions;
                            @endphp
                            @if($remaining>=5)
                            <span class="badge bg-success">
                                {{ $remaining }}
                            </span>
                            @elseif($remaining>=3)
                            <span class="badge bg-warning text-dark">
                                {{ $remaining }}
                            </span>
                            @elseif($remaining>=1)
                            <span class="badge bg-danger">
                                {{ $remaining }}
                            </span>
                            @else
                            <span class="badge bg-dark">
                                0
                            </span>
                            @endif
                            @else
                            -
                            @endif
                        </td>
                        @if(auth()->user()->role != 'tutor')
                        <td>

                            @if($student->status_pembayaran == 'Lunas')
                                <span class="badge bg-success">
                                    Lunas
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Belum Bayar
                                </span>
                            @endif

                        </td>
                        @endif
                        <td>
                            <span class="badge {{ $student->status_badge }}">
                                {{ $student->status_label }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('students.show',$student->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->role != 'tutor')
                            <a href="{{ route('students.edit',$student->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin hapus?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role == 'tutor' ? 8 : 9 }}">
                            Tidak ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
        </table>
    </div>

</div>
<x-pagination :data="$students" />
@push('styles')

<style>

.card{

transition:.25s;

}

.card:hover{

transform:translateY(-2px);

}

.table tbody tr{

transition:.2s;

}

.table tbody tr:hover{

background:#f8fbff;

}

.badge{

font-weight:600;

padding:.55em .8em;

}

</style>

@endpush
@endsection