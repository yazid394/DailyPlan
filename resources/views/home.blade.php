@extends('layout.app')

@section('content')
    <div style="font-family: 'Nunito', sans-serif; width: 100%; overflow-x: hidden;">

        <div style="background: #FDF8E2; padding: 40px 8%; min-height: 100vh; display: flex; flex-direction: column;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 60px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                    </div>
                    <div style="color: #233D7B; font-size: 24px; font-weight: 900;">DailyPlan</div>
                </div>
                <div
                    style="background: white; padding: 10px 20px; border-radius: 16px; box-shadow: 0px 8px 24px rgba(35, 61, 123, 0.1); display: flex; align-items: center; gap: 10px;">
                    <div style="width: 10px; height: 10px; background: #EAA549; border-radius: 50%;"></div>
                    <div style="color: #233D7B; font-size: 14px; font-weight: 700;">Planner harian yang seru</div>
                </div>
            </div>

            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 60px; flex: 1; align-items: center;">

                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div
                        style="background: #EAA549; padding: 8px 16px; border-radius: 16px; display: inline-flex; align-items: center; gap: 8px; align-self: flex-start;">
                        <span style="color: #233D7B; font-size: 14px; font-weight: 900; text-transform: uppercase;">Hari
                            rapi, pikiran happy</span>
                    </div>

                    <h1 style="color: #233D7B; font-size: clamp(48px, 5vw, 72px); font-weight: 900; line-height: 1.1;">Plan
                        Your Day, Your Way</h1>
                    <p
                        style="color: #233D7B; font-size: 20px; font-weight: 600; line-height: 1.5; opacity: 0.9; max-width: 540px;">
                        Tingkatkan produktivitas harianmu dengan rencana yang lebih terarah, dan jangan pernah melewatkan
                        deadline penting lagi.
                    </p>

                    <div style="display: flex; gap: 16px; margin-top: 16px; flex-wrap: wrap;">
                        <a href="{{ route('register') }}"
                            style="background: #EAA549; color: #233D7B; padding: 16px 32px; border-radius: 16px; font-size: 16px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 12px; box-shadow: 0px 8px 18px rgba(35, 61, 123, 0.15);">
                            Daftar Gratis ➔
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
                            style="width: 38px; height: 38px; background: #EAA549; border-radius: 12px; display: flex; justify-content: center; align-items: center; color: #233D7B; font-weight: bold;">
                            ✓</div>
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
                            <span style="color: #EAA549;">🔥</span> Fokus hari ini
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
                    style="background: white; padding: 28px; border-radius: 16px; box-shadow: 0 12px 32px rgba(35,61,123,0.1); display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div
                            style="width: 54px; height: 54px; background: #EAA549; border-radius: 16px; display: flex; justify-content: center; align-items: center; font-size: 24px;">
                            ☕</div>
                        <div style="color: #EAA549; font-size: 12px; font-weight: 900; text-transform: uppercase;">Jaga
                            Energi</div>
                    </div>
                    <h3 style="color: #233D7B; font-size: 23px; font-weight: 900;">Smart Break Reminder</h3>
                    <p style="color: #233D7B; font-size: 16px; font-weight: 600; opacity: 0.78; line-height: 1.5; flex: 1;">
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
                    style="background: white; padding: 28px; border-radius: 16px; box-shadow: 0 12px 32px rgba(35,61,123,0.1); display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div
                            style="width: 54px; height: 54px; background: #EAA549; border-radius: 16px; display: flex; justify-content: center; align-items: center; font-size: 24px;">
                            📅</div>
                        <div style="color: #EAA549; font-size: 12px; font-weight: 900; text-transform: uppercase;">Tepat
                            Waktu</div>
                    </div>
                    <h3 style="color: #233D7B; font-size: 23px; font-weight: 900;">Visual Deadline Tracker</h3>
                    <p style="color: #233D7B; font-size: 16px; font-weight: 600; opacity: 0.78; line-height: 1.5; flex: 1;">
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
                    style="background: white; padding: 28px; border-radius: 16px; box-shadow: 0 12px 32px rgba(35,61,123,0.1); display: flex; flex-direction: column; gap: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div
                            style="width: 54px; height: 54px; background: #EAA549; border-radius: 16px; display: flex; justify-content: center; align-items: center; font-size: 24px;">
                            📊</div>
                        <div style="color: #EAA549; font-size: 12px; font-weight: 900; text-transform: uppercase;">Makin
                            Konsisten</div>
                    </div>
                    <h3 style="color: #233D7B; font-size: 23px; font-weight: 900;">Statistik Produktivitas</h3>
                    <p style="color: #233D7B; font-size: 16px; font-weight: 600; opacity: 0.78; line-height: 1.5; flex: 1;">
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
                    <span style="color: #233D7B; font-size: 13px; font-weight: 900; text-transform: uppercase;">🚀 Mulai
                        perubahan kecil hari ini</span>
                </div>

                <h2
                    style="color: white; font-size: clamp(36px, 4vw, 58px); font-weight: 900; max-width: 850px; line-height: 1.1;">
                    Siap mengubah cara belajarmu?</h2>
                <p
                    style="color: white; font-size: 18px; font-weight: 600; opacity: 0.78; max-width: 640px; line-height: 1.5;">
                    Susun rencana, jaga fokus, dan rayakan setiap progres bersama DailyPlan.</p>

                <a href="{{ route('register') }}"
                    style="background: #EAA549; color: #233D7B; padding: 16px 32px; border-radius: 16px; font-size: 16px; font-weight: 800; text-decoration: none; display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                    Mulai Sekarang ➔
                </a>
            </div>

            <div
                style="border-top: 1px solid rgba(234, 165, 73, 0.3); padding: 30px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div
                        style="width: 36px; height: 36px; background: #EAA549; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-weight: bold; color: #233D7B;">
                        DP</div>
                    <div style="color: white; font-size: 20px; font-weight: 900;">DailyPlan</div>
                </div>
                <div style="color: white; font-size: 14px; font-weight: 600; opacity: 0.72;">
                    © 2026 DailyPlan by Yazid Diansyah. All rights reserved.
                </div>
            </div>

        </div>

    </div>
@endsection
