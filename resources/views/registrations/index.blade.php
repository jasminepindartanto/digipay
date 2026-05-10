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
            <td>{{ $reg->parent_phone }}</td>
            <td>{{ $reg->created_at->format('d M Y') }}</td>

            <td>
                <span class="badge bg-warning text-dark">
                    Pending
                </span>
            </td>

            <td>
                <div class="d-flex gap-2">

                    {{-- APPROVE --}}
                    <form action="{{ route('registrations.approve', $reg->id) }}"
                          method="POST"
                          onsubmit="return confirm('Approve pendaftaran ini?')">

                        @csrf

                        <button class="btn btn-success btn-sm">
                            Approve
                        </button>

                    </form>

                    {{-- REJECT --}}
                    <form action="{{ route('registrations.reject', $reg->id) }}"
                          method="POST"
                          onsubmit="return confirm('Tolak pendaftaran ini?')">

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