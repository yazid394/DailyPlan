@extends('layout.app')

@section('content')
@php
    $jam = (int) now()->format('H');
    $sapaan = match (true) {
        $jam < 11 => 'Selamat Pagi',
        $jam < 15 => 'Selamat Siang',
        $jam < 18 => 'Selamat Sore',
        default => 'Selamat Malam',
    };
    $namaDepan = explode(' ', trim($user->name ?? ''))[0] ?: 'Sahabat';
    $totalHariIni = $stats['total_today'];
    $selesaiHariIni = $stats['completed_today'];
    $pendingHariIni = $totalHariIni - $selesaiHariIni;
    $persentase = $totalHariIni > 0 ? (int) round($selesaiHariIni / $totalHariIni * 100) : 0;
@endphp

<div style="min-height: 100vh; width: 100%; background: #FDF8E2; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column;">

    <!-- NAVBAR -->
    <x-navbar />

    <!-- KONTEN UTAMA -->
    <main class="dp-page" style="width: 100%; background: #FDF8E2; padding: 36px 5%; box-sizing: border-box; display: flex; flex-direction: column; gap: 32px; flex: 1;">

        <!-- Header Halaman -->
        <div>
            <h1 style="color: #233D7B; font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $sapaan }}, {{ $namaDepan }}!</h1>
            <p class="dp-subtitle" style="color: #78829B; font-size: 15px; margin: 0;">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>

        <!-- Kartu Ringkasan (angka asli dari database) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            <!-- Gambaran Umum -->
            <div class="dp-surface" style="padding: 28px; display: flex; flex-direction: column; gap: 20px;">
                <h2 style="color: #233D7B; font-size: 18px; font-weight: 800; margin: 0;">Ringkasan Hari Ini</h2>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                    <div>
                        <span class="dp-label">Tugas</span>
                        <span class="dp-number" data-stat="total">{{ $totalHariIni }}</span>
                    </div>
                    <div>
                        <span class="dp-label">Selesai</span>
                        <span class="dp-number" data-stat="completed">{{ $selesaiHariIni }}</span>
                    </div>
                    <div>
                        <span class="dp-label">Pending</span>
                        <span class="dp-number" data-stat="pending">{{ $pendingHariIni }}</span>
                    </div>
                </div>
            </div>

            <!-- Kartu Progres -->
            <div class="dp-surface" style="padding: 28px; display: flex; align-items: center; gap: 24px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; border: 8px solid #EAA549; display: flex; justify-content: center; align-items: center; color: #233D7B; font-size: 20px; font-weight: 800; font-variant-numeric: tabular-nums; flex-shrink: 0;">
                    <span data-progress>{{ $persentase }}%</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <h3 style="color: #233D7B; font-size: 18px; font-weight: 800; margin: 0;">Progres harian</h3>
                    <p style="color: #78829B; font-size: 14px; margin: 0; line-height: 1.5;">
                        {{ $totalHariIni === 0 ? 'Belum ada tugas terjadwal.' : ($selesaiHariIni === $totalHariIni ? 'Semua tugas hari ini selesai.' : 'Sisa beberapa tugas lagi hari ini.') }}
                    </p>
                    <p style="color: #233D7B; font-size: 12px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 7px;">
                        <span class="dp-dot" style="background: #EAA549;"></span>
                        {{ $stats['urgent_count'] }} tugas perlu perhatian dalam 2 hari ke depan
                    </p>
                </div>
            </div>
        </div>

        <!-- Daftar Tugas Hari Ini -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="color: #233D7B; font-size: 22px; font-weight: 800; margin: 0;">To-Do List Hari Ini</h2>
                <span style="color: #78829B; font-size: 13px; font-weight: 700;">{{ $totalHariIni }} tugas</span>
            </div>

            @forelse ($todayTasks as $task)
                @php
                    $mulai = $task->start_time ? substr((string) $task->start_time, 0, 5) : null;
                    $selesai = $task->end_time ? substr((string) $task->end_time, 0, 5) : null;
                    $waktu = $mulai ? ($selesai ? $mulai.' - '.$selesai : $mulai) : 'Tanpa jam';
                    $warnaKategori = $task->category->color_code ?? '#EAA549';
                    $warnaPrioritas = match (strtoupper((string) $task->priority)) {
                        'HIGH' => '#E95D62',
                        'MEDIUM' => '#F2C453',
                        default => '#62B58A',
                    };
                    $subSelesai = $task->subtasks->where('is_completed', true)->count();
                    $subTotal = $task->subtasks->count();
                @endphp

                <div data-task-row class="dp-surface" style="padding: 20px 24px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <input type="checkbox" class="dashboard-task-checkbox" data-url="{{ route('task.toggle', $task) }}"
                        {{ $task->is_completed ? 'checked' : '' }}
                        style="width: 22px; height: 22px; accent-color: #233D7B; cursor: pointer; flex-shrink: 0;">

                    <div data-task-title style="flex: 1; min-width: 180px; color: #233D7B; font-size: 16px; font-weight: 600; {{ $task->is_completed ? 'text-decoration: line-through; opacity: .7;' : '' }}">
                        {{ $task->title }}
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px; flex-wrap: wrap;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; color: #78829B; font-size: 12px; font-weight: 700;">
                                <span style="width: 8px; height: 8px; background: {{ $warnaKategori }}; border-radius: 50%;"></span>
                                {{ $task->category->name ?? 'Tanpa Kategori' }}
                            </span>
                            @if ($subTotal > 0)
                                <span style="background: #FDF8E2; color: #47608F; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 999px;">
                                    {{ $subSelesai }}/{{ $subTotal }} langkah
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="dp-num" style="color: #78829B; font-size: 14px; font-weight: 600; white-space: nowrap;">{{ $waktu }}</div>
                    <div title="Prioritas {{ $task->priority }}" style="width: 12px; height: 12px; background: {{ $warnaPrioritas }}; border-radius: 50%; flex-shrink: 0;"></div>

                    <!-- Menu opsi cepat: titik tiga sekarang buka dropdown, bukan langsung edit -->
                    <div data-todo-menu style="position: relative; flex-shrink: 0;">
                        <button type="button" onclick="toggleTodoMenu(event, {{ $task->id }})"
                            title="Opsi tugas" aria-label="Opsi tugas" aria-expanded="false"
                            style="background: none; border: none; cursor: pointer; color: #78829B; padding: 6px; line-height: 0; display: flex; align-items: center; justify-content: center;">
                            <svg class="dp-icon" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="5" r="1.5"></circle>
                                <circle cx="12" cy="12" r="1.5"></circle>
                                <circle cx="12" cy="19" r="1.5"></circle>
                            </svg>
                        </button>

                        <div id="todo-dropdown-{{ $task->id }}" data-todo-dropdown
                            style="display: none; position: absolute; right: 0; top: calc(100% + 8px); z-index: 20; min-width: 230px; background: #FDF8E2; border: 1px solid #DDEBFA; border-radius: 12px; box-shadow: 0px 10px 24px rgba(35, 61, 123, 0.12); padding: 8px; font-family: 'Nunito Sans', sans-serif;">

                            <a href="{{ route('task.edit', $task) }}" class="todo-menu-item"
                                style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #233D7B; font-size: 13px; font-weight: 700; text-decoration: none;">
                                <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4 11.5-11.5z"></path>
                                </svg>
                                Edit Tugas
                            </a>

                            <a href="{{ route('task.index') }}" class="todo-menu-item"
                                style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #233D7B; font-size: 13px; font-weight: 700; text-decoration: none;">
                                <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.6-6 10-6 10 6 10 6-3.6 6-10 6-10-6-10-6z"></path>
                                    <circle cx="12" cy="12" r="2.5"></circle>
                                </svg>
                                Lihat Detail / Pantau Tugas
                            </a>

                            <form action="{{ route('task.destroy', $task) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus tugas ini?');"
                                style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="todo-menu-item"
                                    style="width: 100%; display: flex; align-items: center; gap: 10px; text-align: left; padding: 10px 12px; background: none; border: none; border-radius: 8px; color: #9B1C1C; font-size: 13px; font-weight: 700; cursor: pointer; font-family: 'Nunito Sans', sans-serif;">
                                    <svg class="dp-icon" fill="none" stroke="#9B1C1C" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v5m4-5v5"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="dp-surface" style="padding: 32px; text-align: center; color: #78829B; font-size: 14px;">
                    Belum ada tugas terjadwal.
                </div>
            @endforelse
        </div>

        <!-- Jadwal Rutin Hari Ini -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="color: #233D7B; font-size: 22px; font-weight: 800; margin: 0;">Jadwal Rutin Mingguan</h2>
                <span style="color: #78829B; font-size: 13px; font-weight: 700;">{{ $todayRecurrings->count() }} jadwal</span>
            </div>

            @forelse ($todayRecurrings as $recurring)
                @php
                    $jamMulai = $recurring->start_time ? substr((string) $recurring->start_time, 0, 5) : 'Rutin';
                @endphp
                <div class="dp-surface" style="padding: 16px 24px; display: flex; align-items: center; gap: 14px;">
                    <span style="width: 10px; height: 10px; background: {{ $recurring->category->color_code ?? '#5489D6' }}; border-radius: 50%; flex-shrink: 0;"></span>
                    <div class="dp-item-title" style="flex: 1; color: #233D7B; font-size: 15px; font-weight: 700;">{{ $recurring->title }}</div>
                    <div class="dp-num" style="color: #78829B; font-size: 13px; font-weight: 700;">{{ $jamMulai }}</div>
                    <span style="background: #DDF3E6; color: #1D6B45; font-size: 11px; font-weight: 800; padding: 5px 10px; border-radius: 6px;">Rutin</span>
                </div>
            @empty
                <div class="dp-surface" style="padding: 24px; text-align: center; color: #78829B; font-size: 14px;">
                    Tidak ada jadwal rutin untuk hari ini.
                </div>
            @endforelse
        </div>

        <!-- Deadline Mendatang (maksimal 4) -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <h2 style="color: #233D7B; font-size: 22px; font-weight: 800; margin: 0;">Deadline Mendatang</h2>

            @forelse ($upcomingDeadlines as $deadline)
                @php
                    $sisaHari = (int) today()->startOfDay()->diffInDays($deadline->date->copy()->startOfDay(), false);
                    $labelDeadline = match (true) {
                        $sisaHari < 0 => 'Terlewat '.abs($sisaHari).' hari',
                        $sisaHari === 0 => 'Hari ini',
                        default => $sisaHari.' hari lagi',
                    };
                @endphp
                <div class="dp-surface" style="padding: 18px 24px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <span style="width: 10px; height: 10px; background: {{ $deadline->category->color_code ?? '#EAA549' }}; border-radius: 50%; flex-shrink: 0;"></span>
                    <div class="dp-item-title" style="flex: 1; min-width: 160px; color: #233D7B; font-size: 15px; font-weight: 700;">{{ $deadline->title }}</div>
                    <div class="dp-num" style="color: #78829B; font-size: 13px; font-weight: 600;">{{ $deadline->date->format('d M Y') }}</div>
                    <span style="background: {{ $sisaHari <= 2 ? '#FCE1E1' : '#FBE2A8' }}; color: #233D7B; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 6px; display: inline-flex; align-items: center; gap: 7px;">
                        <span class="dp-dot" style="background: {{ $sisaHari <= 2 ? '#E95D62' : '#EAA549' }};"></span>
                        {{ $labelDeadline }}
                    </span>
                </div>
            @empty
                <div class="dp-surface" style="padding: 24px; text-align: center; color: #78829B; font-size: 14px;">
                    Belum ada deadline setelah hari ini.
                </div>
            @endforelse
        </div>

    </main>
