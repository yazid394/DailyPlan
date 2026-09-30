<div
    style="min-height: 100vh; width: 100%; background: #FBF4CF; display: grid; grid-template-columns: 1fr 1fr; position: relative; font-family: 'Inter', sans-serif; overflow: hidden;">

    <!-- Teks Logo Kiri Atas -->
    <div
        style="position: absolute; top: 24px; left: 32px; color: #233D7B; font-size: 24px; font-weight: bold; z-index: 10;">
        DailyPlan
    </div>

    <!-- Kolom Kiri (Area Gambar Maskot) - Terkunci 50% Layar Kiri -->
    <div style="display: flex; justify-content: center; align-items: center; padding: 40px; position: relative;">
        <div style="position: relative; width: 100%; max-width: 450px; height: 500px;">
            <!-- Pastikan gambar logo-badge.png dan notebook-character.png ada di public/images/ -->
            <img src="{{ asset('images/icon/DailyPlan.png') }}" alt="Logo DailyPlan"
                style="position: absolute; top: -150px; right: -150px; width: 220px; z-index: 1;" />
            <img src="{{ asset('images/maskot/Maskot-DailyPlan.png') }}" alt="Maskot DailyPlan"
                style="position: absolute; bottom: 0; left: -20px; width: 650px; z-index: 2;" />
        </div>
    </div>

    <!-- Kolom Kanan (Area Form Register) - Terkunci 50% Layar Kanan -->
    <div style="display: flex; justify-content: center; align-items: center; padding: 40px;">
        <div style="width: 100%; max-width: 400px; text-align: center;">

            <h1 style="color: #16386C; font-size: 36px; font-weight: 700; margin-bottom: 8px;">Halo! Yuk Mulai Gabung.
            </h1>
            <p style="color: #000; font-size: 16px; margin-bottom: 40px;">Buat akun barumu sekarang dan mulai atur
                jadwal harianmu jadi lebih rapi!</p>

            <form action="{{ route('register.store') }}" method="POST"
                style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                @if ($errors->any())
                    <div
                        style="background: #FDE8E8; border: 1px solid #E05555; color: #9B1C1C; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; text-align: left;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Input Nama Lengkap (Ditambah Ikon User) -->
                <div style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #888;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </span>
                    <input type="text" name="name" placeholder="Nama Lengkap" required
                        value="{{ old('name') }}" autocomplete="name" autofocus
                        style="width: 100%; height: 60px; padding-left: 56px; border: 1px solid rgba(22, 56, 108, 0.3); border-radius: 8px; font-size: 16px; color: #16386C; outline: none; box-sizing: border-box;" />
                </div>

                <!-- Input Alamat Email -->
                <div style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #888;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </span>
                    <input type="email" name="email" placeholder="Alamat Email" required
                        value="{{ old('email') }}" autocomplete="email"
                        style="width: 100%; height: 60px; padding-left: 56px; border: 1px solid rgba(22, 56, 108, 0.3); border-radius: 8px; font-size: 16px; color: #16386C; outline: none; box-sizing: border-box;" />
                </div>

                <!-- Input Kata Sandi -->
                <div style="position: relative; width: 100%;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #888;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </span>
                    <input type="password" name="password" placeholder="Kata Sandi" required minlength="8"
                        autocomplete="new-password"
                        style="width: 100%; height: 60px; padding-left: 56px; border: 1px solid rgba(22, 56, 108, 0.3); border-radius: 8px; font-size: 16px; color: #16386C; outline: none; box-sizing: border-box;" />
                </div>
                <p style="margin-top: -8px; font-size: 12px; color: #666; text-align: left;">Kata sandi minimal 8
                    karakter.</p>

                <!-- Tombol Buat Akun -->
                <button type="submit"
                    style="width: 100%; height: 60px; background: #E5B448; color: #16386C; font-size: 20px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; margin-top: 10px; box-sizing: border-box; transition: background 0.3s;">
                    Buat Akun
                </button>
            </form>

            <!-- Teks Pindah ke Login -->
            <p style="margin-top: 30px; font-size: 14px; color: #000;">
                Sudah punya akun? <a href="{{ route('login') }}"
                    style="color: #16386C; font-weight: 700; text-decoration: none;">Masuk di sini</a>
            </p>

        </div>
    </div>

</div>
