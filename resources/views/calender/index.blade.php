@extends('layout.app')

@section('content')
    <x-navbar />
    <div class="dp-page"
        style="min-height: calc(100vh - 80px); width: 100%; background: #FDF8E2; padding: 36px 5%; box-sizing: border-box; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column; gap: 24px;">

        <!-- Header Halaman -->
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <h1 style="color: #233D7B; font-size: 30px; font-weight: 800; line-height: 1.2; margin: 0;">Kalender & Waktu</h1>
            <p class="dp-subtitle" style="color: #78829B; font-size: 15px; margin: 0;">Atur ritme belajar dan kegiatanmu dalam satu tampilan.</p>
        </div>

        @if (session('success'))
            <div role="alert"
                style="background: #DDF3E6; border: 1px solid #62B58A; color: #1D6B45; padding: 14px 18px; border-radius: 12px; font-size: 14px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Utama: Kalender Kiri & Detail Agenda Kanan -->
        <div class="calendar-main-grid" style="display: grid; grid-template-columns: 1fr; gap: 24px; align-items: start;">

            <!-- KOLOM KIRI: WIDGET KALENDER -->
            <div
                class="dp-surface" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div id="cal-month-year" class="dp-num" style="color: #233D7B; font-size: 22px; font-weight: 800;"></div>
                        <button type="button" onclick="kembaliKeHariIni()"
                            style="padding: 4px 12px; background: #FDF8E2; border: 1px solid rgba(35, 61, 123, 0.10); border-radius: 9px; color: #233D7B; font-size: 12px; font-weight: 700; cursor: pointer;">
                            Hari Ini
                        </button>
                    </div>

                    <div style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" onclick="geserBulan(-1)" aria-label="Bulan sebelumnya"
                            style="width: 34px; height: 34px; background: #FDF8E2; border: none; border-radius: 9px; color: #233D7B; cursor: pointer; display: flex; justify-content: center; align-items: center;">
                            <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M15 18l-6-6 6-6"></path>
                            </svg>
                        </button>
                        <button type="button" onclick="geserBulan(1)" aria-label="Bulan berikutnya"
                            style="width: 34px; height: 34px; background: #FDF8E2; border: none; border-radius: 9px; color: #233D7B; cursor: pointer; display: flex; justify-content: center; align-items: center;">
                            <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 18l6-6-6-6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Nama Hari -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; text-align: center;">
                    @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $hari)
                        <div style="color: #78829B; font-size: 12px; font-weight: 800; padding: 4px 0;">{{ $hari }}
                        </div>
                    @endforeach
                </div>

                <!-- Kotak Tanggal -->
                <div id="cal-days-grid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;"></div>

                <!-- Legenda Kategori -->
                <div style="display: flex; gap: 18px; margin-top: 4px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 8px; height: 8px; background: #5489D6; border-radius: 50%;"></div>
                        <span style="color: #78829B; font-size: 12px;">School</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 8px; height: 8px; background: #8B68D0; border-radius: 50%;"></div>
                        <span style="color: #78829B; font-size: 12px;">Organization</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 8px; height: 8px; background: #62B58A; border-radius: 50%;"></div>
                        <span style="color: #78829B; font-size: 12px;">Personal</span>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: JADWAL HARIAN DINAMIS -->
            <div
                class="dp-surface" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">

                <!-- Header Tanggal Terpilih -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <h2 id="agenda-selected-date" style="color: #233D7B; font-size: 22px; font-weight: 800; margin: 0;">
                        </h2>
                        <span id="agenda-count-text" style="color: #78829B; font-size: 13px;">0 kegiatan terjadwal</span>
                    </div>
                    <a id="btn-tambah-kalender" href="{{ route('task.create') }}"
                        style="padding: 8px 14px; background: #EAA549; color: #233D7B; font-size: 12px; font-weight: 800; border-radius: 9px; text-decoration: none; white-space: nowrap;">
                        + Tambah
                    </a>
                </div>

                <!-- Kontainer Daftar Kegiatan (Diisi otomatis oleh JavaScript) -->
                <div id="agenda-list-container" style="display: flex; flex-direction: column; gap: 14px;"></div>

            </div>

        </div>
    </div>

    <style>
        @media (min-width: 1024px) {
            .calendar-main-grid {
                grid-template-columns: 1.8fr 1fr !important;
            }
        }

        .cal-day-box:hover {
            border-color: rgba(234, 165, 73, 0.45) !important;
            background-color: #FAF6E6 !important;
        }
    </style>

    <script>
        const namaBulanID = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const namaHariID = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        // Data awal dari Laravel Controller (Format kunci: "YYYY-MM-DD")
        let eventsData = @json($initialEvents ?? []);
        let dotsData = @json($initialDots ?? []);

        // [UBAHAN 1] Cek apakah habis redirect dari form Tambah Tugas bawa parameter ?date=...
        const urlParams = new URLSearchParams(window.location.search);
        const paramDate = urlParams.get('date');

        const today = new Date();
        let initialTarget = today;

        if (paramDate) {
            const parts = paramDate.split('-'); // Pecah "2026-11-25" jadi [2026, 11, 25]
            if (parts.length === 3) {
                const target = new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
                if (!Number.isNaN(target.getTime())) {
                    initialTarget = target;
                }
            }
        }

        // Set bulan, tahun, & tanggal yang aktif pakai tanggal target tadi (atau hari ini kalau baru buka biasa)
        let currentYear = initialTarget.getFullYear();
        let currentMonth = initialTarget.getMonth();
        let selectedDate = new Date(initialTarget.getFullYear(), initialTarget.getMonth(), initialTarget.getDate());

        // Helper untuk membuat kunci "YYYY-MM-DD" sesuai tanggal yang diklik
        function formatDateKey(year, monthIndex, day) {
            const mm = String(monthIndex + 1).padStart(2, '0');
            const dd = String(day).padStart(2, '0');
            return `${year}-${mm}-${dd}`;
        }

        function renderKalender() {
            const titleEl = document.getElementById('cal-month-year');
            const gridEl = document.getElementById('cal-days-grid');

            titleEl.innerText = `${namaBulanID[currentMonth]} ${currentYear}`;
            gridEl.innerHTML = '';

            const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Kotak kosong sebelum tanggal 1
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyBox = document.createElement('div');
                emptyBox.style.cssText =
                    'min-height: 68px; padding: 8px; background: #FDF8E2; opacity: 0.45; border-radius: 12px;';
                gridEl.appendChild(emptyBox);
            }

            // Loop semua tanggal
            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = formatDateKey(currentYear, currentMonth, day);

                const isToday = (
                    day === today.getDate() &&
                    currentMonth === today.getMonth() &&
                    currentYear === today.getFullYear()
                );

                const isSelected = (
                    day === selectedDate.getDate() &&
                    currentMonth === selectedDate.getMonth() &&
                    currentYear === selectedDate.getFullYear()
                );

                const box = document.createElement('div');
                box.className = 'cal-day-box';

                const bg = isToday ? '#FBE2A8' : '#FDF8E2';
                const border = isSelected ? '2px solid #233D7B' : '2px solid transparent';
                const weight = (isToday || isSelected) ? '900' : '600';

                box.style.cssText = `
                min-height: 68px;
                padding: 8px;
                background: ${bg};
                border: ${border};
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                cursor: pointer;
                transition: all 0.15s ease;
                box-sizing: border-box;
            `;

                const numEl = document.createElement('div');
                numEl.style.cssText = `color: #233D7B; font-size: 13px; font-weight: ${weight}; font-variant-numeric: tabular-nums;`;
                numEl.innerText = day;
                box.appendChild(numEl);

                // Tampilkan titik indikator jika tanggal ini punya tugas/jadwal rutin
                if (dotsData[dateKey] && dotsData[dateKey].length > 0) {
                    const dotsWrap = document.createElement('div');
                    dotsWrap.style.cssText = 'display: flex; gap: 4px; flex-wrap: wrap;';
                    dotsData[dateKey].forEach(color => {
                        const dot = document.createElement('div');
                        dot.style.cssText = `width: 6px; height: 6px; background: ${color}; border-radius: 50%;`;
                        dotsWrap.appendChild(dot);
                    });
                    box.appendChild(dotsWrap);
                }

                // AKSI SAAT TANGGAL DIKLIK:
                box.onclick = () => {
                    selectedDate = new Date(currentYear, currentMonth, day);
                    renderKalender();
                    renderAgendaHarian();
                };

                gridEl.appendChild(box);
            }
        }

        // Merender daftar kegiatan di panel kanan sesuai tanggal yang dipilih
        function renderAgendaHarian() {
            const headerEl = document.getElementById('agenda-selected-date');
            const countEl = document.getElementById('agenda-count-text');
            const containerEl = document.getElementById('agenda-list-container');

            const namaHari = namaHariID[selectedDate.getDay()];
            const tgl = selectedDate.getDate();
            const bulan = namaBulanID[selectedDate.getMonth()];
            headerEl.innerText = `${namaHari}, ${tgl} ${bulan}`;

            const selectedKey = formatDateKey(
                selectedDate.getFullYear(),
                selectedDate.getMonth(),
                selectedDate.getDate()
            );

            // [UBAHAN 2] Bikin link tombol "+ Tambah" otomatis bawa tanggal yang lagi diklik
            const btnTambah = document.getElementById('btn-tambah-kalender');
            if (btnTambah) {
                btnTambah.href = `{{ route('task.create') }}?date=${selectedKey}`;
            }

            const daftarAgenda = eventsData[selectedKey] || [];
            countEl.innerText = `${daftarAgenda.length} kegiatan terjadwal`;

            // Kosongkan isi panel kanan lalu bangun ulang kartu kegiatannya
            containerEl.innerHTML = '';

            if (daftarAgenda.length === 0) {
                containerEl.innerHTML = `
                <div style="text-align: center; padding: 32px 16px; background: #FDF8E2; border-radius: 14px; color: #78829B; font-size: 14px;">
                    Belum ada tugas terjadwal.
                </div>
            `;
                return;
            }

            daftarAgenda.forEach(item => {
                const coret = item.status === 'completed' ? 'text-decoration: line-through; opacity: 0.65;' : '';
                const row = document.createElement('div');
                row.style.cssText = 'display: flex; align-items: flex-start; gap: 14px;';
                row.innerHTML = `
                <div style="width: 56px; color: #233D7B; font-size: 13px; font-weight: 800; padding-top: 4px; flex-shrink: 0; font-variant-numeric: tabular-nums;">
                    ${item.waktu}
                </div>
                <div style="flex: 1; padding: 14px; background: ${item.bg}; border-radius: 12px; display: flex; flex-direction: column; gap: 5px;">
                    <div class="dp-item-title" style="color: #233D7B; font-size: 14px; font-weight: 800; ${coret}">${item.judul}</div>
                    <div class="dp-meta" style="color: #78829B; font-size: 13px; font-weight: 500;">${item.kategori}</div>
                </div>
            `;
                containerEl.appendChild(row);
            });
        }

        // Mengambil data bulan baru dari Laravel saat user menggeser bulan
        async function muatDataBulan(year, monthIndex) {
            try {
                const response = await fetch(`{{ route('calender.data') }}?year=${year}&month=${monthIndex + 1}`);
                const result = await response.json();
                eventsData = result.events || {};
                dotsData = result.dots || {};
            } catch (error) {
                console.error('Gagal memuat jadwal bulan:', error);
            }
            renderKalender();
            renderAgendaHarian();
        }

        function geserBulan(arah) {
            currentMonth += arah;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            muatDataBulan(currentYear, currentMonth);
        }

        function kembaliKeHariIni() {
            const now = new Date();
            currentYear = now.getFullYear();
            currentMonth = now.getMonth();
            selectedDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            muatDataBulan(currentYear, currentMonth);
        }

        // [UBAHAN 3] Cek kalau tanggal target beda bulan sama bulan sekarang, tarik dulu datanya dari server
        if (currentYear !== today.getFullYear() || currentMonth !== today.getMonth()) {
            muatDataBulan(currentYear, currentMonth);
        } else {
            renderKalender();
            renderAgendaHarian();
        }
    </script>
@endsection