</div>

<style>
    /* Hover item dropdown tetap pakai palet DailyPlan. */
    .todo-menu-item {
        transition: background 0.15s ease;
    }

    .todo-menu-item:hover {
        background: #DDEBFA !important;
    }
</style>

@push('scripts')
<script>
    const csrfTokenDashboard = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    // --- Menu opsi cepat pada tiap baris To-Do ---
    function tutupSemuaTodoMenu() {
        document.querySelectorAll('[data-todo-dropdown]').forEach(function (dropdown) {
            dropdown.style.display = 'none';
            const trigger = dropdown.closest('[data-todo-menu]')?.querySelector('[aria-expanded]');
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function toggleTodoMenu(event, taskId) {
        event.preventDefault();
        event.stopPropagation(); // biar global click listener nggak langsung nutup menu ini

        const menu = document.getElementById('todo-dropdown-' + taskId);
        if (!menu) return;

        const akanDibuka = menu.style.display !== 'block';

        // Kalau ada menu lain yang kebuka, tutup dulu.
        tutupSemuaTodoMenu();

        if (akanDibuka) {
            menu.style.display = 'block';
            const trigger = menu.closest('[data-todo-menu]')?.querySelector('[aria-expanded]');
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'true');
            }
        }
    }

    // Klik di luar dropdown = tutup semua menu.
    document.addEventListener('click', function (event) {
        if (event.target.closest('[data-todo-menu]')) return;
        tutupSemuaTodoMenu();
    });

    // Tombol Escape juga nutup menu, biar enak dipakai keyboard.
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            tutupSemuaTodoMenu();
        }
    });

    // Centang tugas di dashboard: langsung PATCH ke database, tanpa reload.
    document.addEventListener('change', async function (event) {
        const checkbox = event.target.closest('.dashboard-task-checkbox');
        if (!checkbox) return;

        const sebelumnya = !checkbox.checked; // keadaan sebelum user mengubah centangan
        checkbox.disabled = true;

        try {
            const response = await fetch(checkbox.dataset.url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenDashboard,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: '{}',
            });

            if (!response.ok) {
                throw new Error('Gagal memperbarui status tugas.');
            }

            const data = await response.json();
            checkbox.checked = data.is_completed;

            const baris = checkbox.closest('[data-task-row]');
            const judul = baris?.querySelector('[data-task-title]');
            if (judul) {
                judul.style.textDecoration = data.is_completed ? 'line-through' : 'none';
                judul.style.opacity = data.is_completed ? '.7' : '1';
            }

            // Ringkasan angka ikut berubah juga biar nggak menampilkan data lama.
            const completedEl = document.querySelector('[data-stat="completed"]');
            const pendingEl = document.querySelector('[data-stat="pending"]');
            const progressEl = document.querySelector('[data-progress]');

            let selesai = Number(completedEl?.textContent ?? 0);
            let pending = Number(pendingEl?.textContent ?? 0);

            if (data.is_completed && !sebelumnya) {
                selesai++;
                pending = Math.max(0, pending - 1);
            } else if (!data.is_completed && sebelumnya) {
                selesai = Math.max(0, selesai - 1);
                pending++;
            }

            if (completedEl) completedEl.textContent = selesai;
            if (pendingEl) pendingEl.textContent = pending;

            const total = selesai + pending;
            if (progressEl) {
                progressEl.textContent = (total > 0 ? Math.round(selesai / total * 100) : 0) + '%';
            }
        } catch (error) {
            checkbox.checked = sebelumnya;
            alert(error.message || 'Gagal memperbarui status tugas.');
        } finally {
            checkbox.disabled = false;
        }
    });
</script>
@endpush
@endsection
