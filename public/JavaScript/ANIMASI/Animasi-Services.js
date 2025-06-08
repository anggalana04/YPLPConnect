document.addEventListener('DOMContentLoaded', function () {
  const serviceBoxes = document.querySelectorAll('#services .sub-box-konten');

  function checkInView() {
    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

    serviceBoxes.forEach(box => {
      const rect = box.getBoundingClientRect();
      const isVisible = rect.top < viewportHeight - 50 && rect.bottom > 50;

      if (isVisible) {
        // Reset animasi jika sudah pernah tampil
        box.classList.remove('show');
        void box.offsetWidth; // Force reflow
        box.classList.add('show');
      } else {
        // Hanya hapus .show tanpa transisi (hilangkan animasi keluar)
        box.classList.remove('show');
      }
    });
  }

  window.addEventListener('scroll', checkInView);
  checkInView();
});
