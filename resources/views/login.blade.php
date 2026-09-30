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
            <img src="{{ asset('images/icon/DailyPlan.png') }}" alt="Logo DailyPlan"
                style="position: absolute; top: 40px; right: -20px; width: 220px; z-index: 1;" />
            <img src="{{ asset('images/maskot/Maskot-DailyPlan.png') }}" alt="Maskot DailyPlan"
                style="position: absolute; bottom: -60px; left: -50px; width: 650px; z-index: 2;" />
        </div>
    </div>

    <div style="display: flex; justify-content: center; align-items: center; padding: 40px;">
        <div
            style="width: 100%; max-width: 440px; background: #ffffff; padding: 48px 40px; border-radius: 24px; box-shadow: 0px 20px 40px rgba(22, 56, 108, 0.08); text-align: center; box-sizing: border-box;">
            <h1 style="color: #16386C; font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px;">
                Selamat Datang!</h1>
            <p style="color: #4A5568; font-size: 15px; margin-bottom: 36px; line-height: 1.6;">Sudah siap bikin hari ini
                lebih produktif?<br>Masuk ke akunmu dulu, ya!</p>

            <form action="{{ route('login.store') }}" method="POST"
                style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                @if ($errors->any())
                    <div
                        style="background: #FEF2F2; border: 1px solid #F87171; color: #991B1B; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; text-align: left; display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div style="position: relative; width: 100%;">
                    <span
                        style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #9CA3AF;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </span>
                    <input type="email" name="email" placeholder="Alamat Email" required value="{{ old('email') }}"
                        autocomplete="email" autofocus
                        style="width: 100%; height: 52px; padding-left: 54px; padding-right: 16px; background: #F9FAFB; border: 1px solid rgba(22, 56, 108, 0.2); border-radius: 12px; font-size: 15px; color: #16386C; outline: none; box-sizing: border-box; transition: all 0.3s;" />
                </div>

                <div style="position: relative; width: 100%;">
                    <span
                        style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #9CA3AF;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </span>
                    <input type="password" name="password" placeholder="Kata Sandi" required
                        autocomplete="current-password"
                        style="width: 100%; height: 52px; padding-left: 54px; padding-right: 16px; background: #F9FAFB; border: 1px solid rgba(22, 56, 108, 0.2); border-radius: 12px; font-size: 15px; color: #16386C; outline: none; box-sizing: border-box; transition: all 0.3s;" />
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 4px;">
                    <label
                        style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #4A5568; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                            style="width: 18px; height: 18px; accent-color: #E5B448; cursor: pointer;" />
                        Ingat saya
                    </label>
                    <a href="#"
                        style="font-size: 14px; color: #16386C; font-weight: 700; text-decoration: none;">Lupa
                        Sandi?</a>
                </div>

                <button type="submit"
                    style="width: 100%; height: 52px; background: #E5B448; color: #16386C; font-size: 18px; font-weight: 800; border: none; border-radius: 12px; cursor: pointer; margin-top: 8px; box-shadow: 0px 8px 16px rgba(229, 180, 72, 0.3); box-sizing: border-box; transition: background 0.3s, transform 0.2s;">
                    Masuk
                </button>
            </form>

            <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid rgba(22, 56, 108, 0.1);">
                <p style="font-size: 15px; color: #4A5568; margin: 0;">
                    Belum punya akun? <a href="{{ route('register') }}"
                        style="color: #16386C; font-weight: 800; text-decoration: none;">Daftar di sini</a>
                </p>
            </div>

        </div>
    </div>

</div>
