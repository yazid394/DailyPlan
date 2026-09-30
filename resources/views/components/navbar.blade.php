<header
    style="background: white; border-bottom: 1px solid #D9DDBE; padding: 16px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50;">

    <!-- Logo -->
    <div style="display: flex; align-items: center; gap: 12px;">
        <div
            style="width: 40px; height: 40px; background: #233D7B; border-radius: 8px; display: flex; justify-content: center; align-items: center; color: white; font-weight: 900;">
            DP</div>
        <div style="color: #233D7B; font-size: 22px; font-weight: 800;">DailyPlan</div>
    </div>

    <!-- Menu Navigasi -->
    <nav style="display: flex; gap: 8px;">
        <a href="{{ route('dashboard') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('dashboard') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('dashboard') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Beranda</a>

        <a href="{{ route('calender') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('calender') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('calender') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Kalender</a>

        <a href="{{ route('task') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('task') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('task') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Tugas</a>

        <a href="{{ route('progress') }}"
            style="padding: 10px 20px; background: {{ request()->routeIs('progress') ? '#FBE2A8' : 'transparent' }}; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: {{ request()->routeIs('progress') ? '800' : '600' }}; text-decoration: none; transition: background 0.3s;">Kemajuan</a>
    </nav>

    <!-- Profil Akun & Logout -->
    <div style="display: flex; align-items: center; gap: 16px;">
        <!-- Ikon Notifikasi -->
        <button
            style="width: 40px; height: 40px; background: #FDF8E2; border-radius: 50%; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center;">
            <svg width="20" height="20" fill="none" stroke="#233D7B" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </button>

        <!-- Data User Dinamis -->
        <img src="{{ asset('images/avatar-default.png') }}" alt="Profil"
            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />
        <div style="display: flex; flex-direction: column;">
            <div style="color: #233D7B; font-size: 14px; font-weight: 800;">{{ Auth::user()->name ?? 'Pengguna' }}
            </div>
            <div style="color: #78829B; font-size: 12px;">Pelajar produktif</div>
        </div>

        <!-- Tombol Logout (Wajib ada di Navbar) -->
        <form action="{{ route('logout') }}" method="POST" style="margin-left: 8px;">
            @csrf
            <button type="submit"
                style="background: none; border: none; cursor: pointer; color: #E95D62; font-size: 14px; font-weight: 700;">
                Keluar
            </button>
        </form>
    </div>
</header>
