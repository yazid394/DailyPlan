@extends('layout.app')

@section('content')
<div style="min-height: 100vh; width: 100%; background: #FDF8E2; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column;">

    <!-- NAVBAR -->
    <header style="background: white; border-bottom: 1px solid #D9DDBE; padding: 16px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 50;">
        <!-- Logo -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; background: #233D7B; border-radius: 8px; display: flex; justify-content: center; align-items: center; color: white; font-weight: 900;">DP</div>
            <div style="color: #233D7B; font-size: 22px; font-weight: 800;">DailyPlan</div>
        </div>

        <!-- Menu Navigasi -->
        <nav style="display: flex; gap: 8px;">
            <a href="{{ route('dashboard') }}" style="padding: 10px 20px; background: #FBE2A8; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: 800; text-decoration: none;">Beranda</a>
            <a href="#" style="padding: 10px 20px; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.3s;">Kalender</a>
            <a href="#" style="padding: 10px 20px; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.3s;">Tugas</a>
            <a href="#" style="padding: 10px 20px; border-radius: 99px; color: #233D7B; font-size: 14px; font-weight: 600; text-decoration: none; transition: background 0.3s;">Kemajuan</a>
        </nav>

        <!-- Profil Akun -->
        <div style="display: flex; align-items: center; gap: 16px;">
            <!-- Ikon Notifikasi -->
            <button style="width: 40px; height: 40px; background: #FDF8E2; border-radius: 50%; border: none; cursor: pointer; display: flex; justify-content: center; align-items: center;">
                <svg width="20" height="20" fill="none" stroke="#233D7B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </button>
            <!-- Data User Dinamis dari Laravel Auth -->
            <img src="{{ asset('images/avatar-default.png') }}" alt="Profil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />
            <div style="display: flex; flex-direction: column;">
                <div style="color: #233D7B; font-size: 14px; font-weight: 800;">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                <div style="color: #78829B; font-size: 12px;">Pelajar produktif</div>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main style="padding: 40px 5%; display: flex; flex-direction: column; gap: 32px; flex: 1;">

        <!-- Header & Tambah Tugas -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <!-- Mengambil nama depan user -->
                <h1 style="color: #233D7B; font-size: 32px; font-weight: 800; margin-bottom: 8px;">Selamat Pagi, {{ explode(' ', Auth::user()->name ?? 'Bro')[0] }}!</h1>
                <!-- Tanggal Dinamis -->
                <p style="color: #78829B; font-size: 15px; margin: 0;">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} • Yuk, buat hari ini berarti.</p>
            </div>
            <button style="padding: 14px 24px; background: #EAA549; color: #233D7B; font-size: 15px; font-weight: 800; border: none; border-radius: 12px; cursor: pointer; box-shadow: 0px 8px 16px rgba(35, 61, 123, 0.1); transition: transform 0.2s;">
                + Tambah Tugas
            </button>
        </div>

        <!-- Kartu Ringkasan (Grid Responsif) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            <!-- Gambaran Umum -->
            <div style="background: white; padding: 28px; border-radius: 20px; box-shadow: 0px 8px 24px rgba(35, 61, 123, 0.06); display: flex; flex-direction: column; justify-content: center;">
                <h2 style="color: #233D7B; font-size: 20px; font-weight: 800; margin-bottom: 24px;">Gambaran Umum Hari Ini</h2>
                <div style="display: flex; justify-content: space-around; align-items: center;">
                    <div style="text-align: center;"><div style="color: #233D7B; font-size: 32px; font-weight: 900;">5</div><div style="color: #78829B; font-size: 13px;">Tugas</div></div>
                    <div style="text-align: center;"><div style="color: #233D7B; font-size: 32px; font-weight: 900;">2</div><div style="color: #78829B; font-size: 13px;">Selesai</div></div>
                    <div style="text-align: center;"><div style="color: #233D7B; font-size: 32px; font-weight: 900;">3</div><div style="color: #78829B; font-size: 13px;">Pending</div></div>
                </div>
            </div>

            <!-- Kartu Motivasi/Progress -->
            <div style="background: white; padding: 28px; border-radius: 20px; box-shadow: 0px 8px 24px rgba(35, 61, 123, 0.06); display: flex; align-items: center; gap: 24px;">
                <!-- Lingkaran Progress -->
                <div style="width: 80px; height: 80px; border-radius: 50%; border: 8px solid #EAA549; display: flex; justify-content: center; align-items: center; color: #233D7B; font-size: 20px; font-weight: 900;">
                    80%
                </div>
                <div>
                    <h3 style="color: #233D7B; font-size: 18px; font-weight: 800; margin-bottom: 4px;">Gokil, {{ explode(' ', Auth::user()->name ?? 'Bro')[0] }}!!</h3>
                    <p style="color: #78829B; font-size: 14px; margin: 0; line-height: 1.5;">Sedikit lagi menuju target harianmu.</p>
                </div>
            </div>
        </div>

        <!-- Daftar Tugas -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="color: #233D7B; font-size: 22px; font-weight: 800; margin: 0;">To-Do List</h2>
                <select style="background: transparent; color: #47608F; font-size: 14px; font-weight: 700; border: none; outline: none; cursor: pointer;">
                    <option>Semua tugas</option>
                    <option>Selesai</option>
                    <option>Pending</option>
                </select>
            </div>

            <!-- List Item 1 (Selesai) -->
            <div style="background: white; padding: 20px 24px; border-radius: 16px; display: flex; align-items: center; gap: 16px; box-shadow: 0px 4px 12px rgba(35, 61, 123, 0.03);">
                <input type="checkbox" checked style="width: 22px; height: 22px; accent-color: #233D7B; cursor: pointer;" />
                <div style="flex: 1; color: #233D7B; font-size: 16px; font-weight: 600; text-decoration: line-through; opacity: 0.7;">Rapat kelompok Biologi</div>
                <div style="color: #78829B; font-size: 14px; font-weight: 600;">09:00 - 10:00</div>
                <div style="width: 12px; height: 12px; background: #E95D62; border-radius: 50%;"></div>
                <button style="background: none; border: none; cursor: pointer; color: #78829B; font-size: 20px; font-weight: bold;">⋮</button>
            </div>

            <!-- List Item 2 (Belum Selesai) -->
            <div style="background: white; padding: 20px 24px; border-radius: 16px; display: flex; align-items: center; gap: 16px; box-shadow: 0px 4px 12px rgba(35, 61, 123, 0.03);">
                <input type="checkbox" style="width: 22px; height: 22px; accent-color: #233D7B; cursor: pointer;" />
                <div style="flex: 1; color: #233D7B; font-size: 16px; font-weight: 600;">Kerjakan latihan Matematika</div>
                <div style="color: #78829B; font-size: 14px; font-weight: 600;">13:00 - 14:30</div>
                <div style="width: 12px; height: 12px; background: #F2C453; border-radius: 50%;"></div>
                <button style="background: none; border: none; cursor: pointer; color: #78829B; font-size: 20px; font-weight: bold;">⋮</button>
            </div>

            <!-- List Item 3 -->
            <div style="background: white; padding: 20px 24px; border-radius: 16px; display: flex; align-items: center; gap: 16px; box-shadow: 0px 4px 12px rgba(35, 61, 123, 0.03);">
                <input type="checkbox" style="width: 22px; height: 22px; accent-color: #233D7B; cursor: pointer;" />
                <div style="flex: 1; color: #233D7B; font-size: 16px; font-weight: 600;">Baca materi Sejarah</div>
                <div style="color: #78829B; font-size: 14px; font-weight: 600;">16:00 - 17:00</div>
                <div style="width: 12px; height: 12px; background: #F2C453; border-radius: 50%;"></div>
                <button style="background: none; border: none; cursor: pointer; color: #78829B; font-size: 20px; font-weight: bold;">⋮</button>
            </div>

        </div>
    </main>
</div>
@endsection
