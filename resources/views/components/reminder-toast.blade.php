<!-- Container Pop-up Melayang di Pojok Kanan Bawah -->
<div id="dailyplan-toast-container"
    style="position: fixed; bottom: 28px; right: 28px; z-index: 9999; display: flex; flex-direction: column; gap: 14px; max-width: 380px; width: calc(100vw - 40px); pointer-events: none; font-family: 'Nunito Sans', sans-serif;">
</div>

<style>
    @keyframes toastSlideIn {
        from {
            opacity: 0;
            transform: translateX(40px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    @keyframes toastSlideOut {
        from {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        to {
            opacity: 0;
            transform: translateX(40px) scale(0.95);
        }
    }

    .dp-toast-card {
        pointer-events: auto;
        background: #FFFFFF;
        border: 1px solid rgba(35, 61, 123, 0.10);
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0px 16px 36px rgba(35, 61, 123, 0.18);
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        overflow: hidden;
        animation: toastSlideIn 0.35s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .dp-toast-card.closing {
        animation: toastSlideOut 0.25s ease-in forwards;
    }
</style>

<script>
    // Judul/catatan tugas berasal dari database, jadi amankan dulu sebelum masuk innerHTML.
    function escapeHtml(teks) {
        return String(teks ?? '').replace(/[&<>"']/g, function (karakter) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            }[karakter];
        });
    }

    /**
     * Fungsi global untuk memunculkan Custom Alert Pengingat Tugas
     */
    function showTaskReminder({
        judul = 'Pengingat Tugas!',
        pesan = 'Waktunya mengerjakan tugasmu sekarang.',
        waktu = 'Sekarang',
        url = "{{ route('task.index') }}",
        durasi = 8000 // Otomatis hilang setelah 8 detik (set 0 kalau mau manual tutup)
    }) {
        const container = document.getElementById('dailyplan-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'dp-toast-card';

        toast.innerHTML = `
            <!-- Garis Aksen Kiri -->
            <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background: #EAA549;"></div>

            <!-- Gambar Custom (Maskot DailyPlan) -->
            <div style="width: 56px; height: 56px; background: #FDF8E2; border-radius: 16px; display: flex; justify-content: center; align-items: center; flex-shrink: 0; padding: 4px; box-sizing: border-box;">
                <img src="{{ asset('images/maskot/Maskot-DailyPlan.png') }}" alt="Maskot"
                    style="width: 100%; height: 100%; object-fit: contain;" />
            </div>

            <!-- Konten Teks & Tombol -->
            <div style="flex: 1; display: flex; flex-direction: column; gap: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; background: #FBE2A8; color: #233D7B; font-size: 11px; font-weight: 800; border-radius: 6px; letter-spacing: 0.01em; line-height: 1.3; font-variant-numeric: tabular-nums;">
                        <svg class="dp-icon" style="width: 14px; height: 14px;" fill="none" stroke="#233D7B" viewBox='0 0 24 24' stroke-linecap='round' stroke-linejoin='round' aria-hidden='true'>
                            <circle cx='12' cy='12' r='9'></circle>
                            <path d='M12 7v5l3 2'></path>
                        </svg>
                        ${escapeHtml(waktu)}
                    </span>
                    <button type="button" onclick="tutupToast(this)" aria-label="Tutup pengingat"
                        style="background: none; border: none; color: #78829B; line-height: 0; cursor: pointer; padding: 2px; display: flex; align-items: center; justify-content: center;">
                        <svg class="dp-icon" fill="none" stroke="#78829B" viewBox='0 0 24 24' stroke-linecap='round' stroke-linejoin='round' aria-hidden='true'>
                            <path d='M6 6l12 12M18 6L6 18'></path>
                        </svg>
                    </button>
                </div>

                <div style="color: #233D7B; font-size: 15px; font-weight: 800; line-height: 1.35;">
                    ${escapeHtml(judul)}
                </div>

                <p style="color: #47608F; font-size: 13px; font-weight: 500; line-height: 1.5; margin: 0;">
                    ${escapeHtml(pesan)}
                </p>

                <div style="display: flex; gap: 8px; margin-top: 6px;">
                    <a href="${escapeHtml(url)}"
                        style="padding: 6px 14px; background: #EAA549; color: #233D7B; font-size: 12px; font-weight: 800; border-radius: 9px; text-decoration: none; letter-spacing: 0.01em; line-height: 1.3;">
                        Kerjakan
                    </a>
                    <button type="button" onclick="tutupToast(this)"
                        style="padding: 6px 12px; background: #FDF8E2; color: #233D7B; font-size: 12px; font-weight: 800; border: none; border-radius: 9px; cursor: pointer; letter-spacing: 0.01em; line-height: 1.3;">
                        Mengerti
                    </button>
                </div>
            </div>
        `;

        container.appendChild(toast);

        // Opsional: Mainkan suara ding halus kalau ada file audio
        // new Audio("{{ asset('sounds/reminder.mp3') }}").play().catch(() => {});

        if (durasi > 0) {
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.add('closing');
                    setTimeout(() => toast.remove(), 250);
                }
            }, durasi);
        }
    }

    function tutupToast(btn) {
        const card = btn.closest('.dp-toast-card');
        if (!card) return;
        card.classList.add('closing');
        setTimeout(() => card.remove(), 250);
    }

    // --- Pemantau waktu pengingat ---
    // Data ini dikirim AppServiceProvider, sudah berupa array bersih.
    const daftarPengingat = @json($reminders ?? []);
    const tanggalReminder = '{{ now()->toDateString() }}';

    function jalankanPengecekanPengingat() {
        if (!Array.isArray(daftarPengingat)) {
            return;
        }

        daftarPengingat.forEach(function (pengingat) {
            if (!pengingat.start_time) {
                return;
            }

            // Satu tugas cukup sekali sehari, walau dicek tiap 10 detik.
            const kunciTersimpan = `dailyplan_reminded_${tanggalReminder}_${pengingat.id}`;

            let sudahDiberitahu = false;
            try {
                sudahDiberitahu = localStorage.getItem(kunciTersimpan) === '1';
            } catch (error) {
                // localStorage bisa diblokir browser; anggap belum pernah diingatkan.
            }
            if (sudahDiberitahu) {
                return;
            }

            const bagianJam = String(pengingat.start_time).split(':').map(Number);
            const jam = bagianJam[0];
            const menit = bagianJam[1];

            if (!Number.isFinite(jam) || !Number.isFinite(menit)) {
                return;
            }

            const jamTeks = String(jam).padStart(2, '0');
            const menitTeks = String(menit).padStart(2, '0');

            // +07:00 biar titik waktu tetap WIB walau browser user di zona lain.
            const startTimestamp = new Date(`${tanggalReminder}T${jamTeks}:${menitTeks}:00+07:00`).getTime();
            const menitPengingat = Number(pengingat.reminder_minutes) || 15;
            const triggerTimestamp = startTimestamp - menitPengingat * 60 * 1000;
            const sekarang = Date.now();

            // Rentang toleran: dari menit trigger sampai 15 menit setelah jam mulai.
            // Jadi tetap muncul walau user baru buka browser setelah lewat menit trigger.
            if (sekarang >= triggerTimestamp && sekarang <= startTimestamp + 15 * 60 * 1000) {
                try {
                    localStorage.setItem(kunciTersimpan, '1');
                } catch (error) {
                    // Walau gagal disimpan, toast tetap ditampilkan.
                }

                showTaskReminder({
                    judul: pengingat.title,
                    pesan: `${pengingat.category} • Mulai pukul ${pengingat.start_time}`,
                    waktu: pengingat.start_time,
                    url: "{{ route('task.index') }}",
                    durasi: 12000,
                });
            }
        });
    }

    function pasangPemantauPengingat() {
        jalankanPengecekanPengingat(); // jalan sekali pas halaman siap
        setInterval(jalankanPengecekanPengingat, 10000); // lalu tiap 10 detik
    }

    // Guard supaya interval nggak dobel kalau komponen kepanggil lebih dari sekali.
    if (!window.__dailyplanReminderSiap) {
        window.__dailyplanReminderSiap = true;

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', pasangPemantauPengingat);
        } else {
            pasangPemantauPengingat();
        }
    }
</script>
