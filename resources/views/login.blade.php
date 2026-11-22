@include('components.typography')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

<style>
    .auth-shell {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .auth-art {
        min-height: 520px;
        gap: 24px;
    }

    .auth-brand {
        font-family: 'Plus Jakarta Sans', 'Nunito Sans', sans-serif;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #233D7B;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .auth-brand:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }

    .auth-brand:focus-visible {
        outline: 2px solid #EAA549;
        outline-offset: 4px;
        border-radius: 6px;
    }

    @media (max-width: 900px) {
        .auth-shell {
            grid-template-columns: 1fr !important;
        }

        .auth-art {
            min-height: auto;
            padding: 88px 24px 32px !important;
        }

        .auth-art > div {
            max-width: 300px !important;
        }
    }

    @media (max-width: 480px) {
        .auth-art > div {
            max-width: 260px !important;
        }

        .auth-brand {
            top: 20px !important;
            left: 24px !important;
            font-size: 30px;
        }
    }
</style>

<div class="auth-shell dp-auth"
    style="min-height: 100vh; width: 100%; background: #FBF4CF; position: relative; font-family: 'Nunito Sans', sans-serif; overflow: hidden;">

    <a href="{{ url('/') }}" class="auth-brand" aria-label="Kembali ke halaman utama DailyPlan"
        style="position: absolute; top: 28px; left: 40px; z-index: 10;">
        Daily<span style="color: #EAA549;">Plan</span>
    </a>

    <!-- Kolom kiri: maskot DailyPlan -->
    <div class="auth-art"
        style="display: flex; justify-content: center; align-items: center; padding: 48px 40px; box-sizing: border-box;">
        <div style="width: 100%; max-width: 320px; margin: 0 auto; display: flex; justify-content: center; align-items: center;">
            <img src="{{ asset('images/maskot/Maskot-DailyPlan.png') }}" alt="Maskot DailyPlan"
                style="display: block; width: 100%; max-width: 320px; height: auto; object-fit: contain; filter: drop-shadow(0 16px 24px rgba(22, 56, 108, 0.16));" />
        </div>
    </div>

    <!-- Kolom kanan: form masuk -->
    <div style="display: flex; justify-content: center; align-items: center; padding: 40px;">
        <div
            style="width: 100%; max-width: 440px; background: #ffffff; padding: 44px 40px; text-align: center; box-sizing: border-box;">
            <h1 style="color: #16386C; font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px;">
                Selamat Datang!</h1>
            <p class="dp-subtitle" style="color: #4A5568; font-size: 15px; margin-bottom: 32px; line-height: 1.6;">Sudah siap bikin hari ini
                lebih produktif?<br>Masuk ke akunmu dulu, ya!</p>

            <form action="{{ route('login.store') }}" method="POST"
                style="display: flex; flex-direction: column; gap: 18px;">
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
                        style="width: 100%; height: 52px; padding-left: 54px; padding-right: 16px; background: #F9FAFB; border: 1px solid rgba(22, 56, 108, 0.2); border-radius: 12px; font-size: 15px; color: #16386C; outline: none; box-sizing: border-box; transition: border-color 0.3s, box-shadow 0.3s;" />
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
                        style="width: 100%; height: 52px; padding-left: 54px; padding-right: 16px; background: #F9FAFB; border: 1px solid rgba(22, 56, 108, 0.2); border-radius: 12px; font-size: 15px; color: #16386C; outline: none; box-sizing: border-box; transition: border-color 0.3s, box-shadow 0.3s;" />
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
                    style="width: 100%; height: 52px; background: #EAA549; color: #233D7B; font-size: 18px; font-weight: 800; border: none; border-radius: 9px; cursor: pointer; margin-top: 6px; box-sizing: border-box; transition: background-color 0.15s ease;">
                    Masuk
                </button>
            </form>

            <div style="margin-top: 28px; padding-top: 22px; border-top: 1px solid rgba(22, 56, 108, 0.1);">
                <p style="font-size: 15px; color: #4A5568; margin: 0;">
                    Belum punya akun? <a href="{{ route('register') }}"
                        style="color: #16386C; font-weight: 800; text-decoration: none;">Daftar di sini</a>
                </p>
            </div>

        </div>
    </div>

</div>
