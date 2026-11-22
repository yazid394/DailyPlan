@extends('layout.app')

@section('content')
    <x-navbar />

    <div class="dp-page"
        style="min-height: calc(100vh - 80px); width: 100%; background: #FDF8E2; padding: 36px 5%; box-sizing: border-box; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column; gap: 24px;">

        <!-- Header Halaman -->
        <div
            style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 16px; margin-bottom: 24px; flex-wrap: wrap;">
            <div>
                <h1 style="color: #233D7B; font-size: 28px; font-weight: 800; margin: 0;">Kemajuan Saya</h1>
                <p style="color: #78829B; font-size: 14px; margin: 4px 0 0 0;">Ringkasan belajar dan tugas minggu ini.</p>
            </div>

            <a href="{{ route('progress.pdf') }}"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: auto !important; flex-shrink: 0; background: #233D7B; color: #FFFFFF; padding: 11px 20px; border-radius: 10px; font-size: 14px; font-weight: 700; text-decoration: none; white-space: nowrap;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Unduh Laporan PDF
            </a>
        </div>

        <!-- 3 Kartu Statistik Utama (angka asli dari database) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px;">

            <!-- Card 1: Tugas Selesai -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; align-items: center; gap: 16px;">
                <div
                    style="width: 52px; height: 52px; background: #DDF3E6; border-radius: 12px; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                    <svg class="dp-icon" style="width: 22px; height: 22px;" fill="none" stroke="#233D7B"
                        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <span class="dp-label">Tugas selesai</span>
                    <span class="dp-number">{{ $tugasSelesai }}</span>
                </div>
            </div>

            <!-- Card 2: Deadline Terpenuhi -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; align-items: center; gap: 16px;">
                <div
                    style="width: 52px; height: 52px; background: #FBE2A8; border-radius: 12px; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                    <svg class="dp-icon" style="width: 22px; height: 22px;" fill="none" stroke="#233D7B"
                        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <span class="dp-label">Deadline terpenuhi</span>
                    <span class="dp-number">{{ $deadlineTerpenuhi }}</span>
                </div>
            </div>

            <!-- Card 3: Jam Belajar -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; align-items: center; gap: 16px;">
                <div
                    style="width: 52px; height: 52px; background: #DDEBFA; border-radius: 12px; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                    <svg class="dp-icon" style="width: 22px; height: 22px;" fill="none" stroke="#233D7B"
                        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <span class="dp-label">Jam belajar</span>
                    <span class="dp-number">{{ $durasiFormatted }}</span>
                </div>
            </div>

        </div>

        <!-- Bagian Grafik Interaktif (Chart.js) -->
        <div class="progress-charts-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 22px; align-items: start;">

            <!-- Bar Chart: Jam Belajar Mingguan -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <h2
                        style="color: #233D7B; font-size: 18px; font-weight: 800; letter-spacing: -0.01em; line-height: 1.3; margin: 0;">
                        Jam Belajar Mingguan</h2>
                    <span class="dp-num" style="color: #78829B; font-size: 13px; font-weight: 500;">
                        {{ \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->translatedFormat('d') }}–{{ \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY)->translatedFormat('d F') }}
                    </span>
                </div>
                <div style="position: relative; height: 290px;">
                    <canvas id="weeklyStudyChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart: Distribusi Tugas per Kategori -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <h2
                        style="color: #233D7B; font-size: 18px; font-weight: 800; letter-spacing: -0.01em; line-height: 1.3; margin: 0;">
                        Distribusi Tugas per Kategori</h2>
                    <span style="color: #78829B; font-size: 13px; font-weight: 500;">Semua kategori</span>
                </div>
                <div style="position: relative; height: 290px;">
                    <canvas id="categoryDoughnutChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Insight Minggu Ini & Target Belajar -->
        <div class="progress-insight-grid"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; align-items: stretch;">

            <!-- Card Insight -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; flex-direction: column; gap: 12px;">
                <div style="color: #EAA549; font-size: 12px; font-weight: 800; letter-spacing: 0.5px;">MINGGU INI</div>
                <div style="color: #233D7B; font-size: 20px; font-weight: 700; line-height: 1.35;">
                    @if ($mostProductive !== '-')
                        {{ $mostProductive }} jadi hari paling produktifmu minggu ini!
                    @else
                        Belum ada jam belajar tercatat minggu ini. Yuk mulai dari satu tugas!
                    @endif
                </div>
                <div style="color: #78829B; font-size: 13px; line-height: 1.5;">
                    {{ $tugasSelesai }} tugas selesai dan {{ $durasiFormatted }} belajar terkumpul.
                </div>
            </div>

            <!-- Card Progress Bar Target Belajar -->
            <div class="dp-surface"
                style="padding: 24px; background: white; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #233D7B; font-size: 15px; font-weight: 800;">Target belajar</span>
                    <span class="dp-num" style="color: #233D7B; font-size: 14px; font-weight: 800;">
                        {{ $durasiFormatted }} / {{ $targetJam }} jam
                    </span>
                </div>
                <div style="width: 100%; height: 10px; background: #FDF8E2; border-radius: 999px; overflow: hidden;">
                    <div
                        style="width: {{ $targetPersen }}%; height: 100%; background: #62B58A; border-radius: 999px; transition: width 0.4s ease;">
                    </div>
                </div>
            </div>

        </div>

        <!-- Riwayat tugas selesai minggu ini (maksimal 5, dari database) -->
        <div class="dp-surface" style="padding: 24px; background: white; display: flex; flex-direction: column; gap: 14px;">
            <h2 style="color: #233D7B; font-size: 22px; font-weight: 800; margin: 0;">Riwayat Tugas Selesai</h2>

            @forelse ($completedHistory as $riwayat)
                <div
                    style="padding: 14px 16px; background: #FDF8E2; border-radius: 12px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <span
                        style="width: 10px; height: 10px; background: {{ $riwayat->category->color_code ?? '#EAA549' }}; border-radius: 50%; flex-shrink: 0;"></span>
                    <div class="dp-item-title"
                        style="flex: 1; min-width: 160px; color: #233D7B; font-size: 14px; font-weight: 700;">
                        {{ $riwayat->title }}</div>
                    <div style="color: #78829B; font-size: 12px; font-weight: 600;">
                        {{ $riwayat->category->name ?? 'Tanpa Kategori' }}</div>
                    <div style="color: #78829B; font-size: 12px; font-weight: 600;">{{ $riwayat->date?->format('d M Y') }}
                    </div>
                    <span
                        style="background: #DDF3E6; color: #1D6B45; font-size: 11px; font-weight: 800; padding: 5px 10px; border-radius: 6px;">Selesai</span>
                </div>
            @empty
                <div style="color: #78829B; font-size: 14px;">Belum ada tugas selesai minggu ini.</div>
            @endforelse
        </div>
    </div>

    <style>
        @media (min-width: 1024px) {

            .progress-charts-grid,
            .progress-insight-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Chart === 'undefined') {
                    return;
                }

                const fontUtama = "'Nunito Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
                Chart.defaults.font.family = fontUtama;
                Chart.defaults.color = '#233D7B';

                const formatDurasi = (nilai) => {
                    const totalMenit = Math.round(Number(nilai) * 60);
                    const jam = Math.floor(totalMenit / 60);
                    const menit = totalMenit % 60;

                    if (jam > 0 && menit > 0) {
                        return `${jam} jam ${menit} menit`;
                    }

                    if (jam > 0) {
                        return `${jam} jam`;
                    }

                    return `${menit} menit`;
                };

                const dataMingguan = @json($weeklyChart);

                new Chart(document.getElementById('weeklyStudyChart'), {
                    type: 'bar',
                    data: {
                        labels: dataMingguan.map((item) => item.hari),
                        datasets: [{
                            label: 'Jam Belajar',
                            data: dataMingguan.map((item) => item.jam),
                            backgroundColor: dataMingguan.map((item) => item.is_highest ? '#EAA549' :
                                '#233D7B'),
                            borderRadius: 10,
                            borderSkipped: false,
                            maxBarThickness: 48,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#233D7B',
                                titleFont: {
                                    family: fontUtama,
                                    size: 13,
                                    weight: '800'
                                },
                                bodyFont: {
                                    family: fontUtama,
                                    size: 12,
                                    weight: '600'
                                },
                                padding: 10,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: (context) => formatDurasi(context.parsed.y),
                                },
                            },
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    color: '#233D7B',
                                    font: {
                                        family: fontUtama,
                                        size: 12,
                                        weight: '700'
                                    },
                                },
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F1F3F9'
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    color: '#78829B',
                                    precision: 0,
                                    font: {
                                        family: fontUtama,
                                        size: 11,
                                        weight: '600'
                                    },
                                },
                            },
                        },
                    },
                });

                const dataKategori = @json($categoryChart);

                let labelsKategori = dataKategori.labels.filter((_, index) => dataKategori.counts[index] > 0);
                let jumlahKategori = dataKategori.counts.filter((count) => count > 0);
                let warnaKategori = dataKategori.colors.filter((_, index) => dataKategori.counts[index] > 0);

                if (jumlahKategori.reduce((total, count) => total + count, 0) === 0) {
                    labelsKategori = ['Belum ada data'];
                    jumlahKategori = [1];
                    warnaKategori = ['#E5E7EB'];
                }

                new Chart(document.getElementById('categoryDoughnutChart'), {
                    type: 'doughnut',
                    data: {
                        labels: labelsKategori,
                        datasets: [{
                            data: jumlahKategori,
                            backgroundColor: warnaKategori,
                            borderColor: '#FFFFFF',
                            borderWidth: 3,
                            hoverOffset: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: '#233D7B',
                                    boxWidth: 12,
                                    padding: 16,
                                    usePointStyle: true,
                                    font: {
                                        family: fontUtama,
                                        size: 12,
                                        weight: '600'
                                    },
                                },
                            },
                            tooltip: {
                                backgroundColor: '#233D7B',
                                titleFont: {
                                    family: fontUtama,
                                    size: 13,
                                    weight: '800'
                                },
                                bodyFont: {
                                    family: fontUtama,
                                    size: 12,
                                    weight: '600'
                                },
                                padding: 10,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: (context) => `${context.label}: ${context.parsed} tugas`,
                                },
                            },
                        },
                    },
                });
            });
        </script>
    @endpush
@endsection
