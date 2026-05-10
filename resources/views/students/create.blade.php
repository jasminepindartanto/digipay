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

        {{-- Tanggal Registrasi --}}
        <div class="col-md-6">
            <label>Tanggal Registrasi</label>
            <input type="date" name="registration_date" class="form-control" required>
        </div>

        {{-- Program --}}
        <div class="col-md-6">
            <label>Program</label>
            <select name="program" id="program" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="Digikidz">Digikidz</option>
                <option value="Digischool">Digischool</option>
            </select>
        </div>

        {{-- Level --}}
        <div class="col-md-6">
            <label>Level</label>
            <select name="level" id="level" class="form-control" required>
                <option value="">-- Pilih Program Dulu --</option>
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
            <label>Class</label>
            <input type="text" name="class" class="form-control">
        </div>

        {{-- Address --}}
        <div class="col-md-12">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
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

    </div>

    <button class="btn btn-success mt-3">Simpan</button>
</form>

@endsection


@push('scripts')
<script>
    const programSelect = document.getElementById('program');
    const levelSelect = document.getElementById('level');

    const levels = {
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
        Digischool: [
            "TK",
            "SD",
            "SMP",
            "SMA"
        ]
    };

    programSelect.addEventListener('change', function () {
        const selected = this.value;

        levelSelect.innerHTML = '<option value="">-- Pilih Level --</option>';

        if (levels[selected]) {
            levels[selected].forEach(level => {
                const option = document.createElement('option');
                option.value = level;
                option.textContent = level;
                levelSelect.appendChild(option);
            });
        }
    });
</script>
@endpush