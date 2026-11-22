@extends('layout.app')

@section('content')
    @php
        $isEditing = isset($task);
        $startTime = $isEditing && $task->start_time ? substr($task->start_time, 0, 5) : '19:00';
        $endTime = $isEditing && $task->end_time ? substr($task->end_time, 0, 5) : '20:00';
        $reminderMinutes = $isEditing ? $task->reminders->first()?->reminder_time ?? 0 : 15;
    @endphp

    <x-navbar />
    <div class="dp-page dp-container"
        style="min-height: calc(100vh - 80px); width: 100%; background: #FDF8E2; padding: 34px 8%; box-sizing: border-box; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column; gap: 22px;">

        <!-- Header Halaman -->
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <h1 style="color: #233D7B; font-size: 30px; font-weight: 800; line-height: 1.2; margin: 0;">
                {{ $isEditing ? 'Edit Tugas' : 'Buat Tugas Baru' }}</h1>
            <p class="dp-subtitle" style="color: #78829B; font-size: 15px; margin: 0;">
                {{ $isEditing ? 'Perbarui detail tugas agar rencanamu tetap rapi.' : 'Tambahkan detail agar rencanamu lebih mudah diselesaikan.' }}
            </p>
        </div>

        <!-- Alert Error Validasi -->
        @if ($errors->any())
            <div
                style="background: #FCE1E1; border: 1px solid #E95D62; color: #9B1C1C; padding: 14px 18px; border-radius: 12px; font-size: 14px; font-weight: 700;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Grid Utama: Form Kiri & Ilustrasi/Tips Kanan -->
        <div class="create-task-grid" style="display: grid; grid-template-columns: 1fr; gap: 28px; align-items: start;">

            <!-- KOLOM KIRI: FORM BUAT TUGAS -->
            <div class="dp-surface" style="padding: 28px; background: white;">
                <form action="{{ isset($task) ? route('task.update', $task) : route('task.store') }}" method="POST"
                    style="display: flex; flex-direction: column; gap: 18px;">
                    @csrf
                    @if (isset($task))
                        @method('PUT')
                    @endif

                    <!-- Judul Tugas -->
                    <div style="display: flex; flex-direction: column; gap: 7px;">
                        <label for="title" style="color: #233D7B; font-size: 13px; font-weight: 800;">Judul tugas</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $task->title ?? '') }}"
                            placeholder="Contoh: Persiapan presentasi Biologi" required
                            style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                    </div>

                    <!-- Deskripsi -->
                    <div style="display: flex; flex-direction: column; gap: 7px;">
                        <label for="description"
                            style="color: #233D7B; font-size: 13px; font-weight: 800;">Deskripsi</label>
                        <textarea id="description" name="description" rows="3"
                            placeholder="Susun poin utama, visual pendukung, dan latihan presentasi bersama kelompok."
                            style="width: 100%; padding: 13px 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; resize: vertical; box-sizing: border-box;">{{ old('description', $task->notes ?? '') }}</textarea>
                    </div>

                    <!-- BAGIAN SUBTASK (LANGKAH-LANGKAH TUGAS) -->
                    <div
                        style="display: flex; flex-direction: column; gap: 10px; background: #FDF8E2; padding: 16px; border-radius: 14px; border: 1px dashed #233D7B;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="color: #233D7B; font-size: 13px; font-weight: 800; margin: 0;">
                                Langkah / Subtask (Opsional)
                            </label>
                            <button type="button" id="btn-add-subtask"
                                style="padding: 6px 12px; background: #233D7B; color: #FDF8E2; font-size: 12px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer;">
                                + Tambah Langkah
                            </button>
                        </div>

                        <!-- Kontainer tempat baris input subtask muncul -->
                        @php($oldSubtasks = old('subtasks'))
                        <div id="subtask-container" style="display: flex; flex-direction: column; gap: 8px;">
                            @if ($oldSubtasks !== null)
                                {{-- Validasi gagal: kembalikan apa yang baru saja diketik user --}}
                                @foreach ($oldSubtasks as $subTitle)
                                    <div class="subtask-row" style="display: flex; gap: 8px; align-items: center;">
                                        <input type="text" name="subtasks[]" value="{{ $subTitle }}"
                                            placeholder="Contoh: Rangkum Bab 1..."
                                            style="flex: 1; height: 42px; padding: 0 12px; background: white; border-radius: 10px; border: 1px solid #233D7B; color: #233D7B; font-size: 13px; font-family: 'Nunito Sans', sans-serif; outline: none;">
                                        <button type="button" class="btn-remove-subtask"
                                            style="width: 38px; height: 42px; background: #FCE1E1; color: #A63A3A; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                                            <svg class="dp-icon" fill="none" stroke="#A63A3A" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M6 6l12 12M18 6L6 18"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @elseif (isset($task) && $task->subtasks->count() > 0)
                                {{-- Mode edit: ambil subtask yang sudah ada di database --}}
                                @foreach ($task->subtasks as $sub)
                                    <div class="subtask-row" style="display: flex; gap: 8px; align-items: center;">
                                        <input type="text" name="subtasks[]" value="{{ $sub->title }}"
                                            placeholder="Contoh: Rangkum Bab 1..."
                                            style="flex: 1; height: 42px; padding: 0 12px; background: white; border-radius: 10px; border: 1px solid #233D7B; color: #233D7B; font-size: 13px; font-family: 'Nunito Sans', sans-serif; outline: none;">
                                        <button type="button" class="btn-remove-subtask"
                                            style="width: 38px; height: 42px; background: #FCE1E1; color: #A63A3A; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                                            <svg class="dp-icon" fill="none" stroke="#A63A3A" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M6 6l12 12M18 6L6 18"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                {{-- Mode tambah: siapkan satu baris kosong --}}
                                <div class="subtask-row" style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="subtasks[]" placeholder="Contoh: Cari referensi materi..."
                                        style="flex: 1; height: 42px; padding: 0 12px; background: white; border-radius: 10px; border: 1px solid #233D7B; color: #233D7B; font-size: 13px; font-family: 'Nunito Sans', sans-serif; outline: none;">
                                    <button type="button" class="btn-remove-subtask"
                                        style="width: 38px; height: 42px; background: #FCE1E1; color: #A63A3A; border: none; border-radius: 10px; font-weight: 800; cursor: pointer;">
                                        <svg class="dp-icon" fill="none" stroke="#A63A3A" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M6 6l12 12M18 6L6 18"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Baris Tanggal & Waktu (3 Kolom Responsif) -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px;">
                        <div style="display: flex; flex-direction: column; gap: 7px;">
                            <label for="due_date" style="color: #233D7B; font-size: 13px; font-weight: 800;">Tanggal</label>
                            <input type="date" id="due_date" name="due_date"
                                value="{{ old('due_date', $isEditing && $task->date ? $task->date->format('Y-m-d') : request('date', date('Y-m-d'))) }}"
                                required
                                style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 7px;">
                            <label for="start_time" style="color: #233D7B; font-size: 13px; font-weight: 800;">Waktu
                                mulai</label>
                            <input type="time" id="start_time" name="start_time"
                                value="{{ old('start_time', $startTime) }}"
                                style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 7px;">
                            <label for="end_time" style="color: #233D7B; font-size: 13px; font-weight: 800;">Waktu
                                selesai</label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $endTime) }}"
                                style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                        </div>
                    </div>

                    <!-- Baris Kategori & Prioritas -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                        <div style="display: flex; flex-direction: column; gap: 7px;">
                            <label for="category_id"
                                style="color: #233D7B; font-size: 13px; font-weight: 800;">Kategori</label>
                            <select id="category_id" name="category_id"
                                style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box; cursor: pointer;">
                                <option value="" @selected((string) old('category_id', $task->category_id ?? '') === '')>-- Tanpa Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $task->category_id ?? '') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 7px;">
                            <label for="priority"
                                style="color: #233D7B; font-size: 13px; font-weight: 800;">Prioritas</label>
                            <select id="priority" name="priority"
                                style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box; cursor: pointer;">
                                <option value="high" @selected(old('priority', $isEditing ? strtolower($task->priority) : 'medium') == 'high')>High</option>
                                <option value="medium" @selected(old('priority', $isEditing ? strtolower($task->priority) : 'medium') == 'medium')>Medium</option>
                                <option value="low" @selected(old('priority', $isEditing ? strtolower($task->priority) : 'medium') == 'low')>Low</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pengingat -->
                    <div style="display: flex; flex-direction: column; gap: 7px;">
                        <label for="reminder_minutes"
                            style="color: #233D7B; font-size: 13px; font-weight: 800;">Pengingat</label>
                        <select id="reminder_minutes" name="reminder_minutes"
                            style="width: 100%; height: 48px; padding: 0 14px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box; cursor: pointer;">
                            <option value="15" @selected(old('reminder_minutes', $reminderMinutes) == '15')>15 menit sebelum mulai</option>
                            <option value="30" @selected(old('reminder_minutes', $reminderMinutes) == '30')>30 menit sebelum mulai</option>
                            <option value="60" @selected(old('reminder_minutes', $reminderMinutes) == '60')>1 jam sebelum mulai</option>
                            <option value="0" @selected(old('reminder_minutes', $reminderMinutes) == '0')>Tanpa pengingat</option>
                        </select>
                    </div>

                    <!-- Footer Form: Info Draft & Tombol Aksi -->
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-top: 6px;">
                        <span
                            style="color: #78829B; font-size: 12px;">{{ $isEditing ? 'Perbarui detail tugas di bawah ini' : 'Perubahan disimpan sebagai draft' }}</span>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <a href="{{ route('task.index') }}"
                                style="padding: 12px 22px; background: white; border-radius: 12px; border: 1px solid #233D7B; color: #233D7B; font-size: 14px; font-weight: 800; text-decoration: none; box-sizing: border-box;">
                                Batal
                            </a>
                            <button type="submit"
                                style="padding: 12px 24px; background: #EAA549; border: none; border-radius: 9px; color: #233D7B; font-size: 14px; font-weight: 800; font-family: 'Nunito Sans', sans-serif; cursor: pointer;">
                                {{ $isEditing ? 'Perbarui Tugas' : 'Simpan Tugas' }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- KOLOM KANAN: KARTU MASKOT & TIPS PRODUKTIF -->
            <div style="display: flex; flex-direction: column; gap: 18px;">

                <!-- Kotak Maskot DailyPlan -->
                <div class="dp-surface"
                    style="width: 100%; height: 290px; background: white; display: flex; justify-content: center; align-items: center; overflow: hidden; padding: 20px; box-sizing: border-box;">
                    <img src="{{ asset('images/maskot/Maskot-DailyPlan.png') }}" alt="Maskot DailyPlan"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;" />
                </div>

                <!-- Kartu Tips Produktif -->
                <div
                    style="padding: 20px; background: #FBE2A8; border-radius: 18px; display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="#233D7B" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                        <span style="color: #233D7B; font-size: 14px; font-weight: 800;">Tips produktif</span>
                    </div>
                    <p style="color: #47608F; font-size: 13px; line-height: 1.5; margin: 0;">
                        Pecah tugas besar menjadi langkah kecil dan beri jeda singkat di antaranya.
                    </p>
                </div>

            </div>

        </div>
    </div>

    <style>
        @media (min-width: 1024px) {
            .create-task-grid {
                grid-template-columns: 1fr 330px !important;
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('click', function(e) {
                // 1. Jika tombol "+ Tambah Langkah" diklik
                const addBtn = e.target.closest('#btn-add-subtask');
                if (addBtn) {
                    e.preventDefault();
                    const container = document.getElementById('subtask-container');
                    if (!container) return;

                    const row = document.createElement('div');
                    row.className = 'subtask-row';
                    row.style.cssText = 'display: flex; gap: 8px; align-items: center;';
                    row.innerHTML = `
                <input type="text" name="subtasks[]" placeholder="Tulis langkah berikutnya..."
                    style="flex: 1; height: 42px; padding: 0 12px; background: white; border-radius: 10px; border: 1px solid #233D7B; color: #233D7B; font-size: 13px; font-family: 'Nunito Sans', sans-serif; outline: none;">
                <button type="button" class="btn-remove-subtask" aria-label="Hapus langkah"
                    style="width: 38px; height: 42px; background: #FCE1E1; color: #A63A3A; border: none; border-radius: 9px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <svg class="dp-icon" fill="none" stroke="#A63A3A" viewBox='0 0 24 24' stroke-linecap='round' stroke-linejoin='round' aria-hidden='true'>
                        <path d='M6 6l12 12M18 6L6 18'></path>
                    </svg>
                </button>
            `;
                    container.appendChild(row);
                    row.querySelector('input').focus();
                    return;
                }

                // 2. Jika tombol hapus pada baris subtask diklik
                const removeBtn = e.target.closest('.btn-remove-subtask');
                if (removeBtn) {
                    e.preventDefault();
                    const row = removeBtn.closest('.subtask-row');
                    if (row) {
                        row.remove();
                    }
                }
            });
        </script>
    @endpush
@endsection
