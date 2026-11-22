<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kemajuan Belajar - {{ $user->name }}</title>
    <style>
        @page {
            margin: 28px 34px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1E293B;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #233D7B;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            color: #233D7B;
            margin: 0;
        }

        .brand-accent {
            color: #EAA549;
        }

        .subtitle {
            font-size: 12px;
            color: #475569;
            margin: 3px 0 0 0;
        }

        .meta-right {
            text-align: right;
            font-size: 10px;
            color: #475569;
        }

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin: 0 -8px 18px -8px;
        }

        .kpi-card {
            background-color: #FDF8E2;
            border: 1px solid #E2D9B3;
            border-radius: 6px;
            padding: 10px 12px;
            width: 33.33%;
            vertical-align: top;
        }

        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748B;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 15px;
            font-weight: bold;
            color: #233D7B;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #233D7B;
            margin: 16px 0 8px 0;
            padding-left: 8px;
            border-left: 4px solid #EAA549;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .data-table th {
            background-color: #233D7B;
            color: #FFFFFF;
            font-weight: bold;
            text-align: left;
            padding: 7px 10px;
            font-size: 10px;
        }

        .data-table td {
            border-bottom: 1px solid #E2E8F0;
            padding: 7px 10px;
            font-size: 10.5px;
        }

        .data-table tr:nth-child(even) td {
            background-color: #F8FAFC;
        }

        .badge-done {
            background-color: #DCFCE7;
            color: #166534;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .footer {
            margin-top: 24px;
            padding-top: 8px;
            border-top: 1px solid #CBD5E1;
            font-size: 9px;
            color: #64748B;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <p class="brand">Daily<span class="brand-accent">Plan</span></p>
                <p class="subtitle">Laporan Kemajuan & Produktivitas Mingguan</p>
            </td>
            <td class="meta-right" style="vertical-align: top;">
                <strong>Nama:</strong> {{ $user->name }}<br>
                <strong>Periode:</strong> {{ $periode }}<br>
                <strong>Dicetak:</strong> {{ $printedAt }}
            </td>
        </tr>
    </table>

    <!-- Ringkasan Statistik -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card">
                <div class="kpi-label">Tugas Selesai</div>
                <div class="kpi-value">{{ $tugasSelesai }} Tugas</div>
            </td>
            <td class="kpi-card">
                <div class="kpi-label">Durasi Belajar Terkumpul</div>
                <div class="kpi-value">{{ $durasiFormatted }}</div>
            </td>
            <td class="kpi-card">
                <div class="kpi-label">Kategori Aktif</div>
                <div class="kpi-value">{{ $categoryStats->count() }} Kategori</div>
            </td>
        </tr>
    </table>

    <!-- Rincian Harian -->
    <div class="section-title">Rincian Aktivitas Harian (Senin – Minggu)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 22%;">Hari</th>
                <th style="width: 28%;">Tanggal</th>
                <th style="width: 25%;">Tugas Selesai</th>
                <th style="width: 25%;">Durasi Belajar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dailyBreakdown as $day)
                <tr>
                    <td><strong>{{ $day['hari'] }}</strong></td>
                    <td>{{ $day['tanggal'] }}</td>
                    <td>{{ $day['jumlah'] }} tugas</td>
                    <td>{{ $day['durasi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Distribusi Kategori -->
    <div class="section-title">Distribusi Tugas per Kategori</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 65%;">Nama Kategori</th>
                <th style="width: 35%;">Total Tugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categoryStats as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->tasks_count }} tugas</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: #64748B;">Belum ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Riwayat Tugas Selesai -->
    <div class="section-title">Riwayat Tugas Selesai Minggu Ini</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 44%;">Judul Tugas</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 16%;">Tanggal</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($weeklyTasks as $index => $task)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->category->name ?? 'Tanpa Kategori' }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->date)->translatedFormat('d M Y') }}</td>
                    <td><span class="badge-done">Selesai</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748B;">Belum ada tugas yang diselesaikan
                        pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem DailyPlan • Tetap konsisten dan terarah setiap hari.
    </div>

</body>

</html>
