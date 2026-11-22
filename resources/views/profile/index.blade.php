@extends('layout.app')

@section('content')
    @php
        $user = Auth::user();
        $namaUser = $user->name ?? 'Ridaa Nurhayati';
        $emailUser = $user->email ?? 'ridaa@email.com';

        // Preferensi tidur diambil dari tabel `user_preferences`.
        $waktuTidur = $user?->preference?->default_sleep_start ?? '21:00';
        $waktuBangun = $user?->preference?->default_sleep_end ?? '04:00';
        $pengingatTidur = true;
    @endphp

    <x-navbar />
    <div class="dp-page"
        style="min-height: calc(100vh - 80px); width: 100%; background: #FDF8E2; padding: 36px 5%; box-sizing: border-box; font-family: 'Nunito Sans', sans-serif; display: flex; flex-direction: column; gap: 32px;">

        <!-- Header Halaman -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <h1 style="color: #233D7B; font-size: 32px; font-weight: 900; margin: 0;">Profil & Preferensi Tidur</h1>
            <p class="dp-subtitle" style="color: #78829B; font-size: 16px; margin: 0;">Kelola informasi pribadi dan jadwal istirahatmu.</p>
        </div>

        <!-- Notifikasi Sukses / Error -->
        @if (session('success'))
            <div
                style="background: #DDF3E6; border: 1px solid #62B58A; color: #1E4620; padding: 14px 18px; border-radius: 12px; font-size: 14px; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                style="background: #FCE1E1; border: 1px solid #E95D62; color: #9B1C1C; padding: 14px 18px; border-radius: 12px; font-size: 14px; font-weight: 700;">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Grid 2 Kolom Utama -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; align-items: start;">

            <!-- KOLOM KIRI: INFORMASI PROFIL -->
            <div
                class="dp-surface" style="padding: 32px; display: flex; flex-direction: column; gap: 24px;">
                <h2 style="color: #233D7B; font-size: 20px; font-weight: 800; margin: 0;">Informasi Profil</h2>

                <!-- Avatar Inisial Dinamis -->
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div
                        style="width: 72px; height: 72px; background: #FBE2A8; border-radius: 50%; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                        <span style="color: #233D7B; font-size: 28px; font-weight: 900;">{{ Auth::user()?->initials ?? 'U' }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <div style="color: #233D7B; font-size: 16px; font-weight: 800;">{{ Auth::user()->name }}</div>
                        <div style="color: #78829B; font-size: 13px; font-weight: 500;">Pelajar produktif</div>
                    </div>
                </div>

                <!-- Form Update Profil -->
                <form action="{{ route('profile.update') }}" method="POST"
                    style="display: flex; flex-direction: column; gap: 16px;">
                    @csrf
                    @method('PUT')

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="color: #233D7B; font-size: 14px; font-weight: 700;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $namaUser) }}" required
                            style="width: 100%; padding: 14px 16px; background: white; border-radius: 12px; border: 1px solid #D9DDBE; color: #47608F; font-size: 15px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="color: #233D7B; font-size: 14px; font-weight: 700;">Email</label>
                        <input type="email" name="email" value="{{ old('email', $emailUser) }}" required
                            style="width: 100%; padding: 14px 16px; background: white; border-radius: 12px; border: 1px solid #D9DDBE; color: #47608F; font-size: 15px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="color: #233D7B; font-size: 14px; font-weight: 700;">Kata Sandi Baru</label>
                        <input type="password" name="password" placeholder="••••••••••••" autocomplete="new-password"
                            style="width: 100%; padding: 14px 16px; background: white; border-radius: 12px; border: 1px solid #D9DDBE; color: #47608F; font-size: 15px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="color: #233D7B; font-size: 14px; font-weight: 700;">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••••••" autocomplete="new-password"
                            style="width: 100%; padding: 14px 16px; background: white; border-radius: 12px; border: 1px solid #D9DDBE; color: #47608F; font-size: 15px; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                    </div>

                    <button type="submit"
                        style="width: 100%; padding: 14px 24px; background: #EAA549; border: none; border-radius: 12px; color: #233D7B; font-size: 15px; font-weight: 800; font-family: 'Nunito Sans', sans-serif; cursor: pointer; margin-top: 8px;">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- KOLOM KANAN: PREFERENSI TIDUR -->
            <div
                class="dp-surface" style="padding: 32px; display: flex; flex-direction: column; gap: 24px;">

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color: #233D7B; font-size: 20px; font-weight: 800; margin: 0;">Preferensi Tidur</h2>
                    <!-- Ikon Bulan -->
                    <div
                        style="width: 36px; height: 36px; background: #FBE2A8; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                        <svg width="20" height="20" fill="none" stroke="#233D7B" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </div>
                </div>

                <form action="{{ route('profile.preferences.update') }}" method="POST"
                    style="display: flex; flex-direction: column; gap: 20px;">
                    @csrf
                    @method('PUT')

                    <div style="color: #47608F; font-size: 16px; font-weight: 700;">Jadwal Tidur</div>

                    <!-- Input Jam Tidur & Bangun -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="color: #233D7B; font-size: 14px; font-weight: 700;">Waktu Tidur</label>
                            <input type="time" id="bed_time" name="default_sleep_start"
                                value="{{ old('default_sleep_start', substr($waktuTidur, 0, 5)) }}"
                                onchange="hitungDurasiTidur()"
                                style="width: 100%; padding: 14px 16px; background: white; border-radius: 12px; border: 1px solid #D9DDBE; color: #233D7B; font-size: 16px; font-weight: 800; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="color: #233D7B; font-size: 14px; font-weight: 700;">Waktu Bangun</label>
                            <input type="time" id="wake_time" name="default_sleep_end"
                                value="{{ old('default_sleep_end', substr($waktuBangun, 0, 5)) }}"
                                onchange="hitungDurasiTidur()"
                                style="width: 100%; padding: 14px 16px; background: white; border-radius: 12px; border: 1px solid #D9DDBE; color: #233D7B; font-size: 16px; font-weight: 800; font-family: 'Nunito Sans', sans-serif; outline: none; box-sizing: border-box;" />
                        </div>
                    </div>

                    <!-- Banner Durasi Tidur Otomatis -->
                    <div
                        style="padding: 16px; background: #FDF8E2; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
                        <svg width="20" height="20" fill="none" stroke="#EAA549" stroke-width="2"
                            viewBox="0 0 24 24" style="flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div id="durasi_tidur_text" style="color: #233D7B; font-size: 15px; font-weight: 700;">
                            Durasi tidur ideal Anda: 7 jam
                        </div>
                    </div>

                    <!-- Toggle Switch Pengingat Tidur -->
                    <div
                        style="padding: 8px 0; display: flex; justify-content: space-between; align-items: center; gap: 16px;">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <div style="color: #233D7B; font-size: 15px; font-weight: 800;">Pengingat Tidur</div>
                            <div style="color: #78829B; font-size: 13px;">Kirim notifikasi 15 menit sebelum tidur.</div>
                        </div>

                        <label class="switch-toggle"
                            style="position: relative; display: inline-block; width: 48px; height: 28px; flex-shrink: 0; cursor: pointer;">
                            <input type="checkbox" name="sleep_reminder" value="1"
                                {{ $pengingatTidur ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;">
                            <span class="slider-toggle"></span>
                        </label>
                    </div>

                    <button type="submit"
                        style="width: 100%; padding: 14px 24px; background: #EAA549; border: none; border-radius: 12px; color: #233D7B; font-size: 15px; font-weight: 800; font-family: 'Nunito Sans', sans-serif; cursor: pointer;">
                        Simpan Jadwal
                    </button>
                </form>
            </div>

        </div>

        <!-- Tombol Keluar Akun di Bagian Bawah -->
        <div style="padding-top: 16px; display: flex; justify-content: center;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    style="padding: 14px 32px; background: #EAA549; border: none; border-radius: 9px; color: #233D7B; font-size: 15px; font-weight: 800; font-family: 'Nunito Sans', sans-serif; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" fill="none" stroke="#233D7B" stroke-width="2.2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar Akun
                </button>
            </form>
        </div>

    </div>

    <style>
        .slider-toggle {
            position: absolute;
            inset: 0;
            background-color: #D9DDBE;
            transition: .3s;
            border-radius: 24px;
        }

        .slider-toggle:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        .switch-toggle input:checked+.slider-toggle {
            background-color: #EAA549;
        }

        .switch-toggle input:checked+.slider-toggle:before {
            transform: translateX(20px);
        }
    </style>

    <script>
        function hitungDurasiTidur() {
            const bed = document.getElementById('bed_time').value;
            const wake = document.getElementById('wake_time').value;
            if (!bed || !wake) return;

            const [bH, bM] = bed.split(':').map(Number);
            const [wH, wM] = wake.split(':').map(Number);

            let menitTidur = bH * 60 + bM;
            let menitBangun = wH * 60 + wM;

            if (menitBangun <= menitTidur) {
                menitBangun += 24 * 60;
            }

            const selisihMenit = menitBangun - menitTidur;
            const jam = Math.floor(selisihMenit / 60);
            const menit = selisihMenit % 60;

            const label = menit > 0 ? `${jam} jam ${menit} menit` : `${jam} jam`;
            document.getElementById('durasi_tidur_text').innerText = `Durasi tidur ideal Anda: ${label}`;
        }
    </script>
@endsection
