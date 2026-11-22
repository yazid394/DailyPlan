{{-- Sumber tunggal aturan tipografi DailyPlan. Dipakai layout utama, auth, dan welcome. --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

<style>
    /* ---------------------------------------------------------------
       1. SETUP GLOBAL
       --------------------------------------------------------------- */
    html,
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    html,
    body,
    input,
    select,
    textarea,
    button {
        font-family: 'Nunito Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        color: #233D7B;
    }

    /* Form ikut mewarisi font dari induknya, jadi nggak balik ke Arial/Times. */
    input,
    select,
    textarea,
    button {
        font-family: inherit !important;
    }

    ::placeholder {
        color: #9AA3B8;
        opacity: 1;
    }

    /* ---------------------------------------------------------------
       2. HALAMAN INTERNAL (.dp-page)
       --------------------------------------------------------------- */
    .dp-page h1 {
        font-size: 28px !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
        line-height: 1.25 !important;
        color: #233D7B !important;
    }

    .dp-page h2,
    .dp-page h3 {
        font-size: 18px !important;
        font-weight: 800 !important;
        letter-spacing: -0.01em !important;
        line-height: 1.3 !important;
        color: #233D7B !important;
    }

    .dp-subtitle {
        font-size: 15px !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        color: #78829B !important;
    }

    .dp-item-title,
    .dp-page [data-task-title] {
        font-size: 16px !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
        color: #233D7B !important;
    }

    /* Metadata, tanggal, dan subteks memakai warna lembut yang sama. */
    .dp-meta,
    .dp-page [style*="color: #78829B"]:not(.dp-subtitle) {
        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.45 !important;
    }

    /* Angka, jam, dan tanggal sejajar vertikal. */
    .dp-num,
    .dp-page [style*="font-weight: 900"],
    input[type="date"],
    input[type="time"] {
        font-variant-numeric: tabular-nums;
    }

    /* Tombol aksi & badge kategori. */
    .dp-page button,
    .dp-page a[style*="background: #EAA549"],
    .dp-page [data-badge],
    .dp-page span[style*="border-radius: 999px"] {
        font-weight: 800 !important;
        letter-spacing: 0.01em !important;
        max-width: 100%;
    }

    .dp-page a,
    .dp-page button,
    .dp-page span,
    .dp-page div {
        overflow-wrap: break-word;
    }

    /* ---------------------------------------------------------------
       3. HALAMAN AUTH (.dp-auth)
       --------------------------------------------------------------- */
    .dp-auth h1 {
        font-size: 26px !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em !important;
        line-height: 1.25 !important;
        color: #233D7B !important;
    }

    .dp-auth .dp-subtitle {
        font-size: 14px !important;
        line-height: 1.5 !important;
        color: #78829B !important;
    }

    .dp-auth label {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #233D7B !important;
    }

    .dp-auth input,
    .dp-auth select,
    .dp-auth textarea {
        font-size: 14px !important;
        font-weight: 600 !important;
    }

    .dp-auth button[type="submit"] {
        font-size: 16px !important;
        font-weight: 800 !important;
        letter-spacing: 0.01em !important;
    }

    .dp-auth p {
        line-height: 1.5;
    }

    /* ---------------------------------------------------------------
       4. LANDING PAGE (.dp-landing)
       --------------------------------------------------------------- */
    .dp-landing h1 {
        font-weight: 900 !important;
        letter-spacing: -0.03em !important;
        line-height: 1.15 !important;
    }

    .dp-landing-lead {
        font-size: 16px !important;
        font-weight: 500 !important;
        line-height: 1.65 !important;
        color: #5A6582 !important;
    }

    .dp-landing h3,
    .dp-card-title {
        font-size: 18px !important;
        font-weight: 800 !important;
        letter-spacing: -0.01em !important;
        line-height: 1.3 !important;
    }

    .dp-card-text {
        font-size: 14px !important;
        line-height: 1.6 !important;
    }

    .dp-landing [style*="font-weight: 900"],
    .dp-landing .dp-num {
        font-variant-numeric: tabular-nums;
    }

    /* ---------------------------------------------------------------
       5. NAVBAR (.dp-nav) — hierarki menu nggak jomplang
       --------------------------------------------------------------- */
    .dp-nav a,
    .dp-nav button {
        letter-spacing: 0.01em;
    }

    .dp-nav nav a {
        font-size: 14px !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
    }

    .dp-nav nav a[style*="#FBE2A8"] {
        font-weight: 800 !important;
    }

    .dp-nav-brand {
        font-size: 20px !important;
        font-weight: 900 !important;
        letter-spacing: -0.02em !important;
        line-height: 1.3 !important;
    }

    .dp-nav-title {
        font-size: 15px !important;
        font-weight: 800 !important;
        letter-spacing: -0.01em !important;
    }

    .dp-notif-title {
        font-size: 13px !important;
        font-weight: 800 !important;
        line-height: 1.4 !important;
    }

    .dp-notif-sub {
        font-size: 12px !important;
        font-weight: 500 !important;
        line-height: 1.45 !important;
    }

    .dp-notif-pill,
    .dp-notif-action {
        font-size: 11px !important;
        font-weight: 800 !important;
        letter-spacing: 0.01em !important;
    }

    .dp-notif-label {
        font-size: 10px !important;
        font-weight: 800 !important;
        letter-spacing: 0.01em !important;
    }

    .dp-notif-footer {
        font-size: 13px !important;
        font-weight: 800 !important;
        letter-spacing: 0.01em !important;
    }

    .dp-nav-user {
        font-size: 14px !important;
        font-weight: 800 !important;
        line-height: 1.4 !important;
    }

    .dp-nav-logout {
        font-size: 14px !important;
        font-weight: 700 !important;
        letter-spacing: 0.01em !important;
    }

    #notifikasi-badge {
        font-variant-numeric: tabular-nums;
    }

    /* ---------------------------------------------------------------
        6. EDITORIAL CRAFT UI — permukaan, radius, dan interaksi
        --------------------------------------------------------------- */
    body {
        background: #FDF8E2;
    }

    /* Kartu statis: garis rambut, tanpa bayangan tebal.
       Dropdown dan toast sengaja dikecualikan karena memang elemen melayang. */
    .dp-surface,
    .dp-page div[style*="box-shadow"]:not([data-todo-dropdown]),
    .dp-landing div[style*="box-shadow"],
    .dp-auth div[style*="box-shadow"] {
        background: #FFFFFF;
        border: 1px solid rgba(35, 61, 123, 0.10) !important;
        border-radius: 14px !important;
        box-shadow: none !important;
    }

    /* Tombol, input, dan badge mengikuti disiplin radius yang sama. */
    .dp-page button,
    .dp-page input,
    .dp-page select,
    .dp-page textarea,
    .dp-page a[style*="background: #EAA549"],
    .dp-auth input,
    .dp-auth button[type="submit"] {
        border-radius: 9px !important;
        box-shadow: none !important;
    }

    [data-badge],
    .dp-page span[style*="border-radius: 999px"],
    .dp-nav span[style*="border-radius: 999px"] {
        border-radius: 6px !important;
        letter-spacing: 0.01em;
    }

    /* Dot badge memakai ::before supaya tetap ada saat JS mengganti teks. */
    [data-badge]::before {
        content: "";
        display: inline-block;
        width: 6px;
        height: 6px;
        margin-right: 7px;
        border-radius: 50%;
        background: var(--badge-dot, currentColor);
        vertical-align: middle;
    }

    .dp-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        flex-shrink: 0;
    }

    /* Kartu statistik: micro-label di atas, angka besar di bawah. */
    .dp-label {
        display: block;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #78829B !important;
        line-height: 1.35 !important;
    }

    .dp-number {
        display: block;
        font-size: 32px !important;
        font-weight: 800 !important;
        letter-spacing: -0.03em !important;
        font-variant-numeric: tabular-nums;
        color: #233D7B !important;
        line-height: 1.1 !important;
    }

    /* Baris tugas: hover halus, hangat, tanpa kejut visual. */
    [data-task-row],
    [data-task-card] {
        transition: background-color 0.15s ease, border-color 0.15s ease;
        border: 1px solid rgba(35, 61, 123, 0.10) !important;
    }

    [data-task-row]:hover,
    [data-task-card]:hover {
        background-color: #FAF6E6 !important;
        border-color: rgba(234, 165, 73, 0.45) !important;
    }

    /* Ikon aksi: garis tipis, ukuran seragam. */
    .dp-icon {
        width: 16px;
        height: 16px;
        stroke-width: 1.75;
        flex-shrink: 0;
        vertical-align: -3px;
    }
</style>
