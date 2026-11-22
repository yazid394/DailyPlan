@extends('layout.app')

@section('content')
<x-navbar />

<div class="dp-page" style="min-height: calc(100vh - 80px); width: 100%; background: #FDF8E2; padding: 36px 5%; box-sizing: border-box; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column; gap: 24px;">

    <!-- Header & Tombol Tambah Deadline -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <h1 style="color: #233D7B; font-size: 30px; font-weight: 800; line-height: 1.2; margin: 0;">Pantau Tugas</h1>
            <p class="dp-subtitle" style="color: #78829B; font-size: 15px; margin: 0;">Prioritaskan yang mendesak, selesaikan tanpa panik.</p>
        </div>
        <a href="{{ route('task.create') }}"
            style="display: inline-block; padding: 12px 22px; background: #EAA549; color: #233D7B; font-size: 14px; font-weight: 800; border: none; border-radius: 9px; cursor: pointer; text-decoration: none; transition: background-color 0.15s ease;">
            + Tambah Deadline
        </a>
    </div>

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div style="background: #DDF3E6; border: 1px solid #62B58A; color: #1E4620; padding: 14px 18px; border-radius: 12px; font-size: 14px; font-weight: 700;">
            {{ session('success') }}
        </div>
    @endif

    <!-- 3 Kartu Statistik (angka asli dari TaskController) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="dp-surface" style="padding: 24px; display: flex; flex-direction: column; gap: 10px;">
            <span class="dp-label">Perlu perhatian</span>
            <span class="dp-number">{{ $stats['perlu_perhatian'] }}</span>
        </div>

        <div class="dp-surface" style="padding: 24px; display: flex; flex-direction: column; gap: 10px;">
            <span class="dp-label">Minggu ini</span>
            <span class="dp-number">{{ $stats['minggu_ini'] }}</span>
        </div>

        <div class="dp-surface" style="padding: 24px; display: flex; flex-direction: column; gap: 10px;">
            <span class="dp-label">Tepat waktu</span>
            <span class="dp-number">{{ $stats['tepat_waktu'] }}</span>
        </div>
    </div>

    <!-- Daftar Kartu Tugas (langsung dari database) -->
    <div style="display: flex; flex-direction: column; gap: 14px;">
        @forelse ($tasks as $task)
            @php
                $categoryName = $task->category->name ?? 'Tanpa Kategori';
                $theme = match ($categoryName) {
                    'School' => '#DDEBFA',
                    'Organization' => '#E9E1FA',
                    'Personal' => '#DDF3E6',
                    default => '#FBE2A8',
                };

                // Hitung sisa hari berdasarkan tanggal asli di database.
                $hariIni = today()->startOfDay();
                $tanggalTugas = $task->date?->copy()->startOfDay();
                $selisih = $tanggalTugas ? (int) $hariIni->diffInDays($tanggalTugas, false) : 0;

                if ($task->is_completed) {
                    $badge = 'Selesai';
                    $badgeBg = '#DDF3E6';
                    $badgeDot = '#62B58A';
                } elseif ($selisih < 0) {
                    $badge = 'Terlewat '.abs($selisih).' hari';
                    $badgeBg = '#FCE1E1';
                    $badgeDot = '#E95D62';
                } elseif ($selisih === 0) {
                    $badge = 'Hari ini';
                    $badgeBg = '#FBE2A8';
                    $badgeDot = '#EAA549';
                } elseif ($selisih === 1) {
                    $badge = '1 hari lagi';
                    $badgeBg = '#FBE2A8';
                    $badgeDot = '#EAA549';
                } elseif ($selisih <= 3) {
                    $badge = $selisih.' hari lagi';
                    $badgeBg = '#FBE2A8';
                    $badgeDot = '#EAA549';
                } else {
                    $badge = $selisih.' hari lagi';
                    $badgeBg = $theme;
                    $badgeDot = '#233D7B';
                }

                $subtaskSelesai = $task->subtasks->where('is_completed', true)->count();
                $subtaskTotal = $task->subtasks->count();
            @endphp

            <div data-task-card class="dp-surface" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">

                <!-- Baris utama: ikon, info tugas, badge, dan aksi -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px;">
                    <div style="display: flex; align-items: center; gap: 18px; flex: 1; min-width: 260px;">
                        <div style="width: 50px; height: 50px; background: {{ $theme }}; border-radius: 15px; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                            <svg width="24" height="24" fill="none" stroke="#233D7B" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <div data-task-title style="color: #233D7B; font-size: 17px; font-weight: 700; {{ $task->is_completed ? 'text-decoration: line-through; opacity: .65;' : '' }}">
                                {{ $task->title }}
                            </div>
                            <div class="dp-num" style="color: #78829B; font-size: 13px;">
                                {{ $tanggalTugas?->format('d M Y') }} &nbsp;•&nbsp; {{ $categoryName }}
                                @if ($task->notes)
                                    &nbsp;•&nbsp; {{ $task->notes }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span data-badge data-original-badge="{{ $badge }}" data-original-bg="{{ $badgeBg }}"
                            data-original-dot="{{ $badgeDot }}" style="--badge-dot: {{ $badgeDot }}; padding: 7px 12px; background: {{ $badgeBg }}; border-radius: 6px; color: #233D7B; font-size: 12px; font-weight: 700; white-space: nowrap;">
                            {{ $badge }}
                        </span>

                        <button type="button" class="btn-toggle-complete" data-url="{{ route('task.toggle', $task) }}"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #EAA549; border: none; border-radius: 9px; color: #233D7B; font-size: 13px; font-weight: 800; cursor: pointer; white-space: nowrap;">
                            <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                            <span data-toggle-label>{{ $task->is_completed ? 'Batalkan Selesai' : 'Tandai Selesai' }}</span>
                        </button>

                        <a href="{{ route('task.edit', $task) }}"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: white; border: 1px solid rgba(35, 61, 123, 0.10); border-radius: 9px; color: #233D7B; font-size: 13px; font-weight: 800; text-decoration: none; white-space: nowrap;">
                            <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4 11.5-11.5z"></path>
                            </svg>
                            Edit
                        </a>

                        <form action="{{ route('task.destroy', $task) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus tugas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: #FCE1E1; border: none; border-radius: 9px; color: #9B1C1C; font-size: 13px; font-weight: 800; cursor: pointer; white-space: nowrap;">
                                <svg class="dp-icon" fill="none" stroke="#9B1C1C" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v5m4-5v5"></path>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Checklist subtask realtime -->
                @if ($task->subtasks->count() > 0)
                    <div data-subtask-box
                        style="width: 100%; padding: 14px 16px; background: #FDF8E2; border: 1px dashed #233D7B; border-radius: 14px; display: flex; flex-direction: column; gap: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                            <span style="color: #233D7B; font-size: 13px; font-weight: 800;">Langkah pengerjaan</span>
                            <span data-subtask-counter
                                style="color: #78829B; font-size: 12px; font-weight: 700;">{{ $subtaskSelesai }}/{{ $subtaskTotal }} langkah selesai</span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach ($task->subtasks as $subtask)
                                <label data-subtask-row style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" class="subtask-checkbox" data-url="{{ route('subtask.toggle', $subtask) }}"
                                        {{ $subtask->is_completed ? 'checked' : '' }}
                                        style="width: 16px; height: 16px; accent-color: #233D7B; cursor: pointer;">
                                    <span data-subtask-label
                                        style="color: #47608F; font-size: 13px; {{ $subtask->is_completed ? 'text-decoration: line-through; opacity: .6;' : '' }}">
                                        {{ $subtask->title }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="dp-surface" style="padding: 40px; text-align: center; color: #78829B; font-size: 15px;">
                Belum ada tugas terjadwal.
            </div>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
    // Token CSRF diambil dari <meta> layout, jadi fetch PATCH tetap dianggap request sah Laravel.
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const ajaxHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
    };

    // 1. Tombol "Tandai Selesai" / "Batalkan Selesai"
    document.addEventListener('click', async function (event) {
        const button = event.target.closest('.btn-toggle-complete');
        if (!button) return;

        event.preventDefault();
        if (button.disabled) return;

        const labelTombol = button.querySelector('[data-toggle-label]');
        const teksAwal = labelTombol?.textContent ?? button.textContent.trim();
        const ubahLabel = (teks) => {
            if (labelTombol) {
                labelTombol.textContent = teks;
            } else {
                button.textContent = teks;
            }
        };

        button.disabled = true;
        ubahLabel('Memproses...');

        try {
            const response = await fetch(button.dataset.url, {
                method: 'PATCH',
                headers: ajaxHeaders,
                body: '{}',
            });

            if (!response.ok) {
                throw new Error('Gagal memperbarui status tugas.');
            }

            const data = await response.json();
            const card = button.closest('[data-task-card]');
            const badge = card?.querySelector('[data-badge]');
            const title = card?.querySelector('[data-task-title]');

            if (data.is_completed) {
                ubahLabel('Batalkan Selesai');
                if (badge) {
                    badge.textContent = 'Selesai';
                    badge.style.background = '#DDF3E6';
                    badge.style.setProperty('--badge-dot', '#62B58A');
                }
                if (title) {
                    title.style.textDecoration = 'line-through';
                    title.style.opacity = '.65';
                }
            } else {
                ubahLabel('Tandai Selesai');
                if (badge) {
                    badge.textContent = badge.dataset.originalBadge;
                    badge.style.background = badge.dataset.originalBg;
                    badge.style.setProperty('--badge-dot', badge.dataset.originalDot || '#233D7B');
                }
                if (title) {
                    title.style.textDecoration = 'none';
                    title.style.opacity = '1';
                }
            }
        } catch (error) {
            ubahLabel(teksAwal);
            alert(error.message || 'Gagal memperbarui status tugas.');
        } finally {
            button.disabled = false;
        }
    });

    // 2. Checkbox subtask: langsung PATCH, counter ikut berubah tanpa reload
    async function toggleSubtask(checkbox) {
        const keadaanSebelumnya = !checkbox.checked;
        checkbox.disabled = true;

        try {
            const response = await fetch(checkbox.dataset.url, {
                method: 'PATCH',
                headers: ajaxHeaders,
                body: '{}',
            });

            if (!response.ok) {
                throw new Error('Gagal memperbarui subtask.');
            }

            const data = await response.json();
            checkbox.checked = data.is_completed;

            const label = checkbox.closest('[data-subtask-row]')?.querySelector('[data-subtask-label]');
            if (label) {
                label.style.textDecoration = data.is_completed ? 'line-through' : 'none';
                label.style.opacity = data.is_completed ? '.6' : '1';
            }

            const counter = checkbox.closest('[data-subtask-box]')?.querySelector('[data-subtask-counter]');
            if (counter) {
                counter.textContent = `${data.done}/${data.total} langkah selesai`;
            }
        } catch (error) {
            checkbox.checked = keadaanSebelumnya;
            alert(error.message || 'Gagal memperbarui subtask.');
        } finally {
            checkbox.disabled = false;
        }
    }

    document.addEventListener('change', function (event) {
        const checkbox = event.target.closest('.subtask-checkbox');
        if (!checkbox) return;

        toggleSubtask(checkbox);
    });
</script>
@endpush
@endsection
