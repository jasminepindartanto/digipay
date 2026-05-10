@extends('layouts.app')

@section('content')

<h2>Edit Siswa</h2>

<form action="{{ route('students.update', $student->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-3">

        <div class="col-md-6">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ $student->name }}" required>
        </div>

        <div class="col-md-6">
            <label>No Registrasi</label>
            <input type="text" name="registration_number" class="form-control" 
                   value="{{ $student->registration_number }}" required>
        </div>

        <div class="col-md-6">
            <label>Tanggal Registrasi</label>
            <input type="date"
                    name="registration_date"
                    class="form-control"
                    value="{{ old('registration_date', \Carbon\Carbon::parse($student->registration_date)->format('Y-m-d')) }}">
        </div>

        <div class="col-md-6">
            <label>Program</label>
            <select name="program" id="program" class="form-control">
                <option value="Digikidz" {{ $student->program == 'Digikidz' ? 'selected' : '' }}>Digikidz</option>
                <option value="Digischool" {{ $student->program == 'Digischool' ? 'selected' : '' }}>Digischool</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Level</label>
            <select name="level" id="level" class="form-control">
                <option value="">-- Pilih Level --</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Jadwal</label>
            <select name="schedule_type" class="form-control">
                <option value="Weekday" {{ $student->schedule_type == 'Weekday' ? 'selected' : '' }}>Weekday</option>
                <option value="Sabtu" {{ $student->schedule_type == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Intensitas</label>
            <select name="intensity" class="form-control">
                <option value="Regular" {{ $student->intensity == 'Regular' ? 'selected' : '' }}>Regular</option>
                <option value="Intensif" {{ $student->intensity == 'Intensif' ? 'selected' : '' }}>Intensif</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Status Family</label>
            <select name="family_status" class="form-control">
                <option value="Family" {{ $student->family_status == 'Family' ? 'selected' : '' }}>Family</option>
                <option value="Non Family" {{ $student->family_status == 'Non Family' ? 'selected' : '' }}>Non Family</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>Tanggal Lahir</label>
            <input type="date"
                    name="date_of_birth"
                    class="form-control"
                    value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
        </div>

        <div class="col-md-6">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $student->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-6">
            <label>No HP Orang Tua</label>
            <input type="text" name="parent_phone" class="form-control"
                   value="{{ $student->parent_phone }}">
        </div>

        <div class="col-md-6">
            <label>Sekolah</label>
            <input type="text" name="school" class="form-control"
                   value="{{ $student->school }}">
        </div>

        <div class="col-md-6">
            <label>Class</label>
            <input type="text" name="class" class="form-control"
                   value="{{ $student->class }}">
        </div>

        <div class="col-md-12">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ $student->address }}</textarea>
        </div>

        <div class="col-md-6">
            <label>No HP Anak</label>
            <input type="text" name="child_phone" class="form-control"
                   value="{{ $student->child_phone }}">
        </div>

        <div class="col-md-6">
            <label>Email Orang Tua</label>
            <input type="email" name="parent_email" class="form-control"
                   value="{{ $student->parent_email }}">
        </div>

        <div class="col-md-6">
            <label>Instagram Orang Tua</label>
            <input type="text" name="parent_instagram" class="form-control"
                   value="{{ $student->parent_instagram }}">
        </div>

    </div>

    <button class="btn btn-success mt-3">Update</button>
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

    function loadLevels(selectedProgram, selectedLevel = null) {
        levelSelect.innerHTML = '';

        if (levels[selectedProgram]) {
            levels[selectedProgram].forEach(level => {
                const option = document.createElement('option');

                option.value = level;
                option.textContent = level;

                if (selectedLevel === level) {
                    option.selected = true;
                }

                levelSelect.appendChild(option);
            });
        }
    }

    // saat pertama kali halaman edit dibuka
    loadLevels(programSelect.value, "{{ $student->level }}");

    // saat program diganti
    programSelect.addEventListener('change', function () {
        loadLevels(this.value);
    });
</script>
@endpush