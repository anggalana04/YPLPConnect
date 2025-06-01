document.addEventListener('DOMContentLoaded', function () {
    const toggleBtns = document.querySelectorAll('.toggle-status');

    toggleBtns.forEach(function (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const statusHead = this.closest('.status-head');
            const detailStatus = statusHead.querySelector('.detail-status');
            const arrowIcon = this.querySelector('.arrow-icon');

            const isAlreadyOpen = detailStatus.classList.contains('show');

            // Tutup semua dulu
            document.querySelectorAll('.detail-status.show').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.arrow-icon.rotate').forEach(el => el.classList.remove('rotate'));

            // Jika sebelumnya belum terbuka, buka; kalau sudah terbuka, jangan buka lagi (jadi toggle)
            if (!isAlreadyOpen) {
                detailStatus.classList.add('show');
                arrowIcon.classList.add('rotate');
            }
        });
    });
});