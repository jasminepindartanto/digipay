@extends('layouts.app')

@section('content')

<h2 class="mb-4">Data Pendaftaran</h2>

{{-- PENDING --}}
<h4>Pending</h4>

<table class="table table-bordered align-middle">

    <thead>
        <tr>
            <th>Nama</th>
            <th>Program</th>
            <th>Kelas</th>
            <th>Umur</th>
            <th>No HP Orang Tua</th>
            <th>Tanggal Daftar</th>
            <th>Status</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>

    <tbody>

        @forelse($registrations as $reg)
        <tr>

            <td>{{ $reg->name }}</td>
            <td>{{ $reg->program }}</td>
            <td>{{ $reg->class }}</td>
            <td>{{ \Carbon\Carbon::parse($reg->date_of_birth)->age ?? '-' }}</td>
            <td>{{ $reg->parent_phone }}</td>
            <td>{{ $reg->created_at->format('d M Y') }}</td>

            <td>
                <span class="badge bg-warning text-dark">
                    Pending
                </span>
            </td>

            <td width="350">

            <form action="{{ route('registrations.approve', $reg->id) }}"
                method="POST">

                @csrf

                <div class="mb-2">

                    <select name="program"
                            class="form-control form-control-sm"
                            required>

                        <option value="" disabled selected hidden>
                            Program
                        </option>

                        <option value="Digikidz">
                            Digikidz
                        </option>

                        <option value="Digischool">
                            Digischool
                        </option>

                    </select>

                </div>

                <div class="mb-2">

                    <select name="program_detail"
                            class="form-control form-control-sm"
                            required>

                        <option value="" disabled selected hidden>
                            Program Detail
                        </option>

                        <option value="Little Creator 1">
                            Little Creator 1
                        </option>

                        <option value="Little Creator 2">
                            Little Creator 2
                        </option>

                        <option value="Junior 1">
                            Junior 1
                        </option>

                        <option value="Junior 2">
                            Junior 2
                        </option>

                        <option value="Teenager 1">
                            Teenager 1
                        </option>

                        <option value="Teenager 2">
                            Teenager 2
                        </option>

                        <option value="Teenager 3">
                            Teenager 3
                        </option>

                        <option value="Teenager 4">
                            Teenager 4
                        </option>

                    </select>

                </div>

                <div class="mb-2">

                    <select name="schedule_type"
                            class="form-control form-control-sm">

                        <option value="Weekday">
                            Weekday
                        </option>

                        <option value="Sabtu">
                            Sabtu
                        </option>

                    </select>

                </div>

                <div class="mb-2">

                    <select name="intensity"
                            class="form-control form-control-sm">

                        <option value="Regular">
                            Regular
                        </option>

                        <option value="Intensif">
                            Intensif
                        </option>

                    </select>

                </div>

                <div class="mb-2">

                    <select name="family_status"
                            class="form-control form-control-sm">

                        <option value="Family">
                            Family
                        </option>

                        <option value="Non Family">
                            Non Family
                        </option>

                    </select>

                </div>

                <div class="d-flex gap-2">

                    <button class="btn btn-success btn-sm">
                        Approve
                    </button>

            </form>

            <form action="{{ route('registrations.reject', $reg->id) }}"
                method="POST">

                @csrf

                <button class="btn btn-danger btn-sm">
                    Reject
                </button>

            </form>

                </div>

        </td>

        </tr>

        @empty

        <tr>
            <td colspan="7" class="text-center">
                Belum ada pendaftaran pending
            </td>
        </tr>

        @endforelse

    </tbody>

</table>

{{-- SUDAH DIPROSES --}}
<div class="d-flex justify-content-between align-items-center mt-5 mb-3">

    <h4 class="mb-0">Sudah Diproses</h4>

    <form method="GET" action="{{ route('registrations.index') }}">
        <input type="date"
               name="date"
               value="{{ $selectedDate }}"
               class="form-control"
               onchange="this.form.submit()">
    </form>

</div>

<table class="table table-bordered align-middle">

    <thead>
        <tr>
            <th>Nama</th>
            <th>Program</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Tanggal Diproses</th>
        </tr>
    </thead>

    <tbody>

        @forelse($processed as $reg)
        <tr>

            <td>{{ $reg->name }}</td>
            <td>{{ $reg->program }}</td>
            <td>{{ $reg->class }}</td>

            <td>
                @if($reg->status == 'approved')

                    <span class="badge bg-success">
                        Approved
                    </span>

                @else

                    <span class="badge bg-danger">
                        Rejected
                    </span>

                @endif
            </td>

            <td>{{ $reg->updated_at->format('d M Y') }}</td>

        </tr>

        @empty

        <tr>
            <td colspan="5" class="text-center">
                Belum ada data diproses
            </td>
        </tr>

        @endforelse

    </tbody>

</table>

@endsection