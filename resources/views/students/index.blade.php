@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Siswa</h2>
    <a href="{{ route('students.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>

<form method="GET" action="{{ route('students.index') }}" class="mb-3">
    <div class="input-group">
        <input type="text" 
            name="search" 
            id="searchInput"
            class="form-control" 
            placeholder="Cari nama / no registrasi..."
            value="{{ request('search') }}">

        <button class="btn btn-primary">Cari</button>
    </div>
</form>
<div class="student-card-list d-flex flex-column gap-3">
    @foreach($students as $student)
        <div class="card shadow-sm border">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-0 fw-semibold">
                        {{ $loop->iteration }}. {{ $student->name }}
                    </h5>
                    <small class="text-muted">Reg: {{ $student->registration_number }}</small>
                </div>

                {{-- STATUS PEMBAYARAN --}}
                <div>
                    @if($student->status_pembayaran == 'Lunas')
                        <span class="badge bg-success">Lunas</span>

                    @elseif($student->status_pembayaran == 'Cicilan')
                        <span class="badge bg-warning text-dark">Cicilan</span>

                    @else
                        <span class="badge bg-danger">Belum Bayar</span>
                    @endif
                </div>
            </div>

            @php
                $totalTagihan = $student->payments->sum('amount_due');
                $totalBayar = $student->payments->sum('amount_paid');
                $sisaTagihan = $totalTagihan - $totalBayar;
            @endphp
            {{-- INFO PEMBAYARAN --}}
            <div class="small mt-1">
                <b>Total Tagihan:</b>
                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
            </div>

            <div class="small">
                <b>Total Bayar:</b>
                Rp {{ number_format($totalBayar, 0, ',', '.') }}
            </div>

            <div class="small">
                <b>Sisa Tagihan:</b>
                Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
            </div>

            {{-- STATUS SISWA --}}
            <div class="d-flex gap-2 mt-2">
                @if($student->status == 'Active')
                    <span class="badge bg-success-subtle text-success">{{ $student->status }}</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">{{ $student->status }}</span>
                @endif
            </div>

            {{-- META --}}
            <div class="small text-muted mt-2">
                {{ $student->program }} • {{ $student->level }}
            </div>
            <div class="small">
                {{ $student->schedule_type }} | 
                {{ $student->intensity }} | 
                {{ $student->family_status }}
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-2">
                <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" 
                class="btn btn-sm btn-primary">Bayar</a>

                <a href="{{ route('students.show', $student->id) }}" 
                class="btn btn-sm btn-outline-info">Detail</a>

                <a href="{{ route('students.edit', $student->id) }}" 
                class="btn btn-sm btn-outline-warning">Edit</a>

                <form action="{{ route('students.destroy', $student->id) }}" method="POST">
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

        @push('scripts')
        <script>
        let timeout = null;

        const input = document.getElementById('searchInput');

        // 🔥 ambil posisi cursor terakhir dari localStorage
        const savedPos = localStorage.getItem('cursorPos');
        const savedValue = localStorage.getItem('searchValue');

        if (input) {

            // 🔥 balikin value & cursor setelah reload
            if (savedValue !== null) {
                input.value = savedValue;
            }

            if (savedPos !== null) {
                input.focus();
                input.setSelectionRange(savedPos, savedPos);
            }

            input.addEventListener('input', function () {
                clearTimeout(timeout);

                // 🔥 simpan posisi cursor & isi input
                localStorage.setItem('cursorPos', this.selectionStart);
                localStorage.setItem('searchValue', this.value);

                timeout = setTimeout(() => {
                    this.form.submit();
                }, 400);
            });
        }
        </script>
@endpush