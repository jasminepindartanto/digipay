@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('students.index') }}">
                    Data Siswa
                </a>
            </li>
            <li class="breadcrumb-item active">
                Edit Data Siswa
            </li>
        </ol>
    </nav>
    <div class="w-full">

<!-- Inject Google Fonts, Icons & Tailwind via CDN -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: "#0050cb",
                    "primary-container": "#dae2fd",
                    "on-primary": "#ffffff",
                    tertiary: "#00655c",
                    "tertiary-container": "#89f5e7",
                    surface: "#ffffff",
                    "surface-container-low": "#f8fafc",
                    "outline-variant": "#cbd5e1",
                    "on-surface": "#0f172a",
                    "on-surface-variant": "#475569"
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                }
            }
        }
    }
</script>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>

<!-- Container w-full supaya mepet & fit ke layout utama -->
<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">
            Edit Data Siswa
        </h1>
        <p class="text-sm text-slate-500">
            Perbarui informasi siswa.
        </p>
    </div>
    
    <!-- Progress Stepper UI (Melebar Full Ujung ke Ujung) -->
    <div class="mb-8 py-2 relative w-full">
        <div class="absolute top-7 left-4 right-4 h-0.5 bg-outline-variant z-0"></div>
        <div id="stepProgressBar" class="absolute top-7 left-4 h-0.5 bg-primary transition-all duration-300 z-0" style="width: 0%;"></div>

        <div class="relative z-10 flex justify-between w-full px-2">
            <!-- Step 1 Nav Indicator -->
            <div class="flex flex-col items-center">
                <div id="stepper1" class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-semibold text-sm ring-4 ring-white transition-all">
                    1
                </div>
                <span id="labelStep1" class="mt-2 text-xs font-semibold text-primary">Data Anak</span>
            </div>
            <!-- Step 2 Nav Indicator -->
            <div class="flex flex-col items-center">
                <div id="stepper2" class="w-10 h-10 rounded-full bg-white border-2 border-outline-variant text-on-surface-variant flex items-center justify-center font-semibold text-sm ring-4 ring-white transition-all">
                    2
                </div>
                <span id="labelStep2" class="mt-2 text-xs font-medium text-on-surface-variant">Data Orang Tua</span>
            </div>
            <!-- Step 3 Nav Indicator -->
            <div class="flex flex-col items-center">
                <div id="stepper3" class="w-10 h-10 rounded-full bg-white border-2 border-outline-variant text-on-surface-variant flex items-center justify-center font-semibold text-sm ring-4 ring-white transition-all">
                    3
                </div>
                <span id="labelStep3" class="mt-2 text-xs font-medium text-on-surface-variant">Paket & Jadwal</span>
            </div>
        </div>
    </div>

    <!-- Header Card (Hanya Ikon & Nama Langkah, Tanpa Deskripsi/Step X) -->
    <div class="mb-6 flex items-center gap-3 bg-surface p-4 rounded-lg border-l-4 border-primary shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-primary shrink-0">
            <span id="stepHeaderIcon" class="material-symbols-outlined">person</span>
        </div>
        <div>
            <h2 id="stepHeaderTitle" class="text-lg font-bold text-on-surface">Data Anak</h2>
        </div>
    </div>

    <!-- Form Langsung (Tanpa Card, Full Width) -->
    <form action="{{ route('students.update', $student->id) }}" method="POST" id="studentForm" class="w-full">
        @csrf
        @method('PUT')

        <!-- ================= STEP 1: DATA ANAK ================= -->
        <div id="step1" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Nama --}}
                <div class="md:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-semibold text-on-surface mb-1">Nama Siswa</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('name', $student->name) }}" placeholder="Nama lengkap siswa" required>
                </div>

                {{-- No Registrasi --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">No Registrasi</label>
                    <input type="text" class="w-full px-3 py-2 border border-outline-variant rounded-lg bg-slate-100 text-on-surface-variant cursor-not-allowed text-sm font-medium" value="{{ $student->registration_number }}" readonly>
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('start_date', optional($student->start_date)->format('Y-m-d')) }}">
                </div>

                {{-- Tanggal Registrasi --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Tanggal Daftar</label>
                    <input type="date" name="registration_date" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('registration_date', optional($student->registration_date)->format('Y-m-d')) }}" required>
                </div>

                {{-- Registration Type --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Registration Type</label>
                    <select name="registration_type" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white">
                        <option value="New" {{ old('registration_type', $student->registration_type) == 'New' ? 'selected' : '' }}>New Student</option>
                        <option value="Renewal" {{ old('registration_type', $student->registration_type) == 'Renewal' ? 'selected' : '' }}>Renewal</option>
                    </select>
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Gender</label>
                    <select name="gender" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                {{-- Date of Birth --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}">
                </div>

                {{-- School --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Sekolah</label>
                    <input type="text" name="school" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('school', $student->school) }}" placeholder="Asal sekolah">
                </div>

                {{-- Class --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Kelas</label>
                    <input type="text" name="class" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('class', $student->class) }}" placeholder="Contoh: 3 SD / 8 SMP">
                </div>

                {{-- Child Phone --}}
                <div class="md:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-semibold text-on-surface mb-1">No HP Anak <span class="text-xs font-normal text-on-surface-variant">(Opsional)</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">smartphone</span>
                        <input type="text" name="child_phone" class="w-full pl-9 pr-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('child_phone', $student->child_phone) }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- Address --}}
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-semibold text-on-surface mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm resize-none" placeholder="Alamat domisili lengkap...">{{ old('address', $student->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- ================= STEP 2: DATA ORANG TUA ================= -->
        <div id="step2" class="space-y-6" style="display:none;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Parent Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">No HP Orang Tua / Wali</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">phone</span>
                        <input type="text" name="parent_phone" class="w-full pl-9 pr-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('parent_phone', $student->parent_phone) }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <span class="text-xs text-on-surface-variant mt-1 block">Kontak darurat utama.</span>
                </div>

                {{-- Parent Email --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Email Orang Tua</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">mail</span>
                        <input type="email" name="parent_email" class="w-full pl-9 pr-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('parent_email', $student->parent_email) }}" placeholder="email@domain.com">
                    </div>
                </div>

                {{-- Parent Instagram --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Instagram Orang Tua</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">alternate_email</span>
                        <input type="text" name="parent_instagram" class="w-full pl-9 pr-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm" value="{{ old('parent_instagram', $student->parent_instagram) }}" placeholder="username_ig">
                    </div>
                </div>
            </div>

            <div class="p-4 bg-tertiary-container/30 border-l-4 border-tertiary rounded-r-lg flex gap-3 items-start mt-6">
                <span class="material-symbols-outlined text-tertiary shrink-0">info</span>
                <p class="text-xs text-on-surface">Pastikan email dan nomor WhatsApp orang tua aktif untuk keperluan pengiriman konfirmasi jadwal dan update perkembangan belajar anak.</p>
            </div>
        </div>

        <!-- ================= STEP 3: PAKET & JADWAL ================= -->
        <div id="step3" class="space-y-6" style="display:none;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Program --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Program Belajar</label>
                    <select name="program" id="program" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white" required>
                        <option value="">-- Pilih Program --</option>
                        <option value="Digikidz" {{ old('program', $student->program) == 'Digikidz' ? 'selected' : '' }}>Digikidz</option>
                    </select>
                </div>
                
                {{-- Program Detail --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Level / Specialization</label>
                    <select name="program_detail" id="program_detail" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white" required>
                        <option value="">-- Pilih Program Dulu --</option>
                    </select>
                </div>

                {{-- Paket --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Package Type</label>
                    <select name="package_type" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white">
                        <option value="Monthly" {{ old('package_type', $student->package_type) == 'Monthly' ? 'selected' : '' }}>Monthly (4 Sesi)</option>
                        <option value="1 Level" {{ old('package_type', $student->package_type) == '1 Level' ? 'selected' : '' }}>1 Level (16 Sesi)</option>
                        <option value="Full Course" {{ old('package_type', $student->package_type) == 'Full Course' ? 'selected' : '' }}>Full Course (48 Sesi)</option>
                    </select>
                </div>

                {{-- Schedule --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Jadwal Hari</label>
                    <select name="schedule_type" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white">
                        <option value="Weekday" {{ old('schedule_type', $student->schedule_type) == 'Weekday' ? 'selected' : '' }}>Weekday (Senin - Jumat)</option>
                        <option value="Sabtu" {{ old('schedule_type', $student->schedule_type) == 'Sabtu' ? 'selected' : '' }}>Sabtu Khusus</option>
                    </select>
                </div>

                {{-- Intensitas --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Intensitas Pertemuan</label>
                    <select name="intensity" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white">
                        <option value="Regular" {{ old('intensity', $student->intensity) == 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="Intensif" {{ old('intensity', $student->intensity) == 'Intensif' ? 'selected' : '' }}>Intensif</option>
                    </select>
                </div>

                {{-- Family --}}
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1">Status Family</label>
                    <select name="family_status" class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm bg-white">
                        <option value="Family" {{ old('family_status', $student->family_status) == 'Family' ? 'selected' : '' }}>Family</option>
                        <option value="Non Family" {{ old('family_status', $student->family_status) == 'Non Family' ? 'selected' : '' }}>Non Family</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ================= WIZARD NAVIGATION BUTTONS ================= -->
        <div class="mt-10 pt-6 border-t border-outline-variant flex items-center justify-between">
            <button
                type="button"
                id="prevBtn"
                class="flex items-center gap-1 px-5 py-2.5 rounded-lg border border-outline-variant text-on-surface-variant hover:bg-slate-100 font-semibold text-sm transition-all"
                style="display:none;">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Sebelumnya
            </button>

            <div class="ml-auto flex gap-3">
                <button
                    type="button"
                    id="nextBtn"
                    class="flex items-center gap-1 px-6 py-2.5 rounded-lg bg-primary text-on-primary hover:bg-[#0040a8] font-semibold text-sm shadow transition-all">
                    Selanjutnya
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>

                <button
                    type="submit"
                    id="submitBtn"
                    class="flex items-center gap-1 px-6 py-2.5 rounded-lg bg-tertiary text-white hover:bg-[#004d46] font-semibold text-sm shadow transition-all"
                    style="display:none;">
                    Update Data
                    <span class="material-symbols-outlined text-sm">check</span>
                </button>
            </div>
        </div>
    </form>
</div>
</div>

@endsection

@push('scripts')
<script>
    // 1. DYNAMIC PROGRAM DETAIL OPTIONS
    const programSelect = document.getElementById('program');
    const programDetailSelect = document.getElementById('program_detail');
    const oldDetail = "{{ old('program_detail', $student->program_detail) }}";

    const programDetails = {
        Digikidz: [
            "Little Creator 1", "Little Creator 2",
            "Junior 1", "Junior 2",
            "Teenager 1", "Teenager 2", "Teenager 3", "Teenager 4"
        ],
    };

    function updateProgramDetails(selectedProgram, detailToSelect = null) {
        programDetailSelect.innerHTML = '<option value="">-- Pilih Program Detail --</option>';
        if (programDetails[selectedProgram]) {
            programDetails[selectedProgram].forEach(programDetail => {
                const option = document.createElement('option');
                option.value = programDetail;
                option.textContent = programDetail;
                if (programDetail === detailToSelect) {
                    option.selected = true;
                }
                programDetailSelect.appendChild(option);
            });
        }
    }

    programSelect.addEventListener('change', function () {
        updateProgramDetails(this.value);
    });

    if (programSelect.value) {
        updateProgramDetails(programSelect.value, oldDetail);
    }

    // 2. WIZARD STEPPER LOGIC
    let currentStep = 1;
    const steps = [
        document.getElementById('step1'),
        document.getElementById('step2'),
        document.getElementById('step3')
    ];

    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    const stepProgressBar = document.getElementById('stepProgressBar');
    const stepHeaderIcon = document.getElementById('stepHeaderIcon');
    const stepHeaderTitle = document.getElementById('stepHeaderTitle');

    // Array header hanya menyimpan nama singkat dan ikon
    const headers = [
        { icon: "person", title: "Data Anak" },
        { icon: "contact_page", title: "Data Orang Tua" },
        { icon: "assignment", title: "Paket & Jadwal" }
    ];

    function showStep(step) {
        steps.forEach((section, index) => {

        const inputs = section.querySelectorAll(
            'input, select, textarea'
        );

        if(index === step-1){

            section.style.display = 'block';

            inputs.forEach(input=>{
                input.disabled = false;
            });

        }else{

            section.style.display='none';

            inputs.forEach(input=>{
                input.disabled = true;
            });

        }

    });

        // Update teks card tanpa kata "Step X:"
        stepHeaderIcon.textContent = headers[step - 1].icon;
        stepHeaderTitle.textContent = headers[step - 1].title;

        for (let i = 1; i <= 3; i++) {
            const circle = document.getElementById('stepper' + i);
            const label = document.getElementById('labelStep' + i);
            
            if (i < step) {
                circle.className = "w-10 h-10 rounded-full bg-tertiary text-white flex items-center justify-center font-semibold text-sm transition-all";
                circle.innerHTML = '<span class="material-symbols-outlined text-base">check</span>';
                label.className = "mt-2 text-xs font-semibold text-tertiary";
            } else if (i === step) {
                circle.className = "w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-semibold text-sm ring-4 ring-white transition-all";
                circle.innerHTML = i;
                label.className = "mt-2 text-xs font-bold text-primary";
            } else {
                circle.className = "w-10 h-10 rounded-full bg-white border-2 border-outline-variant text-on-surface-variant flex items-center justify-center font-semibold text-sm ring-4 ring-white transition-all";
                circle.innerHTML = i;
                label.className = "mt-2 text-xs font-medium text-on-surface-variant";
            }
        }

        stepProgressBar.style.width = ((step - 1) * 50) + '%';

        prevBtn.style.display = (step === 1) ? 'none' : 'inline-flex';
        nextBtn.style.display = (step === 3) ? 'none' : 'inline-flex';
        submitBtn.style.display = (step === 3) ? 'inline-flex' : 'none';
    }

    nextBtn.onclick = function () {
        if (currentStep < 3) {
            currentStep++;
            showStep(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };

    prevBtn.onclick = function () {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };

    showStep(1);

    document.getElementById('studentForm').addEventListener('submit', function () {

        this.querySelectorAll('input, select, textarea').forEach(function (el) {
            el.disabled = false;
        });

    });
    
</script>
@endpush