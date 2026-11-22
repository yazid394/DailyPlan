@extends('layout.app')

@section('content')
    <div class="dp-landing" style="font-family: 'Nunito Sans', sans-serif; width: 100%; overflow-x: hidden;">

        <div style="background: #FDF8E2; padding: 40px 8%; min-height: 100vh; display: flex; flex-direction: column;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 60px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    </div>
                    <div style="color: #233D7B; font-size: 24px; font-weight: 900;">DailyPlan</div>
                </div>
                <div
                    style="background: white; padding: 10px 20px; border-radius: 9px; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 10px; height: 10px; background: #EAA549; border-radius: 50%;"></div>
                    <div style="color: #233D7B; font-size: 14px; font-weight: 700;">Perencana harianmu</div>
                </div>
            </div>

            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 60px; flex: 1; align-items: center;">

                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div
                        style="background: #EAA549; padding: 8px 16px; border-radius: 16px; display: inline-flex; align-items: center; gap: 8px; align-self: flex-start;">
                        <span style="color: #233D7B; font-size: 14px; font-weight: 900; text-transform: uppercase;">Rencana
                            rapi, hari lebih tenang</span>
                    </div>

                    <h1 style="color: #233D7B; font-size: clamp(48px, 5vw, 72px); font-weight: 900; line-height: 1.1;">Plan
                        Your Day, Your Way</h1>
                    <p class="dp-landing-lead"
                        style="color: #5A6582; font-size: 16px; font-weight: 500; line-height: 1.65; max-width: 540px;">
                        Tingkatkan produktivitas harianmu dengan rencana yang lebih terarah, dan jangan pernah melewatkan
                        deadline penting lagi.
                    </p>

                    <div style="display: flex; gap: 16px; margin-top: 16px; flex-wrap: wrap;">
                        <a href="{{ route('register') }}"
                            style="background: #EAA549; color: #233D7B; padding: 16px 32px; border-radius: 9px; font-size: 16px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 12px;">
                            Daftar Gratis
                            <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14m-6-6l6 6-6 6"></path>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}"
                            style="background: transparent; color: #233D7B; padding: 16px 32px; border-radius: 16px; font-size: 16px; font-weight: 800; border: 2px solid #233D7B; text-decoration: none;">
                            Masuk
                        </a>
                    </div>

                    <!-- Social Proof -->
                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                        <div style="display: flex;">
                            <div
                                style="width: 32px; height: 32px; background: #EAA549; border-radius: 50%; border: 3px solid #FDF8E2;">
                            </div>
                            <div
                                style="width: 32px; height: 32px; background: #233D7B; border-radius: 50%; border: 3px solid #FDF8E2; margin-left: -12px;">
                            </div>
                            <div
                                style="width: 32px; height: 32px; background: white; border-radius: 50%; border: 2px solid #233D7B; margin-left: -12px;">
                            </div>
                        </div>
                        <span style="color: #233D7B; font-size: 14px; font-weight: 700;">Dipakai 12.000+ pelajar
                            produktif</span>
                    </div>
                </div>

                <div
                    style="position: relative; height: 500px; display: flex; justify-content: center; align-items: center;">
                    <div
                        style="width: 400px; height: 400px; background: #EAA549; opacity: 0.22; border-radius: 50%; position: absolute;">
                    </div>

                    <img src="{{ asset('images/maskot/Maskot2-DailyPlan.png') }}" alt="Maskot DailyPlan"
                        style=" position: relative; z-index: 5;" />

                    <div
                        style="position: absolute; bottom: 40px; left: 0; background: white; padding: 16px; border-radius: 16px; box-shadow: 0 12px 32px rgba(35,61,123,0.1); display: flex; align-items: center; gap: 12px; z-index: 10;">
                        <div
                            style="width: 38px; height: 38px; background: #EAA549; border-radius: 9px; display: flex; justify-content: center; align-items: center; color: #233D7B;">
                            <svg class="dp-icon" style="width: 18px; height: 18px;" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="color: #233D7B; font-size: 14px; font-weight: 900;">Tugas selesai!</div>
                            <div style="color: #233D7B; font-size: 12px; font-weight: 600; opacity: 0.7;">3 hari lebih awal
                            </div>
                        </div>
                    </div>

                    <div
                        style="position: absolute; top: 60px; right: 20px; background: white; padding: 16px; border-radius: 16px; box-shadow: 0 12px 32px rgba(35,61,123,0.1); z-index: 10;">
                        <div
                            style="color: #233D7B; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                            <svg class="dp-icon" style="width: 14px; height: 14px;" fill="none" stroke="#EAA549" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"></path>
                            </svg>
                            Fokus hari ini
                        </div>
                        <div style="color: #233D7B; font-size: 26px; font-weight: 900; margin-top: 4px;">87%</div>
                    </div>
                </div>
            </div>
        </div>

        <div
            style="background: #FDF8E2; padding: 80px 8%; display: flex; flex-direction: column; align-items: center; gap: 48px;">

            <div style="text-align: center; max-width: 720px;">
                <div
                    style="color: #EAA549; font-size: 14px; font-weight: 900; text-transform: uppercase; margin-bottom: 12px;">
                    Semua yang kamu butuhkan</div>
                <h2 style="color: #233D7B; font-size: 46px; font-weight: 900; margin-bottom: 12px;">Kenapa Pilih DailyPlan?
                </h2>
                <p style="color: #233D7B; font-size: 18px; font-weight: 600; opacity: 0.76; line-height: 1.5;">Lebih dari
                    sekadar to-do list—teman kecil untuk rutinitas yang lebih tenang, terarah, dan konsisten.</p>
            </div>

            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; width: 100%;">

                <div
                    style="background: white; padding: 28px; border: 1px solid rgba(35, 61, 123, 0.10); border-radius: 14px; display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div
                            style="width: 54px; height: 54px; background: #EAA549; border-radius: 12px; display: flex; justify-content: center; align-items: center;">
                            <svg class="dp-icon" style="width: 24px; height: 24px;" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 8h13v6a5 5 0 01-5 5H9a5 5 0 01-5-5V8zM17 9h2a3 3 0 010 6h-2M7 3v2m4-2v2"></path>
                            </svg>
                        </div>
                        <div style="color: #EAA549; font-size: 12px; font-weight: 900; text-transform: uppercase;">Jaga
                            Energi</div>
                    </div>
                    <h3 style="color: #233D7B; font-size: 23px; font-weight: 900;">Smart Break Reminder</h3>
                    <p class="dp-card-text" style="color: #233D7B; font-size: 16px; font-weight: 600; opacity: 0.78; line-height: 1.5; flex: 1;">
                        Pengingat istirahat cerdas membantu kamu menjaga fokus tanpa lupa memberi jeda untuk diri sendiri.
                    </p>
                    <div
                        style="background: #FDF8E2; padding: 14px; border-radius: 16px; display: flex; align-items: center; gap: 12px;">
                        <div
                            style="background: #EAA549; padding: 8px 12px; border-radius: 12px; color: #233D7B; font-weight: 900; font-size: 13px;">
                            05:00</div>
                        <div>
                            <div style="color: #233D7B; font-size: 13px; font-weight: 900; margin-bottom: 4px;">Waktunya
                                rehat</div>
                            <div style="width: 100px; height: 6px; background: #EAA549; border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>

                <div
                    style="background: white; padding: 28px; border: 1px solid rgba(35, 61, 123, 0.10); border-radius: 14px; display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div
                            style="width: 54px; height: 54px; background: #EAA549; border-radius: 12px; display: flex; justify-content: center; align-items: center;">
                            <svg class="dp-icon" style="width: 24px; height: 24px;" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div style="color: #EAA549; font-size: 12px; font-weight: 900; text-transform: uppercase;">Tepat
                            Waktu</div>
                    </div>
                    <h3 style="color: #233D7B; font-size: 23px; font-weight: 900;">Visual Deadline Tracker</h3>
                    <p class="dp-card-text" style="color: #233D7B; font-size: 16px; font-weight: 600; opacity: 0.78; line-height: 1.5; flex: 1;">
                        Lihat deadline secara visual, atur prioritas, dan selesaikan tugas sebelum waktunya habis.</p>
                    <div
                        style="background: #FDF8E2; padding: 14px; border-radius: 16px; display: flex; flex-direction: column; gap: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <div style="color: #233D7B; font-size: 13px; font-weight: 900;">Presentasi Biologi</div>
                            <div style="color: #EAA549; font-size: 12px; font-weight: 900;">2 hari</div>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <div style="flex: 1; height: 8px; background: #EAA549; border-radius: 10px;"></div>
                            <div style="width: 50px; height: 8px; background: white; border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>

                <div
                    style="background: white; padding: 28px; border: 1px solid rgba(35, 61, 123, 0.10); border-radius: 14px; display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div
                            style="width: 54px; height: 54px; background: #EAA549; border-radius: 12px; display: flex; justify-content: center; align-items: center;">
                            <svg class="dp-icon" style="width: 24px; height: 24px;" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 20V10m6 10V4m6 16v-7m4 7H2"></path>
                            </svg>
                        </div>
                        <div style="color: #EAA549; font-size: 12px; font-weight: 900; text-transform: uppercase;">Makin
                            Konsisten</div>
                    </div>
                    <h3 style="color: #233D7B; font-size: 23px; font-weight: 900;">Statistik Produktivitas</h3>
                    <p class="dp-card-text" style="color: #233D7B; font-size: 16px; font-weight: 600; opacity: 0.78; line-height: 1.5; flex: 1;">
                        Pantau progres harian dan temukan pola belajar terbaikmu lewat statistik yang mudah dipahami.</p>
                    <div
                        style="background: #FDF8E2; padding: 14px 18px; border-radius: 16px; display: flex; align-items: flex-end; gap: 8px; height: 74px;">
                        <div style="flex: 1; height: 20px; background: #233D7B; opacity: 0.45; border-radius: 6px 6px 0 0;">
                        </div>
                        <div style="flex: 1; height: 34px; background: #233D7B; opacity: 0.7; border-radius: 6px 6px 0 0;">
                        </div>
                        <div style="flex: 1; height: 50px; background: #EAA549; border-radius: 6px 6px 0 0;"></div>
                        <div style="flex: 1; height: 30px; background: #233D7B; opacity: 0.55; border-radius: 6px 6px 0 0;">
                        </div>
                        <div style="flex: 1; height: 42px; background: #233D7B; opacity: 0.85; border-radius: 6px 6px 0 0;">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div style="background: #233D7B; padding: 60px 8% 0; display: flex; flex-direction: column;">

            <div
                style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 24px; padding-bottom: 60px; position: relative;">
                <div
                    style="background: #EAA549; padding: 8px 16px; border-radius: 16px; display: inline-flex; align-items: center; gap: 8px;">
                    <span style="display: inline-flex; align-items: center; gap: 8px; color: #233D7B; font-size: 13px; font-weight: 900; text-transform: uppercase;">
                        <svg class="dp-icon" style="width: 14px; height: 14px;" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3z"></path>
                        </svg>
                        Mulai perubahan kecil hari ini
                    </span>
                </div>

                <h2
                    style="color: white; font-size: clamp(36px, 4vw, 58px); font-weight: 900; max-width: 850px; line-height: 1.1;">
                    Siap mengubah cara belajarmu?</h2>
                <p
                    style="color: white; font-size: 18px; font-weight: 600; opacity: 0.78; max-width: 640px; line-height: 1.5;">
                    Susun rencana, jaga fokus, dan rayakan setiap progres bersama DailyPlan.</p>

                <a href="{{ route('register') }}"
                    style="background: #EAA549; color: #233D7B; padding: 16px 32px; border-radius: 9px; font-size: 16px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; margin-top: 10px;">
                    Mulai Sekarang
                    <svg class="dp-icon" fill="none" stroke="#233D7B" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14m-6-6l6 6-6 6"></path>
                    </svg>
                </a>
            </div>

            <div
                style="border-top: 1px solid rgba(234, 165, 73, 0.3); padding: 30px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="{{ asset('images/icon/DailyPlan-polos.png') }}" alt="Logo DailyPlan"
                        style="width: 36px; height: 36px; object-fit: contain; border-radius: 8px; display: block; flex-shrink: 0;" />
                    <div style="color: white; font-size: 20px; font-weight: 900;">DailyPlan</div>
                </div>
                <div style="color: white; font-size: 14px; font-weight: 600; opacity: 0.72;">
                    © 2026 DailyPlan by Yazid Diansyah. All rights reserved.
                </div>
            </div>

        </div>

    </div>
@endsection
