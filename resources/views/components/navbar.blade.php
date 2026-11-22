@php
    // Komponen ini dipakai di banyak halaman, jadi jaga-jaga kalau composer belum jalan.
    $daftarNotifikasi = $notifications ?? collect();
    $jumlahAktif = $unreadCount ?? $daftarNotifikasi->count();
@endphp

<header class="dp-nav"
    style="background: white; border-bottom: 1px solid rgba(35, 61, 123, 0.10); padding: 16px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50;">

    <!-- Logo -->
    <a href="{{ route('dashboard') }}" aria-label="Ke Beranda DailyPlan"
        style="display: flex; align-items: center; gap: 12px; text-decoration: none; letter-spacing: normal;">
        <img src="{{ asset('images/icon/DailyPlan-polos.png') }}" alt="Logo DailyPlan"
            style="width: 40px; height: 40px; object-fit: contain; border-radius: 8px; display: block; flex-shrink: 0;" />
        <div class="dp-nav-brand" style="color: #233D7B; font-size: 22px; font-weight: 800;">DailyPlan</div>
    </a>

    <!-- Menu Navigasi -->
    <nav style="display: flex; gap: 8px;">
        <a href="{{ route('dashboard') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('dashboard') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('dashboard') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Beranda</a>

        <a href="{{ route('calender.index') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('calender.*') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('calender.*') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Kalender</a>

        <a href="{{ route('task.index') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('task.*') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('task.*') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Tugas</a>

        <a href="{{ route('progress.index') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('progress.*') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('progress.*') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Kemajuan</a>
    </nav>

    <!-- Profil Akun & Logout -->
    <div style="display: flex; align-items: center; gap: 16px;">
        <!-- Ikon Notifikasi + Dropdown Pengingat -->
        <div id="notifikasi-wrapper" style="position: relative;">
            <button type="button" id="notifikasi-trigger" aria-label="Buka notifikasi" aria-expanded="false"
                style="width: 40px; height: 40px; background: #FDF8E2; border-radius: 50%; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center; position: relative;">
                <svg width="20" height="20" fill="none" stroke="#233D7B" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>

                @if ($jumlahAktif > 0)
                    <span id="notifikasi-badge" data-notifikasi-badge
                        style="position: absolute; top: -4px; right: -4px; min-width: 18px; height: 18px; padding: 0 4px; background: #EAA549; color: #233D7B; border-radius: 999px; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(35, 61, 123, 0.18);">
                        {{ $jumlahAktif }}
                    </span>
                @endif
            </button>

            <div id="notifikasi-dropdown"
                style="display: none; position: absolute; right: 0; top: 48px; width: 340px; max-width: 90vw; background: white; border-radius: 18px; box-shadow: 0px 12px 28px rgba(35, 61, 123, 0.16); z-index: 100; overflow: hidden; font-family: 'Nunito Sans', sans-serif;">

                <!-- Header -->
                <div style="padding: 16px 18px; border-bottom: 1px solid #FDF8E2; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span class="dp-nav-title" style="color: #233D7B; font-size: 16px; font-weight: 800; flex: 1;">Notifikasi</span>
                    <span class="dp-notif-pill" style="background: #FDF8E2; color: #233D7B; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 999px;">{{ $jumlahAktif }} Aktif</span>
                    <button type="button" id="notifikasi-tandai-dibaca" class="dp-notif-action"
                        style="background: none; border: none; color: #47608F; font-size: 11px; font-weight: 800; cursor: pointer; padding: 4px;">
                        Tandai dibaca
                    </button>
                </div>

                <!-- Body: daftar pengingat asli dari database -->
                <div style="max-height: 320px; overflow-y: auto;">
                    @forelse ($daftarNotifikasi as $notif)
                        <a href="{{ $notif['link'] }}"
                            style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 18px; border-bottom: 1px solid #FDF8E2; text-decoration: none;">
                            <span style="width: 34px; height: 34px; border-radius: 8px; background: {{ $notif['warna'] }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #233D7B;">
                                @if ($notif['jenis'] === 'sleep')
                                    <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20.4 15.4A9 9 0 018.6 3.6 9 9 0 1012 21a9 9 0 008.4-5.6z"></path>
                                    </svg>
                                @elseif ($notif['jenis'] === 'deadline')
                                    <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 8v5m0 3h.01M10.3 3.9L2.6 17.3A2 2 0 004.3 20h15.4a2 2 0 001.7-2.7L13.7 3.9a2 2 0 00-3.4 0z"></path>
                                    </svg>
                                @else
                                    <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v5l3 2"></path>
                                    </svg>
                                @endif
                            </span>

                            <span style="display: flex; flex-direction: column; gap: 3px; flex: 1; min-width: 0;">
                                <span class="dp-notif-title" style="color: #233D7B; font-size: 13px; font-weight: 800;">{{ $notif['judul'] }}</span>
                                <span class="dp-notif-sub" style="color: #78829B; font-size: 11px;">{{ $notif['subteks'] }}</span>
                            </span>

                            <span class="dp-notif-label" style="background: {{ $notif['warna'] }}; color: #233D7B; font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 999px; white-space: nowrap;">
                                {{ $notif['label'] }}
                            </span>
                        </a>
                    @empty
                        <div class="dp-meta" style="margin: 12px; padding: 22px 16px; background: #FDF8E2; border-radius: 12px; text-align: center; color: #78829B; font-size: 13px; line-height: 1.5;">
                            Semua beres! Belum ada notifikasi baru hari ini.
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <a href="{{ route('task.index') }}" class="dp-notif-footer"
                    style="display: block; padding: 14px 18px; text-align: center; background: #FDF8E2; color: #233D7B; font-size: 13px; font-weight: 800; text-decoration: none;">
                    Lihat Semua Tugas →
                </a>
            </div>
        </div>

        <!-- Avatar Inisial User Dinamis (klik menuju halaman profil) -->
        <a href="{{ route('profile.index') }}" title="Lihat profil" aria-label="Lihat profil"
            style="display: flex; border-radius: 50%; text-decoration: none; transition: transform 0.2s;"
            onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
            <div role="img" aria-label="Avatar {{ Auth::user()->name ?? 'Pengguna' }}"
                style="width: 40px; height: 40px; border-radius: 50%; background: #233D7B; color: #FBE2A8; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: 'Nunito Sans', sans-serif; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ Auth::user()?->initials ?? 'U' }}
            </div>
        </a>
        <div style="display: flex; flex-direction: column;">
            <div class="dp-nav-user" style="color: #233D7B; font-size: 14px; font-weight: 800;">{{ Auth::user()->name ?? 'Pengguna' }}
            </div>
            <div class="dp-meta" style="color: #78829B; font-size: 12px;">Pelajar produktif</div>
        </div>

        <!-- Tombol Logout (Wajib ada di Navbar) -->
        <form action="{{ route('logout') }}" method="POST" style="margin-left: 8px;">
            @csrf
            <button type="submit" class="dp-nav-logout"
                style="background: none; border: none; cursor: pointer; color: #E95D62; font-size: 14px; font-weight: 700;">
                Keluar
            </button>
        </form>
    </div>
