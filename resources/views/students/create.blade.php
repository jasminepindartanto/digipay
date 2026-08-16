@extends('layouts.app')

@section('content')

<h2>Tambah Siswa</h2>

<form action="{{ route('students.store') }}" method="POST">
    @csrf

    <div class="row g-3">

        {{-- Nama --}}
        <div class="col-md-6">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- No Registrasi --}}
        <div class="col-md-6">
            <label>No Registrasi</label>
            <input type="text"
                class="form-control"
                value="{{ $registrationNumber }}"
                readonly>
        </div>

        {{-- Tanggal Mulai --}}
        <div class="col-md-6">
            <label>Tanggal Mulai</label>

            <input
                type="date"
                name="start_date"
                class="form-control"
                value="{{ old('start_date') }}">
        </div>

        {{-- Tanggal Registrasi --}}
        <div class="col-md-6">
            <label>Tanggal Daftar</label>
            <input type="date" name="registration_date" class="form-control" required>
        </div>

        {{-- Program --}}
        <div class="col-md-6">
            <label>Program</label>
            <select name="program" id="program" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="Digikidz">Digikidz</option>
            </select>
        </div>

        
        {{-- Program Detail --}}
        <div class="col-md-6">
            <label>Level</label>
            <select name="program_detail" id="program_detail" class="form-control" required>
                <option value="">-- Pilih Program Dulu --</option>
            </select>
        </div>

        {{-- Paket --}}
        <div class="col-md-6">
            <label>Package Type</label>
            <select
                name="package_type"
                class="form-control"
            >
                <option value="Monthly">
                    Monthly (4 Sesi)
                </option>

                <option value="1 Level">
                    1 Level (16 Sesi)
                </option>

                <option value="Full Course">
                    Full Course (48 Sesi)
                </option>

            </select>

        </div>

        {{-- Schedule --}}
        <div class="col-md-6">
            <label>Jadwal</label>
            <select name="schedule_type" class="form-control">
                <option value="Weekday">Weekday</option>
                <option value="Sabtu">Sabtu</option>
            </select>
        </div>

        {{-- Intensitas --}}
        <div class="col-md-6">
            <label>Intensitas</label>
            <select name="intensity" class="form-control">
                <option value="Regular">Regular</option>
                <option value="Intensif">Intensif</option>
            </select>
        </div>

        {{-- Family --}}
        <div class="col-md-6">
            <label>Status Family</label>
            <select name="family_status" class="form-control">
                <option value="Family">Family</option>
                <option value="Non Family">Non Family</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Registration Type</label>
            <select name="registration_type" class="form-select">
                <option value="New">New Student</option>
                <option value="Renewal">Renewal</option>
            </select>
        </div>

        {{-- Gender --}}
        <div class="col-md-6">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        
        {{-- Date of Birth --}}
        <div class="col-md-6">
            <label>Tanggal Lahir</label>
            <input type="date" name="date_of_birth" class="form-control">
        </div>

        {{-- Status --}}
        <div class="col-md-6">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        {{-- Parent Phone --}}
        <div class="col-md-6">
            <label>No HP Orang Tua</label>
            <input type="text" name="parent_phone" class="form-control">
        </div>

        {{-- School --}}
        <div class="col-md-6">
            <label>Sekolah</label>
            <input type="text" name="school" class="form-control">
        </div>

        {{-- Class --}}
        <div class="col-md-6">
            <label>Kelas</label>
            <input type="text" name="class" class="form-control">
        </div>

        {{-- Child Phone --}}
        <div class="col-md-6">
            <label>No HP Anak</label>
            <input type="text" name="child_phone" class="form-control">
        </div>

        {{-- Parent Email --}}
        <div class="col-md-6">
            <label>Email Orang Tua</label>
            <input type="email" name="parent_email" class="form-control">
        </div>

        {{-- Parent Instagram --}}
        <div class="col-md-6">
            <label>Instagram Orang Tua</label>
            <input type="text" name="parent_instagram" class="form-control">
        </div>

        {{-- Address --}}
        <div class="col-md-12">
            <label>Alamat</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

    </div>

    <button class="btn btn-success mt-3">Simpan</button>
</form>

@endsection


@push('scripts')
<script>
    const programSelect = document.getElementById('program');
    const programDetailSelect = document.getElementById('program_detail');

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

    programSelect.addEventListener('change', function () {

        const selected = this.value;

        programDetailSelect.innerHTML =
            '<option value="">-- Pilih Program Detail --</option>';

        if (programDetails[selected]) {

            programDetails[selected].forEach(programDetail => {

                const option = document.createElement('option');

                option.value = programDetail;
                option.textContent = programDetail;

                programDetailSelect.appendChild(option);

            });

        }

    });
</script>
@endpush