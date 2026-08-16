@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Pendaftaran</h2>
    <a href="{{ route('registrations.create') }}" class="btn btn-primary">
        + Tambah Pendaftaran
    </a>
</div>

{{-- 1. TABEL PENDING --}}
<h4>Pending</h4>

<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>Nama</th>
            <th>Program</th>
            <th>Kelas</th>
            <th>Umur</th>
            <th>No HP Orang Tua</th>
            <th>Tanggal Daftar</th>
            <th>Status</th>
            <th width="380">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pending as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->program }}</td>
            <td>{{ $student->class }}</td>
            <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->age ?? '-' }}</td>
            <td>{{ $student->parent_phone }}</td>
            <td>{{ $student->created_at->format('d M Y') }}</td>
            <td>
                <span class="badge bg-warning text-dark">Pending</span>
            </td>
            <td>
                <a href="{{ route('registrations.show', $student->id) }}" class="btn btn-info btn-sm w-100 mb-2">
                    Lihat Data
                </a>

                @if($student->source == 'online')
                    <a href="{{ route('registrations.edit', $student->id) }}" class="btn btn-warning btn-sm w-100 mb-2">
                        Edit
                    </a>
                @endif

                <form action="{{ route('registrations.approve', $student->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100">
                        Terima
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-3">
                Belum ada pendaftaran pending
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- HEADER SUDAH DIPROSES --}}
<div class="d-flex justify-content-between align-items-center mt-5 mb-3">
    <h4 class="mb-0">Sudah Diproses</h4>
    <form method="GET" action="{{ route('registrations.index') }}">
        <input type="date"
               name="date"
               value="{{ $selectedDate ?? '' }}"
               class="form-control form-control-sm"
               onchange="this.form.submit()">
    </form>
</div>
    {{-- 2. TABEL APPROVED --}}
    <h5 class="mt-3 text-success">Diterima</h5>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Program</th>
                <th>Package</th>
                <th>Registration</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Tanggal Diproses</th>
            </tr>
        </thead>

        <tbody>
            @forelse($approved as $registration)
                <tr>
                    <td>{{ $registration->name }}</td>

                    <td>{{ $registration->program }}</td>

                    <td>{{ $registration->package_type }}</td>

                    <td>{{ $registration->registration_type }}</td>

                    <td>{{ $registration->class }}</td>

                    <td>
                        <span class="badge bg-success">
                            Approved
                        </span>
                    </td>

                    <td>
                        {{ $registration->approved_at
                            ? $registration->approved_at->format('d M Y')
                            : '-' }}
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">
                        Belum ada data murid yang diterima
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </tbody>
</table>
@endsection

@push('scripts')
<script>
    // ── 1. SCRIPT PROGRAM DETAIL (BAWAAN) ──
    const programDetails = {
        Digikidz: [
            "Little Creator 1",
            "Little Creator 2",
            "Junior 1",
            "Junior 2",
            "Teenager 1",
            "Teenager 2",
            "Teenager 3",
            "Teenager 4"
        ],
    };

    document.querySelectorAll('.program-select').forEach(select => {
        function load() {
            let id = select.dataset.target;
            let detail = document.getElementById('program_detail' + id);
            
            if (detail && programDetails[select.value]) {
                detail.innerHTML = '';
                programDetails[select.value].forEach(item => {
                    detail.innerHTML += `<option value="${item}">${item}</option>`;
                });
            }
        }

        load();
        select.addEventListener('change', load);
    });

    // ── 2. SCRIPT MODAL PENOLAKAN BOOTSTRAP (ANTI ERROR) ──
    let rejectModalInstance = null;
    function toggleOtherReason() {
        const value = document.getElementById('rejectOption').value;
        const other = document.getElementById('otherReason');
        const otherText = document.getElementById('otherText');

        if (value === 'other') {
            other.classList.remove('d-none');
            otherText.required = true;
        } else {
            other.classList.add('d-none');
            otherText.required = false;
        }
    }

    function prepareRejectReason() {
        const option = document.getElementById('rejectOption').value;
        const hidden = document.getElementById('rejectReason');
        const otherText = document.getElementById('otherText').value.trim();

        if (option === 'other') {
            if (!otherText) {
                alert('Silakan isi alasan lainnya terlebih dahulu.');
                return false;
            }
            hidden.value = otherText;
        } else {
            hidden.value = option;
        }
        return true;
    }
</script>
@endpush