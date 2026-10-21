/**
 * Laporan Rumah Tahfidz - Global Application Scripts
 * Termasuk Jam & Tanggal Real-Time Admin (Hari, Tanggal, Jam WIB)
 */

document.addEventListener('DOMContentLoaded', () => {
    initAdminLiveClock();
});

function initAdminLiveClock() {
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    function update() {
        const now = new Date();
        const dayName = days[now.getDay()];
        const dateStr = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
        const pad = (n) => String(n).padStart(2, '0');
        const timeStr = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());

        // 1. Update elemen header admin (jika ada)
        const hDay = document.getElementById('live-header-day');
        const hDate = document.getElementById('live-header-date');
        const hTime = document.getElementById('live-header-time');
        if (hDay) hDay.textContent = dayName;
        if (hDate) hDate.textContent = dateStr;
        if (hTime) hTime.textContent = timeStr + ' WIB';

        // 2. Update elemen dashboard admin (jika ada)
        const dDay = document.getElementById('dashboard-clock-day');
        const dDate = document.getElementById('dashboard-clock-date');
        const dTime = document.getElementById('dashboard-clock-time');
        if (dDay) dDay.textContent = dayName;
        if (dDate) dDate.textContent = dateStr;
        if (dTime) dTime.innerHTML = timeStr + ' <small>WIB</small>';

        // 3. Update elemen halaman pendamping (jika ada)
        const pDay = document.getElementById('pendamping-clock-day');
        const pDate = document.getElementById('pendamping-clock-date');
        const pTime = document.getElementById('pendamping-clock-time');
        if (pDay) pDay.textContent = dayName;
        if (pDate) pDate.textContent = dateStr;
        if (pTime) pTime.innerHTML = timeStr + ' <small>WIB</small>';
    }

    update();
    setInterval(update, 1000);

    // Tombol Cepat: Gunakan Waktu Sekarang untuk Input Waktu Setoran
    const btnNow = document.getElementById('btn-now-waktu-setoran');
    const inputWaktu = document.getElementById('waktu_setoran');
    if (btnNow && inputWaktu) {
        btnNow.addEventListener('click', () => {
            const now = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            const localIso = now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()) + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
            inputWaktu.value = localIso;
            if (window.showToast) {
                window.showToast('Waktu setoran disesuaikan ke waktu saat ini.');
            }
        });
    }
}