</header>

<script>
    (function () {
        // Navbar bisa kepanggil lebih dari sekali, jadi pasang listener cuma sekali.
        if (window.__dailyplanNotifikasiSiap) {
            return;
        }
        window.__dailyplanNotifikasiSiap = true;

        const wrapper = document.getElementById('notifikasi-wrapper');
        const panel = document.getElementById('notifikasi-dropdown');
        const trigger = document.getElementById('notifikasi-trigger');
        const badge = document.getElementById('notifikasi-badge');
        const tombolTandai = document.getElementById('notifikasi-tandai-dibaca');

        if (!wrapper || !panel || !trigger) {
            return;
        }

        // "Tandai dibaca" hanya berlaku hari ini, di browser yang ini.
        const kunciPenyimpanan = 'dailyplan_notifikasi_dibaca_{{ now()->toDateString() }}';

        function sembunyikanBadge() {
            if (badge) {
                badge.style.display = 'none';
            }
        }

        try {
            if (localStorage.getItem(kunciPenyimpanan) === '1') {
                sembunyikanBadge();
            }
        } catch (error) {
            // localStorage bisa diblokir browser; nggak apa-apa.
        }

        trigger.addEventListener('click', function (event) {
            event.stopPropagation(); // biar listener "klik di luar" nggak langsung nutup
            const akanDibuka = panel.style.display !== 'block';
            panel.style.display = akanDibuka ? 'block' : 'none';
            trigger.setAttribute('aria-expanded', akanDibuka ? 'true' : 'false');
        });

        document.addEventListener('click', function (event) {
            if (wrapper.contains(event.target)) {
                return;
            }
            panel.style.display = 'none';
            trigger.setAttribute('aria-expanded', 'false');
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                panel.style.display = 'none';
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        if (tombolTandai) {
            tombolTandai.addEventListener('click', function (event) {
                event.stopPropagation();
                try {
                    localStorage.setItem(kunciPenyimpanan, '1');
                } catch (error) {
                    // Walau gagal disimpan, badge tetap disembunyikan untuk sesi ini.
                }
                sembunyikanBadge();
            });
        }
    })();
</script>
