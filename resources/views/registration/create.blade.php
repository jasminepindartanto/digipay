<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f5f7fb">

<div class="container py-5">

    <div class="card shadow-sm mx-auto" style="max-width:700px">
        <div class="card-body p-4">

            <h3 class="mb-4">
                Form Pendaftaran Siswa
            </h3>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Sekolah</label>
                    <input type="text" name="school" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Kelas</label>
                    <input type="text" name="class" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Program</label>
                    <select name="program" class="form-control" required>
                        <option value="">-- Pilih Program --</option>
                        <option>Digikidz</option>
                        <option>Robotics</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>No Whatsapp Orang Tua</label>
                    <input type="text" name="parent_phone" class="form-control" required>
                </div>

                

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>

                <button class="btn btn-primary w-100">
                    Kirim Pendaftaran
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>